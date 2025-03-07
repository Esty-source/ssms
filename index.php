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
    </style>
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section home-hero">
        <div class="hero-image" style="background-image: url('assets/images/hero-storage-facility.jpg'); background-size: cover; background-position: center;"></div>
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
                                        <option value="small">Small (25 sq ft)</option>
                                        <option value="medium">Medium (100 sq ft)</option>
                                        <option value="large">Large (200 sq ft)</option>
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
    <section class="py-5 storage-units">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Popular Storage Solutions</h2>
            <div class="row g-4">
                <!-- Small Unit -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 shadow-hover">
                        <img src="assets/images/small_unit2.png" class="card-img-top" alt="Small Storage Unit">
                        <div class="card-body">
                            <h4>Small Unit</h4>
                            <p class="text-dark mb-3">Perfect for personal items and small furniture</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0">$50/month</span>
                                <a href="containers.php" class="btn btn-outline-primary">Rent Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medium Unit -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 shadow-hover">
                        <img src="assets/images/medium_unit2.png" class="card-img-top" alt="Medium Storage Unit">
                        <div class="card-body">
                            <h4>Medium Unit</h4>
                            <p class="text-dark mb-3">Ideal for apartment or office contents</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0">$100/month</span>
                                <a href="containers.php" class="btn btn-outline-primary">Rent Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Large Unit -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 shadow-hover">
                        <img src="assets/images/large_unit2.png" class="card-img-top" alt="Large Storage Unit">
                        <div class="card-body">
                            <h4>Large Unit</h4>
                            <p class="text-dark mb-3">Perfect for house contents or business storage</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0">$200/month</span>
                                <a href="containers.php" class="btn btn-outline-primary">Rent Now</a>
                            </div>
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
                    <i class="bi bi-shield-lock fs-1 text-primary"></i>
                        <h4>24/7 Security</h4>
                        <p>Round-the-clock surveillance and secure access control</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card text-center">
                    <i class="bi bi-thermometer-half fs-1 text-primary"></i>
                        <h4>Climate Control</h4>
                        <p>Temperature-controlled units to protect your belongings</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card text-center">
                    <i class="bi bi-unlock fs-1 text-primary"></i>
                        <h4>Easy Access</h4>
                        <p>24/7 access with your personal security code</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card text-center">
                    <i class="bi bi-headset fs-1 text-primary"></i>
                        <h4>Customer Support</h4>
                        <p>Dedicated team ready to assist you</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 testimonials-section">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">What Our Customers Say</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 shadow-sm testimonial-card">
                        <div class="card-body text-center">
                            <img src="assets/images/testimonial1.jpg" class="testimonial-image" alt="Customer 1">
                            <div class="testimonial-quote">
                                <p class="mb-0">"Excellent service! The units are clean, secure, and easily accessible. Staff is always helpful."</p>
                            </div>
                            <h5 class="testimonial-name mb-1">John Smith</h5>
                            <p class="testimonial-role">Business Owner</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 shadow-sm testimonial-card">
                        <div class="card-body text-center">
                            <img src="assets/images/testimonial2.jpg" class="testimonial-image" alt="Customer 2">
                            <div class="testimonial-quote">
                                <p class="mb-0">"The climate-controlled units are perfect for storing my valuable items. Great security too!"</p>
                            </div>
                            <h5 class="testimonial-name mb-1">Sarah Johnson</h5>
                            <p class="testimonial-role">Home Owner</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 shadow-sm testimonial-card">
                        <div class="card-body text-center">
                            <img src="assets/images/testimonial3.jpg" class="testimonial-image" alt="Customer 3">
                            <div class="testimonial-quote">
                                <p class="mb-0">"Affordable prices and flexible rental terms. Exactly what I needed for my storage needs."</p>
                            </div>
                            <h5 class="testimonial-name mb-1">Mike Wilson</h5>
                            <p class="testimonial-role">Student</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section with Background Image -->
    <section class="get-started-section position-relative">
        <div class="get-started-bg-image" style="background-image: url('assets/images/section.jpg');"></div>
        <div class="container position-relative">
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
