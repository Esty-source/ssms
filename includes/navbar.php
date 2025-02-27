<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
            <?php echo SITE_TITLE; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/index.php' ? ' active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php echo strpos($_SERVER['PHP_SELF'], '/containers.php') !== false ? ' active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>containers.php">Storage Units</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php echo strpos($_SERVER['PHP_SELF'], '/moving-services.php') !== false ? ' active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>moving-services.php">Moving Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php echo strpos($_SERVER['PHP_SELF'], '/contact.php') !== false ? ' active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>contact.php">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>admin/">Admin Panel</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile.php">My Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>bookings.php">My Bookings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
