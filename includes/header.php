<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoo Admin - Sistem Data Hewan</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4>Zoo Admin</h4>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Navigasi</div>

        <a href="dashboard.php" class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <a href="hewan.php" class="nav-link <?php echo in_array($current_page, ['hewan.php','detail_hewan.php']) ? 'active' : ''; ?>">
            <i class="fas fa-paw"></i> Data Hewan
        </a>

        <a href="tambah_hewan.php" class="nav-link <?php echo in_array($current_page, ['tambah_hewan.php','edit_hewan.php']) ? 'active' : ''; ?>">
            <i class="fas fa-plus-square"></i> Tambah Hewan
        </a>

        <div class="nav-label">Laporan</div>

        <a href="laporan_pdf.php" class="nav-link" target="_blank">
            <i class="fas fa-print"></i> Cetak Laporan
        </a>
        
        <div class="nav-label">Pengaturan</div>
        
        <a href="users.php" class="nav-link <?php echo in_array($current_page, ['users.php','tambah_user.php','edit_user.php']) ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Manajemen User
        </a>

        <a href="logout.php" class="nav-link text-danger" onclick="return confirm('Yakin ingin logout?')">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</aside>

<div class="main-content" id="mainContent">
    <nav class="top-navbar">
        <div>
            <button class="btn btn-sm btn-outline-secondary d-md-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div>
            <span class="text-muted"><i class="fas fa-user-circle"></i> Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        </div>
    </nav>

    <div class="content-wrapper">
