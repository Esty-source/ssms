<?php
require_once '../includes/config.php';
require_once '../includes/Auth.php';
require_once '../includes/Booking.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$booking = new Booking($conn);
$bookings = $booking->getActiveBookings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - <?php echo SITE_TITLE; ?></title>
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
                    <h1 class="h2">Manage Bookings</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportBookings()">
                                <i class="bi bi-download"></i> Export
                            </button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?status=all">All Bookings</a></li>
                                <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                                <li><a class="dropdown-item" href="?status=confirmed">Confirmed</a></li>
                                <li><a class="dropdown-item" href="?status=completed">Completed</a></li>
                                <li><a class="dropdown-item" href="?status=cancelled">Cancelled</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bookings Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Container</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td>#<?php echo $booking['id']; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($booking['user_name']); ?><br>
                                            <small class="text-muted"><?php echo $booking['email']; ?></small>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($booking['size']); ?> ft<br>
                                            <small class="text-muted"><?php echo $booking['location']; ?></small>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($booking['start_date'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($booking['end_date'])); ?></td>
                                        <td>£<?php echo number_format($booking['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $booking['status'] === 'confirmed' ? 'success' : 
                                                    ($booking['status'] === 'pending' ? 'warning' : 
                                                    ($booking['status'] === 'cancelled' ? 'danger' : 'secondary'));
                                            ?>">
                                                <?php echo ucfirst($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary"
                                                        onclick="viewBooking(<?php echo $booking['id']; ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <?php if ($booking['status'] === 'pending'): ?>
                                                <button type="button" class="btn btn-outline-success"
                                                        onclick="updateBookingStatus(<?php echo $booking['id']; ?>, 'confirmed')">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                                <?php endif; ?>
                                                <?php if (in_array($booking['status'], ['pending', 'confirmed'])): ?>
                                                <button type="button" class="btn btn-outline-danger"
                                                        onclick="updateBookingStatus(<?php echo $booking['id']; ?>, 'cancelled')">
                                                    <i class="bi bi-x"></i>
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

    <!-- Booking Details Modal -->
    <div class="modal fade" id="bookingDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="bookingDetails">
                        <!-- Booking details will be loaded here -->
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
        // View booking details
        function viewBooking(id) {
            fetch(`api/booking.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const booking = data.booking;
                        document.getElementById('bookingDetails').innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Customer Information</h6>
                                    <p>
                                        <strong>Name:</strong> ${booking.user_name}<br>
                                        <strong>Email:</strong> ${booking.email}<br>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Container Information</h6>
                                    <p>
                                        <strong>Size:</strong> ${booking.size} ft<br>
                                        <strong>Location:</strong> ${booking.location}<br>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Booking Details</h6>
                                    <p>
                                        <strong>Start Date:</strong> ${new Date(booking.start_date).toLocaleDateString()}<br>
                                        <strong>End Date:</strong> ${new Date(booking.end_date).toLocaleDateString()}<br>
                                        <strong>Status:</strong> ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}<br>
                                        <strong>Amount:</strong> £${parseFloat(booking.total_amount).toFixed(2)}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Timeline</h6>
                                    <p>
                                        <strong>Created:</strong> ${new Date(booking.created_at).toLocaleString()}<br>
                                        <strong>Last Updated:</strong> ${new Date(booking.updated_at).toLocaleString()}
                                    </p>
                                </div>
                            </div>
                        `;
                        new bootstrap.Modal(document.getElementById('bookingDetailsModal')).show();
                    } else {
                        alert('Error loading booking details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading booking details');
                });
        }

        // Update booking status
        function updateBookingStatus(id, status) {
            if (confirm(`Are you sure you want to mark this booking as ${status}?`)) {
                fetch('api/booking.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating booking status: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the booking');
                });
            }
        }

        // Export bookings
        function exportBookings() {
            const status = new URLSearchParams(window.location.search).get('status') || 'all';
            window.location.href = `api/export-bookings.php?status=${status}`;
        }
    </script>
</body>
</html>
