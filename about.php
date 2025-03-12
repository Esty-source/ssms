<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* Override any blue headings to be black */
        h1, h2, h3, h4, h5, h6 {
            color: #000 !important;
        }
        
        /* Keep headings in hero section and dark sections white */
        .hero-section h1,
        .hero-section h2,
        .bg-dark h1,
        .bg-dark h2,
        .bg-dark h3,
        .bg-dark h4,
        .bg-dark h5,
        .bg-dark h6 {
            color: #fff !important;
        }
        
        /* Keep red text elements red */
        .text-danger {
            color: #dc3545 !important;
        }
        
        /* Debug styles for hero background */
        .page-hero .hero-bg-image {
            border: 5px solid red;
            background-color: blue;
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
                <h1 class="display-4 text-white">About <span class="text-danger">Safe Lock</span> Storage</h1>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="assets/images/climate.jpg" alt="Our Storage Facility" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h2 class="mb-4">Our Story</h2>
                    <p class="lead mb-4">Founded in 2010, Safe Lock Storage has been providing secure and reliable storage solutions to Birmingham and surrounding areas.</p>
                    <p>What started as a small family business has grown into one of the most trusted storage facilities in the region. Our commitment to security, customer service, and flexibility has made us the go-to choice for both personal and business storage needs.</p>
                    <div class="mt-4">
                        <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-primary">Get in Touch</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Our Values</h2>
                <p class="lead">The principles that guide everything we do</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 fade-in">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon mb-4">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h5 class="card-title">Security First</h5>
                            <p class="card-text">Your belongings' safety is our top priority. We employ state-of-the-art security systems and 24/7 monitoring.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 fade-in">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon mb-4">
                                <i class="bi bi-people"></i>
                            </div>
                            <h5 class="card-title">Customer Focus</h5>
                            <p class="card-text">We believe in building lasting relationships with our customers through exceptional service and support.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 fade-in">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon mb-4">
                                <i class="bi bi-arrow-up-circle"></i>
                            </div>
                            <h5 class="card-title">Continuous Improvement</h5>
                            <p class="card-text">We constantly invest in our facility and services to provide the best storage experience possible.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Our Leadership Team</h2>
                <p class="lead">Meet the people behind Safe Lock Storage</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 fade-in">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="assets/images/toni.jpg" alt="Toni Ebong" class="rounded-circle mb-4" style="width: 150px; height: 150px; object-fit: cover;">
                            <h5 class="card-title">Toni Ebong</h5>
                            <p class="text-danger mb-2">CEO & Co-Founder</p>
                            <p class="card-text">With over 20 years in the storage industry, Toni leads our company with passion and innovation.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 fade-in">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="assets/images/team-3.jpg" alt="Alieu Jagne" class="rounded-circle mb-4" style="width: 150px; height: 150px; object-fit: cover;">
                            <h5 class="card-title">Alieu Jagne</h5>
                            <p class="text-danger mb-2">Co-Founder</p>
                            <p class="card-text">Jagne helps drive innovative storage solutions with a passion for excellence and customer satisfaction.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 fade-in">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="assets/images/bao.jpg" alt="Bao Jr" class="rounded-circle mb-4" style="width: 150px; height: 150px; object-fit: cover;">
                            <h5 class="card-title">Bao Jr</h5>
                            <p class="text-danger mb-2">Operations Manager</p>
                            <p class="card-text">Bao Jr ensures smooth daily operations and maintains our high service standards.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section-padding bg-dark text-white">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-3 fade-in">
                    <h2 class="display-4 mb-3">45+</h2>
                    <p class="lead">Storage Units</p>
                </div>
                <div class="col-md-3 fade-in">
                    <h2 class="display-4 mb-3">100+</h2>
                    <p class="lead">Happy Customers</p>
                </div>
                <div class="col-md-3 fade-in">
                    <h2 class="display-4 mb-3">13</h2>
                    <p class="lead">Years Experience</p>
                </div>
                <div class="col-md-3 fade-in">
                    <h2 class="display-4 mb-3">24/7</h2>
                    <p class="lead">Security</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding bg-light text-center">
        <div class="container">
            <h2 class="mb-4">Ready to Get Started?</h2>
            <p class="lead mb-4">Experience the Safe Lock Storage difference today</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="<?php echo BASE_URL; ?>containers.php" class="btn btn-primary btn-lg">View Storage Units</a>
                <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-outline-primary btn-lg">Contact Us</a>
            </div>
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
