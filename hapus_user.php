<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? 0;

if ($id == $_SESSION['user_id']) {
    $_SESSION['error'] = 'Anda tidak dapat menghapus akun Anda sendiri.';
    header('Location: users.php');
    exit;
}

$check = $conn->prepare("SELECT id FROM users WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
if ($check->get_result()->num_rows === 0) {
    $_SESSION['error'] = 'User tidak ditemukan.';
    $check->close();
    header('Location: users.php');
    exit;
}
$check->close();

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Data user berhasil dihapus.';
} else {
    $_SESSION['error'] = 'Gagal menghapus data user.';
}

$stmt->close();
header('Location: users.php');
exit;
