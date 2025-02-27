<?php
require_once '../../includes/config.php';
require_once '../../includes/Auth.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['booking_id']) || !isset($data['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit();
}

$booking_id = filter_var($data['booking_id'], FILTER_VALIDATE_INT);
$status = filter_var($data['status'], FILTER_SANITIZE_STRING);

// Validate status
$allowed_statuses = ['pending', 'confirmed', 'cancelled'];
if (!in_array($status, $allowed_statuses)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit();
}

try {
    // Begin transaction
    $conn->beginTransaction();

    // Update booking status
    $stmt = $conn->prepare('UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?');
    $stmt->execute([$status, $booking_id]);

    // If status is cancelled, make the container available again
    if ($status === 'cancelled') {
        $stmt = $conn->prepare('
            UPDATE containers c
            JOIN bookings b ON b.container_id = c.id
            SET c.status = "available"
            WHERE b.id = ?
        ');
        $stmt->execute([$booking_id]);
    }

    // Commit transaction
    $conn->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
