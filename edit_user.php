<?php
require_once 'includes/header.php';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = 'User tidak ditemukan.';
    echo "<script>window.location.href = 'users.php';</script>";
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $error = '';
    
    if (empty($nama) || empty($username)) {
        $error = 'Nama dan Username wajib diisi.';
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $check->bind_param("si", $username, $id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Username sudah digunakan oleh user lain.';
        }
        $check->close();
    }
    
    if (empty($error)) {
        if (!empty($password)) {
            $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nama, $username, $password, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nama, $username, $id);
        }
        
        if (empty($error)) {
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Data user berhasil diperbarui.';
                echo "<script>window.location.href = 'users.php';</script>";
                exit;
            } else {
                $error = 'Terjadi kesalahan saat memperbarui data.';
            }
            $stmt->close();
        }
    }
}
?>

<div class="page-title">
    <i class="fas fa-user-edit"></i> Edit User
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Form Edit User
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($_POST['nama'] ?? $user['nama']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? $user['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru (Opsional)</label>
                        <input type="password" class="form-control" name="password">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Data</button>
                    <a href="users.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
