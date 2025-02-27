<?php
require_once 'includes/config.php';
require_once 'includes/Auth.php';
require_once 'includes/Container.php';
require_once 'includes/Booking.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$container = new Container($conn);
$booking = new Booking($conn);
$error = '';
$success = '';

// Get container details
if (!isset($_GET['container_id'])) {
    header('Location: ' . BASE_URL . 'containers.php');
    exit();
}

$containerData = $container->getContainerById($_GET['container_id']);
if (!$containerData || $containerData['status'] !== 'available') {
    header('Location: ' . BASE_URL . 'containers.php');
    exit();
}

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';
    
    // Validate dates
    $dateValidation = $booking->validateBookingDates($startDate, $endDate);
    if (!$dateValidation['valid']) {
        $error = $dateValidation['message'];
    } else {
        // Calculate duration and total amount
        $duration = $booking->calculateBookingDuration($startDate, $endDate);
        $totalAmount = $container->calculatePrice($containerData['size'], $duration);
        
        // Create booking
        $result = $booking->createBooking(
            $_SESSION['user_id'],
            $containerData['id'],
            $startDate,
            $endDate,
            $totalAmount
        );
        
        if ($result['success']) {
            header('Location: ' . BASE_URL . 'payment.php?booking_id=' . $result['booking_id']);
            exit();
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Storage Unit - <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <h1 class="mb-4">Book Storage Unit</h1>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Selected Unit Details</h5>
                        <p class="card-text">
                            <strong>Size:</strong> <?php echo htmlspecialchars($containerData['size']); ?> ft<br>
                            <strong>Location:</strong> <?php echo htmlspecialchars($containerData['location']); ?><br>
                            <strong>Base Price:</strong> £<?php echo number_format($containerData['price'], 2); ?>/month
                        </p>
                    </div>
                </div>

                <form method="POST" action="" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control datepicker" id="start_date" name="start_date" required
                                   min="<?php echo date('Y-m-d'); ?>">
                            <div class="invalid-feedback">Please select a start date.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control datepicker" id="end_date" name="end_date" required
                                   min="<?php echo date('Y-m-d', strtotime('+1 month')); ?>">
                            <div class="invalid-feedback">Please select an end date.</div>
                        </div>
                    </div>

                    <div class="mt-4" id="price-calculation">
                        <h5>Price Calculation</h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Base Monthly Rate:</span>
                                    <span>£<?php echo number_format($containerData['price'], 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Duration:</span>
                                    <span id="duration">-- months</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Discount:</span>
                                    <span id="discount">--%</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total Amount:</span>
                                    <span id="total-amount">£0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                        <a href="containers.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Booking Information</h5>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Minimum rental period: 1 month</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>24/7 access available</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Flexible payment options</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Cancel anytime (30-day notice)</li>
                        </ul>
                        <hr>
                        <h6>Volume Discounts:</h6>
                        <ul class="list-unstyled">
                            <li>3+ months: 5% off</li>
                            <li>6+ months: 10% off</li>
                            <li>12+ months: 15% off</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date pickers
            flatpickr(".datepicker", {
                minDate: "today",
                dateFormat: "Y-m-d"
            });

            // Calculate price when dates change
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const basePrice = <?php echo $containerData['price']; ?>;

            function calculatePrice() {
                if (startDateInput.value && endDateInput.value) {
                    const start = new Date(startDateInput.value);
                    const end = new Date(endDateInput.value);
                    const months = Math.ceil((end - start) / (30 * 24 * 60 * 60 * 1000));
                    
                    let discount = 0;
                    if (months >= 12) discount = 0.15;
                    else if (months >= 6) discount = 0.10;
                    else if (months >= 3) discount = 0.05;

                    const total = basePrice * months * (1 - discount);

                    document.getElementById('duration').textContent = months + ' months';
                    document.getElementById('discount').textContent = (discount * 100) + '%';
                    document.getElementById('total-amount').textContent = '£' + total.toFixed(2);
                }
            }

            startDateInput.addEventListener('change', calculatePrice);
            endDateInput.addEventListener('change', calculatePrice);
        });
    </script>
</body>
</html>
