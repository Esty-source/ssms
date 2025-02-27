<?php
require_once '../includes/config.php';
require_once '../includes/Auth.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = '';
    $messageType = '';

    if (isset($_POST['update_profile'])) {
        // Update admin profile
        $stmt = $conn->prepare("
            UPDATE users 
            SET name = ?, email = ?
            WHERE id = ? AND role = 'admin'
        ");
        
        try {
            $stmt->execute([
                $_POST['name'],
                $_POST['email'],
                $_SESSION['user_id']
            ]);
            $message = 'Profile updated successfully';
            $messageType = 'success';
        } catch (PDOException $e) {
            $message = 'Error updating profile: ' . $e->getMessage();
            $messageType = 'danger';
        }
    } elseif (isset($_POST['change_password'])) {
        // Change password
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (password_verify($_POST['current_password'], $user['password'])) {
            if ($_POST['new_password'] === $_POST['confirm_password']) {
                $stmt = $conn->prepare("
                    UPDATE users 
                    SET password = ?
                    WHERE id = ?
                ");
                
                try {
                    $stmt->execute([
                        password_hash($_POST['new_password'], PASSWORD_DEFAULT),
                        $_SESSION['user_id']
                    ]);
                    $message = 'Password changed successfully';
                    $messageType = 'success';
                } catch (PDOException $e) {
                    $message = 'Error changing password: ' . $e->getMessage();
                    $messageType = 'danger';
                }
            } else {
                $message = 'New passwords do not match';
                $messageType = 'danger';
            }
        } else {
            $message = 'Current password is incorrect';
            $messageType = 'danger';
        }
    } elseif (isset($_POST['update_site_settings'])) {
        // Update site settings
        try {
            $settings = [
                'site_title' => $_POST['site_title'],
                'site_email' => $_POST['site_email'],
                'stripe_public_key' => $_POST['stripe_public_key'],
                'stripe_secret_key' => $_POST['stripe_secret_key'],
                'smtp_host' => $_POST['smtp_host'],
                'smtp_port' => $_POST['smtp_port'],
                'smtp_username' => $_POST['smtp_username'],
                'smtp_password' => $_POST['smtp_password']
            ];

            // Update each setting
            $stmt = $conn->prepare("
                INSERT INTO settings (setting_key, setting_value) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
            ");

            foreach ($settings as $key => $value) {
                if (!empty($value)) {
                    $stmt->execute([$key, $value]);
                }
            }

            $message = 'Site settings updated successfully';
            $messageType = 'success';
        } catch (PDOException $e) {
            $message = 'Error updating site settings: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

// Get current admin details
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$admin = $stmt->fetch();

// Get current site settings
$stmt = $conn->prepare("SELECT setting_key, setting_value FROM settings");
$stmt->execute();
$settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Settings</h1>
                </div>

                <?php if (isset($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Settings Tabs -->
                <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab">
                            <i class="bi bi-person"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="security-tab" data-bs-toggle="tab" href="#security" role="tab">
                            <i class="bi bi-shield-lock"></i> Security
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="site-tab" data-bs-toggle="tab" href="#site" role="tab">
                            <i class="bi bi-gear"></i> Site Settings
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="settingsTabContent">
                    <!-- Profile Settings -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Profile Settings</h5>
                                <form method="post" class="mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" 
                                               value="<?php echo htmlspecialchars($admin['name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                                    </div>
                                    <button type="submit" name="update_profile" class="btn btn-primary">
                                        Update Profile
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Change Password</h5>
                                <form method="post" class="mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="new_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" name="confirm_password" required>
                                    </div>
                                    <button type="submit" name="change_password" class="btn btn-primary">
                                        Change Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Site Settings -->
                    <div class="tab-pane fade" id="site" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Site Settings</h5>
                                <form method="post" class="mt-4">
                                    <h6 class="mb-3">General</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Site Title</label>
                                        <input type="text" class="form-control" name="site_title" 
                                               value="<?php echo htmlspecialchars($settings['site_title'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Site Email</label>
                                        <input type="email" class="form-control" name="site_email" 
                                               value="<?php echo htmlspecialchars($settings['site_email'] ?? ''); ?>">
                                    </div>

                                    <h6 class="mb-3 mt-4">Payment Gateway</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Stripe Public Key</label>
                                        <input type="text" class="form-control" name="stripe_public_key" 
                                               value="<?php echo htmlspecialchars($settings['stripe_public_key'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stripe Secret Key</label>
                                        <input type="password" class="form-control" name="stripe_secret_key" 
                                               value="<?php echo htmlspecialchars($settings['stripe_secret_key'] ?? ''); ?>">
                                    </div>

                                    <h6 class="mb-3 mt-4">Email Settings</h6>
                                    <div class="mb-3">
                                        <label class="form-label">SMTP Host</label>
                                        <input type="text" class="form-control" name="smtp_host" 
                                               value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">SMTP Port</label>
                                        <input type="number" class="form-control" name="smtp_port" 
                                               value="<?php echo htmlspecialchars($settings['smtp_port'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">SMTP Username</label>
                                        <input type="text" class="form-control" name="smtp_username" 
                                               value="<?php echo htmlspecialchars($settings['smtp_username'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">SMTP Password</label>
                                        <input type="password" class="form-control" name="smtp_password" 
                                               value="<?php echo htmlspecialchars($settings['smtp_password'] ?? ''); ?>">
                                    </div>

                                    <button type="submit" name="update_site_settings" class="btn btn-primary">
                                        Save Settings
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show the tab specified in the URL hash, if any
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash;
            if (hash) {
                const tab = new bootstrap.Tab(document.querySelector(`a[href="${hash}"]`));
                tab.show();
            }
        });

        // Update URL hash when changing tabs
        document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                window.location.hash = e.target.getAttribute('href');
            });
        });
    </script>
</body>
</html>
