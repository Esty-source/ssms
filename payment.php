<?php
require_once 'includes/config.php';
require_once 'includes/Auth.php';
require_once 'includes/Booking.php';
require_once 'includes/Payment.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

// Check if booking ID is provided
if (!isset($_GET['booking_id'])) {
    header('Location: ' . BASE_URL . 'dashboard.php');
    exit();
}

$booking = new Booking($conn);
$payment = new Payment($conn);
$bookingData = $booking->getBooking($_GET['booking_id']);

// Validate booking
if (!$bookingData || $bookingData['user_id'] != $_SESSION['user_id']) {
    header('Location: ' . BASE_URL . 'dashboard.php');
    exit();
}

$error = '';
$success = '';

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['payment_method'])) {
        $result = $payment->createPayment(
            $bookingData['id'],
            $_SESSION['user_id'],
            $bookingData['total_amount'],
            $_POST['payment_method']
        );

        if ($result['success']) {
            header('Location: ' . BASE_URL . 'booking-confirmation.php?payment_id=' . $result['payment_id']);
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
    <title>Payment - <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="mb-4">Complete Your Payment</h1>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Booking Summary</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Storage Unit:</strong> <?php echo htmlspecialchars($bookingData['size']); ?> ft</p>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($bookingData['location']); ?></p>
                                <p><strong>Start Date:</strong> <?php echo date('d/m/Y', strtotime($bookingData['start_date'])); ?></p>
                                <p><strong>End Date:</strong> <?php echo date('d/m/Y', strtotime($bookingData['end_date'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <div class="text-end">
                                    <h4 class="mb-0">Total Amount</h4>
                                    <h2 class="text-primary">£<?php echo number_format($bookingData['total_amount'], 2); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Payment Method</h5>
                        <form method="POST" action="" id="payment-form" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="card" value="card" checked>
                                    <label class="form-check-label" for="card">
                                        Credit/Debit Card
                                    </label>
                                </div>
                                <div id="card-details" class="ms-4">
                                    <div class="mb-3">
                                        <label for="card_number" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="card_number" required
                                               pattern="[0-9]{16}" placeholder="1234 5678 9012 3456">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="expiry" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control" id="expiry" required
                                                   pattern="(0[1-9]|1[0-2])\/([0-9]{2})" placeholder="MM/YY">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cvv" required
                                                   pattern="[0-9]{3,4}" placeholder="123">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="save_card" name="save_card">
                                <label class="form-check-label" for="save_card">
                                    Save card for future payments
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Pay Now</button>
                                <a href="booking.php?container_id=<?php echo $bookingData['container_id']; ?>" 
                                   class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="mb-0">
                        <i class="bi bi-shield-lock"></i>
                        Your payment is secured with SSL encryption
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Card number formatting
            document.getElementById('card_number').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 16) value = value.slice(0, 16);
                e.target.value = value.replace(/(\d{4})/g, '$1 ').trim();
            });

            // Expiry date formatting
            document.getElementById('expiry').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 4) value = value.slice(0, 4);
                if (value.length > 2) {
                    value = value.slice(0, 2) + '/' + value.slice(2);
                }
                e.target.value = value;
            });

            // CVV formatting
            document.getElementById('cvv').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 4) value = value.slice(0, 4);
                e.target.value = value;
            });
        });
    </script>
</body>
</html>
