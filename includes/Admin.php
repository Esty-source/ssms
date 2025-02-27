<?php
class Admin {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getDashboardStats() {
        try {
            // Total containers
            $stmt = $this->conn->query("SELECT COUNT(*) FROM containers");
            $totalContainers = $stmt->fetchColumn();

            // Available containers
            $stmt = $this->conn->query("SELECT COUNT(*) FROM containers WHERE status = 'available'");
            $availableContainers = $stmt->fetchColumn();

            // Active bookings
            $stmt = $this->conn->query("SELECT COUNT(*) FROM bookings WHERE status IN ('pending', 'confirmed')");
            $activeBookings = $stmt->fetchColumn();

            // Total revenue this month
            $stmt = $this->conn->prepare("
                SELECT COALESCE(SUM(amount), 0) 
                FROM payments 
                WHERE status = 'completed' 
                AND MONTH(created_at) = MONTH(CURRENT_DATE())
                AND YEAR(created_at) = YEAR(CURRENT_DATE())
            ");
            $stmt->execute();
            $monthlyRevenue = $stmt->fetchColumn();

            // Recent bookings
            $stmt = $this->conn->query("
                SELECT b.*, u.name as user_name, c.size, c.location
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                JOIN containers c ON b.container_id = c.id
                ORDER BY b.created_at DESC
                LIMIT 5
            ");
            $recentBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'total_containers' => $totalContainers,
                'available_containers' => $availableContainers,
                'active_bookings' => $activeBookings,
                'monthly_revenue' => $monthlyRevenue,
                'recent_bookings' => $recentBookings
            ];
        } catch (PDOException $e) {
            // Return default values on error
            return [
                'total_containers' => 0,
                'available_containers' => 0,
                'active_bookings' => 0,
                'monthly_revenue' => 0,
                'recent_bookings' => [],
                'error' => $e->getMessage()
            ];
        }
    }

    public function getAllUsers($filters = []) {
        try {
            $sql = "SELECT * FROM users WHERE 1=1";
            $params = [];

            if (isset($filters['role']) && $filters['role']) {
                $sql .= " AND role = ?";
                $params[] = $filters['role'];
            }

            if (isset($filters['search']) && $filters['search']) {
                $sql .= " AND (name LIKE ? OR email LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            $sql .= " ORDER BY created_at DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updateUserRole($userId, $newRole) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET role = ? WHERE id = ?");
            $stmt->execute([$newRole, $userId]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getBookingStats($period = 'month') {
        try {
            $sql = "SELECT 
                        DATE(created_at) as date,
                        COUNT(*) as total_bookings,
                        SUM(total_amount) as revenue
                    FROM bookings
                    WHERE ";

            switch ($period) {
                case 'week':
                    $sql .= "created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)";
                    break;
                case 'month':
                    $sql .= "created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
                    break;
                case 'year':
                    $sql .= "created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 YEAR)";
                    break;
                default:
                    $sql .= "created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
            }

            $sql .= " GROUP BY DATE(created_at) ORDER BY date";

            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getContainerUtilization() {
        try {
            $sql = "SELECT 
                        c.size,
                        COUNT(*) as total,
                        SUM(CASE WHEN c.status = 'available' THEN 1 ELSE 0 END) as available,
                        SUM(CASE WHEN c.status = 'occupied' THEN 1 ELSE 0 END) as occupied
                    FROM containers c
                    GROUP BY c.size
                    ORDER BY c.size";

            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Return default data if no containers exist
            if (empty($result)) {
                return [
                    ['size' => '10', 'total' => 0, 'available' => 0, 'occupied' => 0],
                    ['size' => '20', 'total' => 0, 'available' => 0, 'occupied' => 0],
                    ['size' => '40', 'total' => 0, 'available' => 0, 'occupied' => 0]
                ];
            }
            
            return $result;
        } catch (PDOException $e) {
            // Return default data on error
            return [
                ['size' => '10', 'total' => 0, 'available' => 0, 'occupied' => 0],
                ['size' => '20', 'total' => 0, 'available' => 0, 'occupied' => 0],
                ['size' => '40', 'total' => 0, 'available' => 0, 'occupied' => 0],
                'error' => $e->getMessage()
            ];
        }
    }

    public function getPaymentStats($period = 'month') {
        try {
            $sql = "SELECT 
                        DATE(created_at) as date,
                        COUNT(*) as total_payments,
                        SUM(amount) as total_amount,
                        payment_method,
                        status
                    FROM payments
                    WHERE ";

            switch ($period) {
                case 'week':
                    $sql .= "created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)";
                    break;
                case 'month':
                    $sql .= "created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
                    break;
                case 'year':
                    $sql .= "created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 YEAR)";
                    break;
                default:
                    $sql .= "created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
            }

            $sql .= " GROUP BY DATE(created_at), payment_method, status ORDER BY date";

            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
