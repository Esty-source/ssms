<?php
require_once '../../includes/config.php';
require_once '../../includes/Auth.php';
require_once '../../includes/Booking.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="bookings_export_' . date('Y-m-d') . '.csv"');

// Create output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for proper Excel encoding
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add CSV headers
fputcsv($output, [
    'Booking ID',
    'Customer Name',
    'Customer Email',
    'Container Size',
    'Container Location',
    'Start Date',
    'End Date',
    'Total Amount',
    'Status',
    'Created At',
    'Updated At'
]);

// Prepare SQL based on status filter
$sql = "
    SELECT 
        b.id,
        u.name as customer_name,
        u.email as customer_email,
        c.size as container_size,
        c.location as container_location,
        b.start_date,
        b.end_date,
        b.total_amount,
        b.status,
        b.created_at,
        b.updated_at
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN containers c ON b.container_id = c.id
    WHERE 1=1
";

if (isset($_GET['status']) && $_GET['status'] !== 'all') {
    $sql .= " AND b.status = :status";
}

$sql .= " ORDER BY b.created_at DESC";

try {
    $stmt = $conn->prepare($sql);
    
    if (isset($_GET['status']) && $_GET['status'] !== 'all') {
        $stmt->bindParam(':status', $_GET['status']);
    }
    
    $stmt->execute();

    // Output each booking as a CSV row
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $row['id'],
            $row['customer_name'],
            $row['customer_email'],
            $row['container_size'],
            $row['container_location'],
            date('d/m/Y', strtotime($row['start_date'])),
            date('d/m/Y', strtotime($row['end_date'])),
            '£' . number_format($row['total_amount'], 2),
            ucfirst($row['status']),
            date('d/m/Y H:i:s', strtotime($row['created_at'])),
            date('d/m/Y H:i:s', strtotime($row['updated_at']))
        ]);
    }
} catch (PDOException $e) {
    die("Error exporting bookings: " . $e->getMessage());
}

fclose($output);
?>
