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

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="payments_export_' . date('Y-m-d') . '.csv"');

// Create output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for proper Excel encoding
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add CSV headers
fputcsv($output, [
    'Payment ID',
    'Customer Name',
    'Customer Email',
    'Container Size',
    'Container Location',
    'Start Date',
    'End Date',
    'Amount',
    'Payment Method',
    'Transaction ID',
    'Status',
    'Created At'
]);

// Prepare SQL based on status filter
$sql = "
    SELECT 
        p.id,
        u.name as customer_name,
        u.email as customer_email,
        c.size as container_size,
        c.location as container_location,
        b.start_date,
        b.end_date,
        p.amount,
        p.payment_method,
        p.transaction_id,
        p.status,
        p.created_at
    FROM payments p
    JOIN bookings b ON p.booking_id = b.id
    JOIN containers c ON b.container_id = c.id
    JOIN users u ON p.user_id = u.id
    WHERE 1=1
";

if (isset($_GET['status']) && $_GET['status'] !== 'all') {
    $sql .= " AND p.status = :status";
}

$sql .= " ORDER BY p.created_at DESC";

try {
    $stmt = $conn->prepare($sql);
    
    if (isset($_GET['status']) && $_GET['status'] !== 'all') {
        $stmt->bindParam(':status', $_GET['status']);
    }
    
    $stmt->execute();

    // Output each payment as a CSV row
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $row['id'],
            $row['customer_name'],
            $row['customer_email'],
            $row['container_size'],
            $row['container_location'],
            date('d/m/Y', strtotime($row['start_date'])),
            date('d/m/Y', strtotime($row['end_date'])),
            '£' . number_format($row['amount'], 2),
            ucfirst($row['payment_method']),
            $row['transaction_id'] ?? 'N/A',
            ucfirst($row['status']),
            date('d/m/Y H:i:s', strtotime($row['created_at']))
        ]);
    }
} catch (PDOException $e) {
    die("Error exporting payments: " . $e->getMessage());
}

fclose($output);
?>
