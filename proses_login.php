<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'Username dan password wajib diisi.';
        header('Location: index.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, password, nama FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];

            header('Location: dashboard.php');
            exit;
        } else {
            $_SESSION['login_error'] = 'Password salah.';
        }
    } else {
        $_SESSION['login_error'] = 'Username tidak ditemukan.';
    }

    $stmt->close();
    header('Location: index.php');
    exit;
} else {
    header('Location: index.php');
    exit;
}
