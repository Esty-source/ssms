<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moving Services - <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4">Moving Services</h1>
            <p class="lead mb-4">Professional moving assistance for a stress-free experience</p>
            <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-primary btn-lg">Get a Quote</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section-padding">
        <div class="container">
            <div class="row g-4">
                <!-- Local Moving -->
                <div class="col-md-6 fade-in">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="feature-icon mb-4">
                                <i class="bi bi-truck"></i>
                            </div>
                            <h3 class="card-title text-center">Local Moving</h3>
                            <ul class="list-unstyled mt-4">
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-danger me-2"></i>
                                    Professional moving team
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-danger me-2"></i>
                                    Fully equipped moving vehicles
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-danger me-2"></i>
                                    Careful handling of your belongings
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-danger me-2"></i>
                                    Flexible scheduling options
                                </li>
                            </ul>
                            <div class="text-center mt-4">
                                <a href="booking.php?service=local" class="btn btn-primary">Book Local Moving</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Packing Services -->
                <div class="col-md-6 fade-in">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="feature-icon mb-4">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <h3 class="card-title text-center">Packing Services</h3>
                            <ul class="list-unstyled mt-4">
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-danger me-2"></i>
                                    Professional packing materials
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-danger me-2"></i>
                                    Expert packing techniques
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-danger me-2"></i>
                                    Special care for fragile items
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-check-circle-fill text-danger me-2"></i>
                                    Unpacking services available
                                </li>
                            </ul>
                            <div class="text-center mt-4">
                                <a href="booking.php?service=packing" class="btn btn-primary">Book Packing Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Our Moving Process</h2>
                <p class="lead text-muted">Simple and efficient moving in 4 easy steps</p>
            </div>
            <div class="row g-4">
                <div class="col-md-3 fade-in">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="bi bi-chat-text"></i>
                        </div>
                        <h5 class="mt-4">1. Consultation</h5>
                        <p>Discuss your moving needs with our experts</p>
                    </div>
                </div>
                <div class="col-md-3 fade-in">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h5 class="mt-4">2. Planning</h5>
                        <p>Schedule your move and plan the logistics</p>
                    </div>
                </div>
                <div class="col-md-3 fade-in">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="bi bi-box"></i>
                        </div>
                        <h5 class="mt-4">3. Packing</h5>
                        <p>Professional packing of your belongings</p>
                    </div>
                </div>
                <div class="col-md-3 fade-in">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="bi bi-house-check"></i>
                        </div>
                        <h5 class="mt-4">4. Moving Day</h5>
                        <p>Safe transportation to your new location</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2>What Our Customers Say</h2>
                <p class="lead text-muted">Read about experiences from our satisfied customers</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 fade-in">
                    <div class="testimonial-card">
                        <div class="text-center">
                            <i class="bi bi-quote display-4 text-danger"></i>
                            <p class="mt-3">"Excellent service! The team was professional and handled our belongings with care. Would definitely recommend!"</p>
                            <p class="text-danger mb-0">- Sarah Johnson</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 fade-in">
                    <div class="testimonial-card">
                        <div class="text-center">
                            <i class="bi bi-quote display-4 text-danger"></i>
                            <p class="mt-3">"Made our move so much easier. The packing service was particularly helpful. Great team!"</p>
                            <p class="text-danger mb-0">- Mike Thompson</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 fade-in">
                    <div class="testimonial-card">
                        <div class="text-center">
                            <i class="bi bi-quote display-4 text-danger"></i>
                            <p class="mt-3">"Very efficient and professional service. Everything arrived safely and on time. Highly recommended!"</p>
                            <p class="text-danger mb-0">- Emma Davis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding bg-dark text-white text-center">
        <div class="container">
            <h2 class="mb-4">Ready to Move?</h2>
            <p class="lead mb-4">Let us help you with your next move</p>
            <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-primary btn-lg">Get Started</a>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-in');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            });

            fadeElements.forEach(element => {
                observer.observe(element);
            });
        });
    </script>
</body>
</html>
