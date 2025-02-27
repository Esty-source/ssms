<?php
require_once '../../includes/config.php';
require_once '../../includes/Auth.php';
require_once '../../includes/Container.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$container = new Container($conn);

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get container by ID or list all containers
        if (isset($_GET['id'])) {
            $result = $container->getContainerById($_GET['id']);
        } else {
            $result = $container->getAllContainers($_GET);
        }
        echo json_encode($result);
        break;

    case 'POST':
        // Create new container
        $data = [
            'size' => $_POST['size'],
            'price' => $_POST['price'],
            'location' => $_POST['location'],
            'status' => $_POST['status'],
            'description' => $_POST['description']
        ];

        $result = $container->createContainer($data);
        echo json_encode($result);
        break;

    case 'PUT':
        // Update existing container
        parse_str(file_get_contents("php://input"), $_PUT);
        
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Container ID is required']);
            exit();
        }

        $data = [
            'size' => $_PUT['size'],
            'price' => $_PUT['price'],
            'location' => $_PUT['location'],
            'status' => $_PUT['status'],
            'description' => $_PUT['description']
        ];

        $result = $container->updateContainer($_GET['id'], $data);
        echo json_encode($result);
        break;

    case 'DELETE':
        // Delete container
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Container ID is required']);
            exit();
        }

        $result = $container->deleteContainer($_GET['id']);
        echo json_encode($result);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>
