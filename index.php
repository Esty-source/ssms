<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?> - Secure Storage Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Add AOS library for scroll animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .carousel-item {
            padding: 3rem 0;
        }
        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            height: 40px;
            width: 40px;
            top: 50%;
            transform: translateY(-50%);
        }
        .carousel-control-prev {
            left: -20px;
        }
        .carousel-control-next {
            right: -20px;
        }
        .carousel-indicators {
            bottom: -50px;
        }
        .carousel-indicators button {
            background-color: var(--bs-primary) !important;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 5px;
        }
        #contentSlider .row {
            min-height: 400px;
        }
        #contentSlider img {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        #contentSlider img:hover {
            transform: scale(1.02);
        }
        #contentSlider .list-unstyled li {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        #contentSlider .bi {
            color: var(--bs-primary);
        }
        .bg-dark {
            background-color: #000000 !important;
        }
        .carousel-item img {
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        .carousel-item img:hover {
            transform: scale(1.02);
        }
        .carousel-item .list-unstyled li {
            opacity: 0.9;
            transition: opacity 0.3s ease;
        }
        .carousel-item .list-unstyled li:hover {
            opacity: 1;
        }
        .carousel-item .bi {
            font-size: 1.2rem;
        }
        /* Review Section Styles */
        .review-card {
            transition: transform 0.3s ease;
        }
        .review-card:hover {
            transform: translateY(-5px);
        }
        .review-avatar {
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .review-stats .bi {
            transition: transform 0.3s ease;
        }
        .review-stats .bg-white:hover .bi {
            transform: scale(1.1);
        }
        .review-stats .bg-white {
            transition: transform 0.3s ease;
        }
        .review-stats .bg-white:hover {
            transform: translateY(-5px);
        }
        .card-img-top {
            height: 200px; /* Set a fixed height */
            object-fit: cover; /* Maintain aspect ratio */
            width: 100%; /* Ensure full width */
        }
        .feature-card i {
            color: red; /* Change icon color to red */
            font-size: 90px; /* Set icon size */
            display: block; /* Center the icon */
            margin: 0 auto; /* Centering the icon */
            text-align: center; /* Ensure text is centered */
        }
        .feature-card {
            margin-bottom: 20px; /* Add spacing between feature cards */
        }
    </style>
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section home-hero">
        <video class="video-background" autoplay muted loop>
            <source src="assets/videos/storage-facility.mp4" type="video/mp4">
        </video>
        <div class="overlay"></div>
        <div class="content">
            <div class="container">
                <h1 class="display-3">Secure Storage Solutions</h1>
                <p class="lead mb-4">Safe, accessible, and flexible storage units for all your needs</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="containers.php" class="btn btn-primary btn-lg">View Units</a>
                    <a href="contact.php" class="btn btn-outline-light btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Search Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow-sm" data-aos="fade-up">
                        <div class="card-body p-4">
                            <h3 class="text-center mb-4">Find Your Perfect Storage Unit</h3>
                            <form class="row g-3">
                                <div class="col-md-4">
                                    <select class="form-select form-select-lg">
                                        <option value="">Select Size</option>
                                        <option value="small">10ft Container</option>
                                        <option value="medium">20ft Container</option>
                                        <option value="large">25ft Container</option>
                                        <option value="small">30ft Container</option>
                                        <option value="medium">40ft Container</option>
                                        <option value="large">45ft Container</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select form-select-lg">
                                        <option value="">Duration</option>
                                        <option value="1">1 Month</option>
                                        <option value="3">3 Months</option>
                                        <option value="6">6 Months</option>
                                        <option value="12">12 Months</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">Search Units</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Units Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Popular Storage Solutions</h2>
            <div class="row g-4">
                <!-- 10ft Container -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 shadow-hover">
                        <img src="assets/images/10ft.png" class="card-img-top" alt="10ft container">
                        <div class="card-body">
                            <h4>10ft Container</h4>
                            <p class="text-dark mb-3">Perfect for personal items and small furniture</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check2-circle me-2"></i>25 sq ft space</li>
                                <li><i class="bi bi-check2-circle me-2"></i>Climate controlled</li>
                                <li><i class="bi bi-check2-circle me-2"></i>24/7 access</li>
                            </ul>
                            <p class="h4 mb-0">From £90/month</p>
                        </div>
                    </div>
                </div>
                <!-- 20ft Container -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 shadow-hover">
                        <img src="assets/images/20ft.png" class="card-img-top" alt="20ft container">
                        <div class="card-body">
                            <h4>20ft Container</h4>
                            <p class="text-dark mb-3">Ideal for apartment or office contents</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check2-circle me-2"></i>100 sq ft space</li>
                                <li><i class="bi bi-check2-circle me-2"></i>Climate controlled</li>
                                <li><i class="bi bi-check2-circle me-2"></i>24/7 access</li>
                            </ul>
                            <p class="h4 mb-0">From £150/month</p>
                        </div>
                    </div>
                </div>
                <!-- 25ft container -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 shadow-hover">
                        <img src="assets/images/25ft.png" class="card-img-top" alt="25ft container">
                        <div class="card-body">
                            <h4>25ft Container</h4>
                            <p class="text-dark mb-3">Perfect for house contents or business storage</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check2-circle me-2"></i>200 sq ft space</li>
                                <li><i class="bi bi-check2-circle me-2"></i>Climate controlled</li>
                                <li><i class="bi bi-check2-circle me-2"></i>24/7 access</li>
                            </ul>
                            <p class="h4 mb-0">From £190/month</p>
                        </div>
                    </div>
                </div>
                <!-- 30ft Container -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 shadow-hover">
                        <img src="assets/images/30ft.png" class="card-img-top" alt="30ft container">
                        <div class="card-body">
                            <h4>30ft Container</h4>
                            <p class="text-dark mb-3">Perfect for personal items and small furniture</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check2-circle me-2"></i>25 sq ft space</li>
                                <li><i class="bi bi-check2-circle me-2"></i>Climate controlled</li>
                                <li><i class="bi bi-check2-circle me-2"></i>24/7 access</li>
                            </ul>
                            <p class="h4 mb-0">From £230/month</p>
                        </div>
                    </div>
                </div>
                 <!-- 40ft Container -->
                 <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 shadow-hover">
                        <img src="assets/images/40ft.png" class="card-img-top" alt="40ft container">
                        <div class="card-body">
                            <h4>40ft Container</h4>
                            <p class="text-dark mb-3">Perfect for personal items and small furniture</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check2-circle me-2"></i>25 sq ft space</li>
                                <li><i class="bi bi-check2-circle me-2"></i>Climate controlled</li>
                                <li><i class="bi bi-check2-circle me-2"></i>24/7 access</li>
                            </ul>
                            <p class="h4 mb-0">From £290/month</p>
                        </div>
                    </div>
                </div>
                <!-- 45ft Container -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 shadow-hover">
                        <img src="assets/images/45ft.png" class="card-img-top" alt="45ft container">
                        <div class="card-body">
                            <h4>45ft Container</h4>
                            <p class="text-dark mb-3">Ideal for apartment or office contents</p>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check2-circle me-2"></i>100 sq ft space</li>
                                <li><i class="bi bi-check2-circle me-2"></i>Climate controlled</li>
                                <li><i class="bi bi-check2-circle me-2"></i>24/7 access</li>
                            </ul>
                            <p class="h4 mb-0">From £330/month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section with Images -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Why Choose Us?</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card text-center">
                    <i class="bi bi-shield-lock mb-3" style="font-size: 90px;"></i>
                        <h4>24/7 Security</h4>
                        <p>Round-the-clock surveillance and secure access control</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card text-center">
                    <i class="bi bi-door-open mb-3" style="font-size: 90px;"></i>
                        <h4>Easy Access</h4>
                        <p>24/7 access </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card text-center">
                    <i class="bi bi-headset mb-3" style="font-size: 90px;"></i>
                        <h4>Customer Support</h4>
                        <p>Dedicated team ready to assist you</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">What Our Customers Say</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="assets/images/testimonial1.jpg" class="rounded-circle mb-3" alt="Customer 1" width="80" height="80">
                            <p class="mb-4">"Excellent service! The units are clean, secure, and easily accessible. Staff is always helpful."</p>
                            <h5 class="mb-1">John Smith</h5>
                            <p class="text-muted">Business Owner</p>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="assets/images/testimonial2.jpg" class="rounded-circle mb-3" alt="Customer 2" width="80" height="80">
                            <p class="mb-4">"Reliable and secure storage solutions tailored to your needs—store with confidence and ease!"</p>
                            <h5 class="mb-1">Sarah Johnson</h5>
                            <p class="text-muted">Home Owner</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="assets/images/testimonial3.jpg" class="rounded-circle mb-3" alt="Customer 3" width="80" height="80">
                            <p class="mb-4">"Affordable prices and flexible rental terms. Exactly what I needed for my storage needs."</p>
                            <h5 class="mb-1">Mike Wilson</h5>
                            <p class="text-muted">Student</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section with Background Image -->
    <section class="get-started-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8" data-aos="fade-up">
                    <h2 class="mb-4">Ready to Get Started?</h2>
                    <p class="lead mb-4">Choose your perfect storage solution today and enjoy peace of mind knowing your belongings are safe with us.</p>
                    <a href="contact.php" class="btn btn-light btn-lg">Contact Us Now</a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>
