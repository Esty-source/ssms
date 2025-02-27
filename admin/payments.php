<?php
require_once '../includes/config.php';
require_once '../includes/Auth.php';
require_once '../includes/Payment.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$payment = new Payment($conn);

// Get all payments with booking and customer details
$stmt = $conn->prepare("
    SELECT p.*, b.start_date, b.end_date, 
           c.size, c.location,
           u.name as customer_name, u.email as customer_email
    FROM payments p
    JOIN bookings b ON p.booking_id = b.id
    JOIN containers c ON b.container_id = c.id
    JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
");
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments - <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Payments</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportPayments()">
                                <i class="bi bi-download"></i> Export
                            </button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?status=all">All Payments</a></li>
                                <li><a class="dropdown-item" href="?status=completed">Completed</a></li>
                                <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                                <li><a class="dropdown-item" href="?status=refunded">Refunded</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Total Revenue</h6>
                                <h3 class="card-text">£<?php
                                    $total = array_reduce($payments, function($sum, $payment) {
                                        return $sum + ($payment['status'] === 'completed' ? $payment['amount'] : 0);
                                    }, 0);
                                    echo number_format($total, 2);
                                ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Pending Payments</h6>
                                <h3 class="card-text"><?php
                                    echo count(array_filter($payments, function($payment) {
                                        return $payment['status'] === 'pending';
                                    }));
                                ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Completed Payments</h6>
                                <h3 class="card-text"><?php
                                    echo count(array_filter($payments, function($payment) {
                                        return $payment['status'] === 'completed';
                                    }));
                                ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Refunded Amount</h6>
                                <h3 class="card-text">£<?php
                                    $refunded = array_reduce($payments, function($sum, $payment) {
                                        return $sum + ($payment['status'] === 'refunded' ? $payment['amount'] : 0);
                                    }, 0);
                                    echo number_format($refunded, 2);
                                ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Booking</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td>#<?php echo $payment['id']; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($payment['customer_name']); ?><br>
                                            <small class="text-muted"><?php echo $payment['customer_email']; ?></small>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($payment['size']); ?> ft<br>
                                            <small class="text-muted">
                                                <?php 
                                                    echo date('d/m/Y', strtotime($payment['start_date'])) . ' - ' . 
                                                         date('d/m/Y', strtotime($payment['end_date']));
                                                ?>
                                            </small>
                                        </td>
                                        <td>£<?php echo number_format($payment['amount'], 2); ?></td>
                                        <td><?php echo ucfirst($payment['payment_method']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $payment['status'] === 'completed' ? 'success' : 
                                                    ($payment['status'] === 'pending' ? 'warning' : 
                                                    ($payment['status'] === 'refunded' ? 'danger' : 'secondary'));
                                            ?>">
                                                <?php echo ucfirst($payment['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($payment['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary"
                                                        onclick="viewPayment(<?php echo $payment['id']; ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <?php if ($payment['status'] === 'completed'): ?>
                                                <button type="button" class="btn btn-outline-danger"
                                                        onclick="refundPayment(<?php echo $payment['id']; ?>)">
                                                    <i class="bi bi-arrow-return-left"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="paymentDetails">
                        <!-- Payment details will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // View payment details
        function viewPayment(id) {
            fetch(`api/payment.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const payment = data.payment;
                        document.getElementById('paymentDetails').innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Payment Information</h6>
                                    <p>
                                        <strong>Amount:</strong> £${parseFloat(payment.amount).toFixed(2)}<br>
                                        <strong>Method:</strong> ${payment.payment_method}<br>
                                        <strong>Status:</strong> ${payment.status.charAt(0).toUpperCase() + payment.status.slice(1)}<br>
                                        <strong>Transaction ID:</strong> ${payment.transaction_id || 'N/A'}<br>
                                        <strong>Date:</strong> ${new Date(payment.created_at).toLocaleString()}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Customer Information</h6>
                                    <p>
                                        <strong>Name:</strong> ${payment.customer_name}<br>
                                        <strong>Email:</strong> ${payment.customer_email}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Booking Details</h6>
                                    <p>
                                        <strong>Container:</strong> ${payment.size} ft<br>
                                        <strong>Location:</strong> ${payment.location}<br>
                                        <strong>Period:</strong> ${new Date(payment.start_date).toLocaleDateString()} - ${new Date(payment.end_date).toLocaleDateString()}
                                    </p>
                                </div>
                            </div>
                        `;
                        new bootstrap.Modal(document.getElementById('paymentDetailsModal')).show();
                    } else {
                        alert('Error loading payment details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading payment details');
                });
        }

        // Refund payment
        function refundPayment(id) {
            if (confirm('Are you sure you want to refund this payment? This action cannot be undone.')) {
                fetch(`api/payment.php?id=${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'refund'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing the refund');
                });
            }
        }

        // Export payments
        function exportPayments() {
            const status = new URLSearchParams(window.location.search).get('status') || 'all';
            window.location.href = `api/export-payments.php?status=${status}`;
        }
    </script>
</body>
</html>
