<?php
require_once '../../includes/config.php';
require_once '../../includes/Auth.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="contact_messages_' . date('Y-m-d') . '.csv"');

// Create output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for proper Excel encoding
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add CSV headers
fputcsv($output, [
    'Message ID',
    'Name',
    'Email',
    'Subject',
    'Message',
    'Status',
    'Created At',
    'Updated At'
]);

// Prepare SQL based on status filter
$sql = "SELECT * FROM contact_messages WHERE 1=1";

if (isset($_GET['status']) && $_GET['status'] !== 'all') {
    $sql .= " AND status = :status";
}

$sql .= " ORDER BY created_at DESC";

try {
    $stmt = $conn->prepare($sql);
    
    if (isset($_GET['status']) && $_GET['status'] !== 'all') {
        $stmt->bindParam(':status', $_GET['status']);
    }
    
    $stmt->execute();

    // Output each message as a CSV row
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['email'],
            $row['subject'],
            $row['message'],
            ucfirst($row['status']),
            date('d/m/Y H:i:s', strtotime($row['created_at'])),
            $row['updated_at'] ? date('d/m/Y H:i:s', strtotime($row['updated_at'])) : 'N/A'
        ]);
    }
} catch (PDOException $e) {
    die("Error exporting messages: " . $e->getMessage());
}

fclose($output);
?>
