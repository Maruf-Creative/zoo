<?php
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $error = '';
    
    if (empty($nama) || empty($username) || empty($password)) {
        $error = 'Semua kolom wajib diisi.';
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Username sudah digunakan.';
        }
        $check->close();
    }
    
    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO users (username, password, nama) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $nama);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'User berhasil ditambahkan.';
            echo "<script>window.location.href = 'users.php';</script>";
            exit;
        } else {
            $error = 'Terjadi kesalahan saat menyimpan data.';
        }
        $stmt->close();
    }
}
?>

<div class="page-title">
    <i class="fas fa-user-plus"></i> Tambah User Baru
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Form Tambah User
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan User</button>
                    <a href="users.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
