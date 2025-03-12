<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storage Units - <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .hero-section {
            background-color: #ff0000;
            color: #000000;
        }
        .price-card {
            background-color: #ff0000;
            color: #000000;
        }
        .price-card .card-header {
            background-color: #ffffff;
            color: #000000;
        }
        .price-card .card-body {
            background-color: #ff0000;
            color: #000000;
        }
        .price-card .card-body ul li {
            color: #000000;
        }
        .price-card .card-body ul li i {
            color: #000000;
        }
        .feature-icon {
            background-color: #ff0000;
            color: #000000;
        }
        .feature-icon i {
            color: #000000;
        }
        .bg-dark {
            background-color: #ff0000;
        }
        .text-white {
            color: #000000;
        }
        .carousel-item {
            padding: 3rem 0;
        }
        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
        }
        .carousel-indicators {
            bottom: -50px;
        }
        .carousel-indicators button {
            background-color: #ff0000 !important;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 5px;
        }
        .section-padding.bg-dark {
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
        }
        .carousel-item .list-unstyled li:hover {
            opacity: 1;
        }
        .feature-icon {
        text-align: center; 
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
                <h1 class="display-4 text-white">Storage <span class="text-danger">Units</span></h1>
            </div>
        </div>
    </section>

    <!-- Storage Units Section -->
    <section class="py-5 storage-units">
        <div class="container">
            <!-- Filter Section -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4 text-dark">Filter Storage Units</h5>
                            <form class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-dark">Size</label>
                                    <select class="form-select">
                                        <option value="">All Sizes</option>
                                        <option value="small">Small (5x5)</option>
                                        <option value="medium">Medium (10x10)</option>
                                        <option value="large">Large (10x20)</option>
                                        <option value="xlarge">Extra Large (20x20)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-dark">Price Range</label>
                                    <select class="form-select">
                                        <option value="">All Prices</option>
                                        <option value="0-50">£0 - £50/month</option>
                                        <option value="51-100">£51 - £100/month</option>
                                        <option value="101-200">£101 - £200/month</option>
                                        <option value="201+">£201+/month</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-dark">Features</label>
                                    <select class="form-select">
                                        <option value="">All Features</option>
                                        <option value="downstairs">Downstairs</option>
                                        <option value="upstairs">upstairs</option>
                                        <option value="open door">open door</option>
                                        <option value="closed door">closed door</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Units Grid -->
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

    <!-- Features Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="text-dark">Storage Unit Features</h2>
                <p class="lead text-muted text-dark">All our storage units come with premium features</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-shield-check text-dark"></i>
                        </div>
                        <h4 class="text-dark">24/7 Security</h4>
                        <p class="text-muted text-dark">Round-the-clock surveillance and security monitoring</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-key text-dark"></i>
                        </div>
                        <h4 class="text-dark">Easy Access</h4>
                        <p class="text-muted text-dark">Convenient access hours and drive-up units</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Slider Section -->
    <section class="section-padding bg-dark text-white">
        <div class="container">
            <div id="storageSlider" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <!-- Slide 1 -->
                    <div class="carousel-item active">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2 class="mb-4">24/7 Security Monitoring</h2>
                                <p class="lead">Your belongings deserve the best protection. Our state-of-the-art security system includes:</p>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-check-circle-fill text-danger me-2"></i>Round-the-clock CCTV surveillance</li>
                                    <li class="mb-2"><i class="bi bi-check-circle-fill text-danger me-2"></i>Advanced access control systems</li>
                                    <li class="mb-2"><i class="bi bi-check-circle-fill text-danger me-2"></i>Professional security staff on premises</li>
                                    <li class="mb-2"><i class="bi bi-check-circle-fill text-danger me-2"></i>Individual unit alarms</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <img src="assets/images/security.jpg" class="img-fluid rounded shadow" alt="Security Monitoring">
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2 class="mb-4">Climate-Controlled Units</h2>
                                <p class="lead">Protect your sensitive items with our climate-controlled storage units:</p>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-check-circle-fill text-danger me-2"></i>Constant temperature monitoring</li>
                                    <li class="mb-2"><i class="bi bi-check-circle-fill text-danger me-2"></i>Humidity control systems</li>
                                    <li class="mb-2"><i class="bi bi-check-circle-fill text-danger me-2"></i>Air filtration</li>
                                    <li class="mb-2"><i class="bi bi-check-circle-fill text-danger me-2"></i>Weather-resistant construction</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <img src="assets/images/climate.jpg" class="img-fluid rounded shadow" alt="Climate Control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#storageSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#storageSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

                <!-- Carousel Indicators -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#storageSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#storageSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding bg-dark text-dark text-center">
        <div class="container">
            <h2 class="mb-4 text-dark">Ready to Store with Us?</h2>
            <p class="lead mb-4 text-dark">Contact us today to reserve your storage unit</p>
            <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-primary btn-lg">Get Started</a>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
