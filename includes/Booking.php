<?php
class Booking {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createBooking($userId, $containerId, $startDate, $endDate, $totalAmount) {
        // Start transaction
        $this->conn->beginTransaction();

        try {
            // Check if container is still available
            $stmt = $this->conn->prepare("
                SELECT status FROM containers 
                WHERE id = ? AND status = 'available'
            ");
            $stmt->execute([$containerId]);
            
            if (!$stmt->fetch()) {
                throw new Exception("Container is no longer available");
            }

            // Create booking
            $stmt = $this->conn->prepare("
                INSERT INTO bookings (user_id, container_id, start_date, end_date, total_amount, status)
                VALUES (?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([$userId, $containerId, $startDate, $endDate, $totalAmount]);
            $bookingId = $this->conn->lastInsertId();

            // Update container status
            $stmt = $this->conn->prepare("
                UPDATE containers 
                SET status = 'occupied'
                WHERE id = ?
            ");
            $stmt->execute([$containerId]);

            $this->conn->commit();
            return ['success' => true, 'booking_id' => $bookingId];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getBooking($id) {
        $stmt = $this->conn->prepare("
            SELECT b.*, c.size, c.location, u.name as user_name, u.email
            FROM bookings b
            JOIN containers c ON b.container_id = c.id
            JOIN users u ON b.user_id = u.id
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserBookings($userId) {
        $stmt = $this->conn->prepare("
            SELECT b.*, c.size, c.location
            FROM bookings b
            JOIN containers c ON b.container_id = c.id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBookingStatus($id, $status) {
        $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($status, $validStatuses)) {
            return ['success' => false, 'message' => 'Invalid status'];
        }

        $this->conn->beginTransaction();

        try {
            $stmt = $this->conn->prepare("
                UPDATE bookings 
                SET status = ?
                WHERE id = ?
            ");
            $stmt->execute([$status, $id]);

            // If booking is cancelled or completed, make container available again
            if (in_array($status, ['cancelled', 'completed'])) {
                $stmt = $this->conn->prepare("
                    UPDATE containers c
                    JOIN bookings b ON c.id = b.container_id
                    SET c.status = 'available'
                    WHERE b.id = ?
                ");
                $stmt->execute([$id]);
            }

            $this->conn->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getActiveBookings() {
        $stmt = $this->conn->prepare("
            SELECT b.*, c.size, c.location, u.name as user_name, u.email
            FROM bookings b
            JOIN containers c ON b.container_id = c.id
            JOIN users u ON b.user_id = u.id
            WHERE b.status IN ('pending', 'confirmed')
            ORDER BY b.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calculateBookingDuration($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);
        return ceil($interval->days / 30); // Convert days to months, rounding up
    }

    public function validateBookingDates($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $today = new DateTime();

        if ($start < $today) {
            return ['valid' => false, 'message' => 'Start date cannot be in the past'];
        }

        if ($end <= $start) {
            return ['valid' => false, 'message' => 'End date must be after start date'];
        }

        $interval = $start->diff($end);
        if ($interval->days < 30) {
            return ['valid' => false, 'message' => 'Minimum booking duration is 1 month'];
        }

        return ['valid' => true];
    }
}
