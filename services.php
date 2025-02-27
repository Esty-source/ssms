<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <section class="hero-section page-hero">
        <div class="content">
            <div class="container">
                <h1 class="display-4">Our <span class="text-danger">Services</span></h1>
                <p class="lead">Comprehensive storage and moving solutions for all your needs</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section-padding">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-4">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <h3>Personal Storage</h3>
                            <p class="text-muted">Secure storage solutions for your personal belongings, from small lockers to large units.</p>
                            <ul class="list-unstyled mt-4">
                                <li class="mb-2"><i class="bi bi-check2 text-danger me-2"></i>24/7 Access Available</li>
                                <li class="mb-2"><i class="bi bi-check2 text-danger me-2"></i>Climate Controlled Units</li>
                                <li class="mb-2"><i class="bi bi-check2 text-danger me-2"></i>Flexible Rental Terms</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-4">
                                <i class="bi bi-building"></i>
                            </div>
                            <h3>Business Storage</h3>
                            <p class="text-muted">Professional storage solutions for businesses, including inventory and document storage.</p>
                            <ul class="list-unstyled mt-4">
                                <li class="mb-2"><i class="bi bi-check2 text-danger me-2"></i>Inventory Management</li>
                                <li class="mb-2"><i class="bi bi-check2 text-danger me-2"></i>Secure Document Storage</li>
                                <li class="mb-2"><i class="bi bi-check2 text-danger me-2"></i>Business Account Benefits</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-4">
                                <i class="bi bi-truck"></i>
                            </div>
                            <h3>Moving Services</h3>
                            <p class="text-muted">Professional moving assistance to make your relocation stress-free.</p>
                            <ul class="list-unstyled mt-4">
                                <li class="mb-2"><i class="bi bi-check2 text-danger me-2"></i>Local & Long Distance</li>
                                <li class="mb-2"><i class="bi bi-check2 text-danger me-2"></i>Packing & Unpacking</li>
                                <li class="mb-2"><i class="bi bi-check2 text-danger me-2"></i>Moving Supplies</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Additional Services</h2>
                <p class="lead text-muted">Making your storage experience easier</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon me-4">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div>
                            <h4>Security Services</h4>
                            <p class="text-muted">24/7 surveillance, individual unit alarms, and secure access control.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon me-4">
                            <i class="bi bi-box-arrow-in-down"></i>
                        </div>
                        <div>
                            <h4>Packing Supplies</h4>
                            <p class="text-muted">Wide range of packing materials and boxes available for purchase.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon me-4">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <h4>Flexible Access</h4>
                            <p class="text-muted">Extended access hours and flexible scheduling for your convenience.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="feature-icon me-4">
                            <i class="bi bi-truck-flatbed"></i>
                        </div>
                        <div>
                            <h4>Loading Assistance</h4>
                            <p class="text-muted">Professional help with loading and unloading your items.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding bg-dark text-white text-center">
        <div class="container">
            <h2 class="mb-4">Ready to Get Started?</h2>
            <p class="lead mb-4">Contact us today to learn more about our services</p>
            <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-primary btn-lg">Get in Touch</a>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
