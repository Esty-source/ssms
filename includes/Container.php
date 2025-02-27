<?php
class Container {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllContainers($filters = []) {
        $sql = "SELECT * FROM containers WHERE 1=1";
        $params = [];

        if (isset($filters['size']) && $filters['size']) {
            $sql .= " AND size = ?";
            $params[] = $filters['size'];
        }

        if (isset($filters['status']) && $filters['status']) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        if (isset($filters['max_price']) && $filters['max_price']) {
            $sql .= " AND price <= ?";
            $params[] = $filters['max_price'];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableContainers() {
        $stmt = $this->conn->prepare("SELECT * FROM containers WHERE status = 'available'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContainerById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM containers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createContainer($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO containers (size, price, location, status, description) 
            VALUES (?, ?, ?, ?, ?)
        ");

        try {
            $stmt->execute([
                $data['size'],
                $data['price'],
                $data['location'],
                $data['status'] ?? 'available',
                $data['description'] ?? ''
            ]);
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateContainer($id, $data) {
        $sql = "UPDATE containers SET ";
        $params = [];
        $updateFields = [];

        foreach ($data as $key => $value) {
            if (in_array($key, ['size', 'price', 'location', 'status', 'description'])) {
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

    public function deleteContainer($id) {
        // Check if container has any active bookings
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) FROM bookings 
            WHERE container_id = ? 
            AND status IN ('pending', 'confirmed')
        ");
        $stmt->execute([$id]);
        
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Cannot delete container with active bookings'];
        }

        $stmt = $this->conn->prepare("DELETE FROM containers WHERE id = ?");
        
        try {
            $stmt->execute([$id]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateContainerStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE containers SET status = ? WHERE id = ?");
        
        try {
            $stmt->execute([$status, $id]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getContainerSizes() {
        return [
            'small' => '5x5',
            'medium' => '10x10',
            'large' => '10x20',
            'xlarge' => '20x20'
        ];
    }

    public function calculatePrice($size, $duration) {
        $basePrices = [
            'small' => 50,   // £50/month for 5x5
            'medium' => 100, // £100/month for 10x10
            'large' => 175,  // £175/month for 10x20
            'xlarge' => 250  // £250/month for 20x20
        ];

        // Apply discounts for longer durations
        $discounts = [
            3 => 0.05,  // 5% discount for 3+ months
            6 => 0.10,  // 10% discount for 6+ months
            12 => 0.15  // 15% discount for 12+ months
        ];

        $basePrice = $basePrices[$size] ?? 0;
        $discount = 0;

        foreach ($discounts as $months => $discountRate) {
            if ($duration >= $months) {
                $discount = $discountRate;
            }
        }

        $totalPrice = $basePrice * $duration * (1 - $discount);
        return round($totalPrice, 2);
    }
}
