<?php
require_once 'includes/config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        // Find user with this token
        $stmt = $conn->prepare("SELECT id FROM users WHERE verification_token = ? AND email_verified = 0");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Update user as verified
            $stmt = $conn->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);

            $_SESSION['success'] = "Email verified successfully! You can now login.";
        } else {
            $_SESSION['error'] = "Invalid verification token or account already verified.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $_SESSION['error'] = "An error occurred during verification. Please try again.";
    }
}

header('Location: login.php');
exit;
