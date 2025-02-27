<?php
require_once '../../includes/config.php';
require_once '../../includes/Auth.php';
require_once '../../includes/Contact.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$contact = new Contact($conn);

// Handle different HTTP methods
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get specific message
            $message = $contact->getMessage($_GET['id']);
            if ($message) {
                echo json_encode(['success' => true, 'message' => $message]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Message not found']);
            }
        } else {
            // Get all messages
            $messages = $contact->getAllMessages();
            echo json_encode(['success' => true, 'messages' => $messages]);
        }
        break;

    case 'PUT':
        // Update message status
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($_GET['id']) && isset($data['status'])) {
            $result = $contact->updateMessageStatus($_GET['id'], $data['status']);
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
        }
        break;

    case 'DELETE':
        // Delete message
        if (isset($_GET['id'])) {
            $result = $contact->deleteMessage($_GET['id']);
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>
