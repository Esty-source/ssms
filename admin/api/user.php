<?php
require_once '../../includes/config.php';
require_once '../../includes/Auth.php';
require_once '../../includes/User.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$user = new User($conn);

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get user details
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            exit();
        }

        $userData = $user->getUserById($_GET['id']);
        if ($userData) {
            // Get user's bookings and payments
            $bookings = $user->getUserBookings($_GET['id']);
            $payments = $user->getUserPayments($_GET['id']);
            
            echo json_encode([
                'success' => true,
                'user' => $userData,
                'bookings' => $bookings,
                'payments' => $payments
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
        break;

    case 'POST':
        // Create new user
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'phone' => $_POST['phone'] ?? null,
            'address' => $_POST['address'] ?? null,
            'role' => $_POST['role'] ?? 'customer'
        ];

        $result = $user->createUser($data);
        echo json_encode($result);
        break;

    case 'PUT':
        // Update existing user
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            exit();
        }

        parse_str(file_get_contents("php://input"), $_PUT);
        
        $data = [
            'name' => $_PUT['name'],
            'email' => $_PUT['email'],
            'password' => $_PUT['password'] ?? null,
            'phone' => $_PUT['phone'] ?? null,
            'address' => $_PUT['address'] ?? null
        ];

        // Remove empty values
        $data = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });

        $result = $user->updateUser($_GET['id'], $data);
        echo json_encode($result);
        break;

    case 'DELETE':
        // Delete user
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            exit();
        }

        $result = $user->deleteUser($_GET['id']);
        echo json_encode($result);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>
