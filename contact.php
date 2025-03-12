<?php
require_once 'includes/config.php';
require_once 'includes/Contact.php';

$message = '';
$messageType = '';

// Get unit type from URL if it exists
$unit = isset($_GET['unit']) ? $_GET['unit'] : '';
$defaultMessage = '';

// Define storage unit sizes
$unitTypes = [
    '10ft' => '10ft Container',
    '20ft' => '20ft Container',
    '26ft' => '26ft Container',
    '30ft' => '30ft Container',
    '40ft' => '40ft Container',
    '45ft' => '45ft Container'
];

// Set default message based on unit type
if ($unit && isset($unitTypes[$unit])) {
    $defaultMessage = "I'm interested in booking the " . $unitTypes[$unit] . ". Please provide me with availability and booking information.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact = new Contact($conn);
    $selectedUnit = isset($_POST['unit_size']) ? $_POST['unit_size'] : '';
    $subject = 'Storage Unit Inquiry: ' . (isset($unitTypes[$selectedUnit]) ? $unitTypes[$selectedUnit] : 'General Inquiry');
    
    $result = $contact->createMessage([
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'subject' => $subject,
        'message' => $_POST['message']
    ]);

    if ($result['success']) {
        $message = 'Your message has been sent successfully. We will get back to you soon.';
        $messageType = 'success';
    } else {
        $message = 'Error sending message: ' . $result['message'];
        $messageType = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .hero-section {
            background-color: #ff0000;
            color: #ffffff;
        }
        .hero-section h1 {
            color: #ffffff;
        }
        .hero-section p {
            color: #ffffff;
        }
        .contact-info-item i {
            color: #ff0000;
        }
        .contact-info-item h5 {
            color: #ff0000;
        }
        .btn-primary {
            background-color: #ff0000;
            border-color: #ff0000;
        }
        .btn-primary:hover {
            background-color: #ff0000;
            border-color: #ff0000;
        }
    </style>
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="page-hero hero-section position-relative" style="background-image: url('assets/images/about-hero.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="overlay"></div>
        <div class="content">
            <div class="container">
                <h1 class="display-4 text-white">Contact <span class="text-danger">Safe Lock</span> Storage</h1>
                <p class="lead text-white">We're here to help. Reach out to us with any questions or inquiries.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-4">Send us a Message</h3>
                            <?php if ($message): ?>
                                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show mb-4">
                                    <?php echo $message; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="unit_size" class="form-label">Storage Unit Size</label>
                                        <select class="form-select" id="unit_size" name="unit_size" required>
                                            <option value="">Select a size</option>
                                            <option value="10ft" <?php echo $unit === '10ft' ? 'selected' : ''; ?>>10ft Container - £90/month</option>
                                            <option value="20ft" <?php echo $unit === '20ft' ? 'selected' : ''; ?>>20ft Container - £150/month</option>
                                            <option value="26ft" <?php echo $unit === '26ft' ? 'selected' : ''; ?>>26ft Container - £190/month</option>
                                            <option value="30ft" <?php echo $unit === '30ft' ? 'selected' : ''; ?>>30ft Container - £230/month</option>
                                            <option value="40ft" <?php echo $unit === '40ft' ? 'selected' : ''; ?>>40ft Container - £290/month</option>
                                            <option value="45ft" <?php echo $unit === '45ft' ? 'selected' : ''; ?>>45ft Container - £330/month</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="start_date" name="start_date" placeholder="Select start date" required>
                                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date" class="form-label">End Date (Optional)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="end_date" name="end_date" placeholder="Select end date">
                                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" required><?php echo htmlspecialchars($defaultMessage); ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-4">Contact Information</h3>
                            <div class="mb-4">
                                <h5 class="text-dark">Address</h5>
                                <p class="mb-0 text-dark">11-12 Anne Road, Smethwick</p>
                                <p class="text-dark">Birmingham, B66 2NZ</p>
                            </div>
                            <div class="mb-4">
                                <h5 class="text-dark">Phone</h5>
                                <p class="mb-0 text-dark">Tel: +44 7481 597581</p>
                                <p class="mb-0 text-dark">Tel: +44 7427497479</p>
                            </div>
                            <div class="mb-4">
                                <h5 class="text-dark">Email</h5>
                                <p class="mb-0"><a href="mailto:safelockstorageltd@gmail.com" class="text-danger">safelockstorageltd@gmail.com</a></p>
                            </div>
                            <div>
                                <h5 class="text-dark">Business Hours</h5>
                                <p class="mb-0 text-dark">Monday - Friday: 8:00 AM - 6:00 PM</p>
                                <p class="mb-0 text-dark">Saturday: 9:00 AM - 5:00 PM</p>
                                <p class="text-dark">Sunday: 10:00 AM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h3 class="mb-4">Follow Us</h3>
                            <div class="d-flex justify-content-around">
                                <a href="#" class="text-danger fs-3"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="text-danger fs-3"><i class="bi bi-twitter"></i></a>
                                <a href="#" class="text-danger fs-3"><i class="bi bi-instagram"></i></a>
                                <a href="#" class="text-danger fs-3"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Find Us</h2>
                <p class="lead text-muted">Visit our facility</p>
            </div>
            <div class="ratio ratio-21x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2430.5751392646347!2d-1.8999800842089468!3d52.48090647980792!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870bc8e8f40f8e7%3A0x9f8e7c8e8f8e7c8e!2sBirmingham%2C%20UK!5e0!3m2!1sen!2suk!4v1625000000000!5m2!1sen!2suk" 
                    style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date pickers
            const startDatePicker = flatpickr("#start_date", {
                minDate: "today",
                dateFormat: "Y-m-d",
                allowInput: true,
                onChange: function(selectedDates, dateStr) {
                    // Update end date minimum when start date changes
                    endDatePicker.set("minDate", dateStr);
                    updateMessage();
                }
            });

            const endDatePicker = flatpickr("#end_date", {
                dateFormat: "Y-m-d",
                allowInput: true,
                onChange: function() {
                    updateMessage();
                }
            });

            // Function to update message
            function updateMessage() {
                const unitSelect = document.getElementById('unit_size');
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                const messageArea = document.getElementById('message');
                
                if (unitSelect.value && startDate) {
                    const unitText = unitSelect.options[unitSelect.selectedIndex].text;
                    let message = `I'm interested in booking the ${unitText} starting from ${startDate}`;
                    if (endDate) {
                        message += ` until ${endDate}`;
                    }
                    message += ". Please provide me with availability and booking information.";
                    messageArea.value = message;
                }
            }

            // Add event listener for unit size change
            document.getElementById('unit_size').addEventListener('change', updateMessage);
        });
    </script>
</body>
</html>
