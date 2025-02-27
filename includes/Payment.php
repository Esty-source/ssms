<?php
class Payment {
    private $conn;
    private $stripeSecretKey;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->stripeSecretKey = 'YOUR_STRIPE_SECRET_KEY'; // Replace with actual key in production
    }

    public function createPayment($bookingId, $userId, $amount, $paymentMethod) {
        $this->conn->beginTransaction();

        try {
            // Create payment record
            $stmt = $this->conn->prepare("
                INSERT INTO payments (booking_id, user_id, amount, payment_method, status)
                VALUES (?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([$bookingId, $userId, $amount, $paymentMethod]);
            $paymentId = $this->conn->lastInsertId();

            // Process payment through payment gateway
            $paymentResult = $this->processPayment($amount, $paymentMethod);

            if ($paymentResult['success']) {
                // Update payment status
                $stmt = $this->conn->prepare("
                    UPDATE payments 
                    SET status = 'completed', transaction_id = ?
                    WHERE id = ?
                ");
                $stmt->execute([$paymentResult['transaction_id'], $paymentId]);

                // Update booking status
                $stmt = $this->conn->prepare("
                    UPDATE bookings 
                    SET status = 'confirmed'
                    WHERE id = ?
                ");
                $stmt->execute([$bookingId]);

                $this->conn->commit();
                return ['success' => true, 'payment_id' => $paymentId];
            } else {
                throw new Exception($paymentResult['message']);
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getPayment($id) {
        $stmt = $this->conn->prepare("
            SELECT p.*, b.start_date, b.end_date, c.size, c.location
            FROM payments p
            JOIN bookings b ON p.booking_id = b.id
            JOIN containers c ON b.container_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserPayments($userId) {
        $stmt = $this->conn->prepare("
            SELECT p.*, b.start_date, b.end_date, c.size, c.location
            FROM payments p
            JOIN bookings b ON p.booking_id = b.id
            JOIN containers c ON b.container_id = c.id
            WHERE p.user_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function refundPayment($paymentId) {
        $this->conn->beginTransaction();

        try {
            $payment = $this->getPayment($paymentId);
            if (!$payment) {
                throw new Exception('Payment not found');
            }

            if ($payment['status'] !== 'completed') {
                throw new Exception('Payment cannot be refunded');
            }

            // Process refund through payment gateway
            $refundResult = $this->processRefund($payment['transaction_id']);

            if ($refundResult['success']) {
                // Update payment status
                $stmt = $this->conn->prepare("
                    UPDATE payments 
                    SET status = 'refunded'
                    WHERE id = ?
                ");
                $stmt->execute([$paymentId]);

                // Update booking status
                $stmt = $this->conn->prepare("
                    UPDATE bookings 
                    SET status = 'cancelled'
                    WHERE id = ?
                ");
                $stmt->execute([$payment['booking_id']]);

                // Update container status
                $stmt = $this->conn->prepare("
                    UPDATE containers c
                    JOIN bookings b ON c.id = b.container_id
                    SET c.status = 'available'
                    WHERE b.id = ?
                ");
                $stmt->execute([$payment['booking_id']]);

                $this->conn->commit();
                return ['success' => true];
            } else {
                throw new Exception($refundResult['message']);
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function processPayment($amount, $paymentMethod) {
        // Implement actual payment gateway integration here
        // This is a dummy implementation for demonstration
        try {
            // Simulate payment processing
            $success = true; // In real implementation, this would depend on the payment gateway response
            $transactionId = 'TR' . time() . rand(1000, 9999);

            return [
                'success' => $success,
                'transaction_id' => $transactionId
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function processRefund($transactionId) {
        // Implement actual refund processing here
        // This is a dummy implementation for demonstration
        try {
            // Simulate refund processing
            $success = true; // In real implementation, this would depend on the payment gateway response

            return [
                'success' => $success
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
