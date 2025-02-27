<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllUsers($role = null) {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        // Check if email already exists
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email already exists'];
        }

        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $stmt = $this->conn->prepare("
            INSERT INTO users (name, email, password, phone, address, role) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        try {
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['password'],
                $data['phone'] ?? null,
                $data['address'] ?? null,
                $data['role'] ?? 'customer'
            ]);
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateUser($id, $data) {
        // Check if email exists for another user
        if (isset($data['email'])) {
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$data['email'], $id]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Email already exists'];
            }
        }

        $sql = "UPDATE users SET ";
        $params = [];
        $updateFields = [];

        foreach ($data as $key => $value) {
            if ($key === 'password') {
                if (!empty($value)) {
                    $updateFields[] = "password = ?";
                    $params[] = password_hash($value, PASSWORD_DEFAULT);
                }
            } elseif (in_array($key, ['name', 'email', 'phone', 'address', 'role'])) {
                $updateFields[] = "$key = ?";
                $params[] = $value;
            }
        }

        if (empty($updateFields)) {
            return ['success' => false, 'message' => 'No valid fields to update'];
        }

        $sql .= implode(', ', $updateFields);
        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->conn->prepare($sql);
        
        try {
            $stmt->execute($params);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function deleteUser($id) {
        // Check if user has any active bookings
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) FROM bookings 
            WHERE user_id = ? 
            AND status IN ('pending', 'confirmed')
        ");
        $stmt->execute([$id]);
        
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Cannot delete user with active bookings'];
        }

        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        
        try {
            $stmt->execute([$id]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getUserBookings($id) {
        $stmt = $this->conn->prepare("
            SELECT b.*, c.size, c.location
            FROM bookings b
            JOIN containers c ON b.container_id = c.id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserPayments($id) {
        $stmt = $this->conn->prepare("
            SELECT p.*, b.start_date, b.end_date, c.size, c.location
            FROM payments p
            JOIN bookings b ON p.booking_id = b.id
            JOIN containers c ON b.container_id = c.id
            WHERE b.user_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
