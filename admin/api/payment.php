<?php
require_once '../../includes/config.php';
require_once '../../includes/Auth.php';
require_once '../../includes/Payment.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$payment = new Payment($conn);

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get payment details
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Payment ID is required']);
            exit();
        }

        // Get payment with additional details
        $stmt = $conn->prepare("
            SELECT p.*, b.start_date, b.end_date, 
                   c.size, c.location,
                   u.name as customer_name, u.email as customer_email
            FROM payments p
            JOIN bookings b ON p.booking_id = b.id
            JOIN containers c ON b.container_id = c.id
            JOIN users u ON p.user_id = u.id
            WHERE p.id = ?
        ");
        $stmt->execute([$_GET['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['success' => true, 'payment' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Payment not found']);
        }
        break;

    case 'PUT':
        // Process refund
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Payment ID is required']);
            exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['action']) || $data['action'] !== 'refund') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            exit();
        }

        $result = $payment->refundPayment($_GET['id']);
        echo json_encode($result);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>
