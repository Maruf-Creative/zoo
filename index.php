<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
$error = $_SESSION['login_error'] ?? '';
$success = $_SESSION['register_success'] ?? '';
unset($_SESSION['login_error']);
unset($_SESSION['register_success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zoo Admin</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">

<div class="auth-card">
    <div class="auth-logo">
        <i class="fas fa-leaf text-success"></i> Zoo Admin
    </div>
    <p class="text-center text-muted mb-4">Silakan login untuk masuk ke sistem</p>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2 text-center">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success py-2 text-center">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" name="username" required autofocus>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" name="password" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>
    </form>
    
    </div>

</body>
</html>
