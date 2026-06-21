<?php
require_once 'includes/header.php';

$id = $_GET['id'] ?? 0;
if (!$id) {
    header('Location: hewan.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_hewan    = trim($_POST['kode_hewan']);
    $nama_hewan    = trim($_POST['nama_hewan']);
    $nama_latin    = trim($_POST['nama_latin']);
    $kategori      = trim($_POST['kategori']);
    $habitat       = trim($_POST['habitat']);
    $jumlah        = (int)$_POST['jumlah'];
    $tanggal_masuk = trim($_POST['tanggal_masuk']);
    $keterangan    = trim($_POST['keterangan']);
    $gambar_lama   = $_POST['gambar_lama'];
    
    $gambar = $gambar_lama;
    $upload_ok = true;
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($file_ext, $allowed_ext)) {
            $error = 'Format file tidak didukung.';
            $upload_ok = false;
        } elseif ($file_size > 5 * 1024 * 1024) {
            $error = 'Ukuran gambar maksimal 5MB.';
            $upload_ok = false;
        } else {
            $gambar_baru = uniqid('hwn_') . '.' . $file_ext;
            if (move_uploaded_file($file_tmp, 'uploads/' . $gambar_baru)) {
                $gambar = $gambar_baru;
                
                if ($gambar_lama && file_exists('uploads/' . $gambar_lama)) {
                    unlink('uploads/' . $gambar_lama);
                }
            } else {
                $error = 'Gagal upload gambar.';
                $upload_ok = false;
            }
        }
    }

    if ($upload_ok && empty($error)) {
        $stmt = $conn->prepare("UPDATE hewan SET kode_hewan=?, nama_hewan=?, nama_latin=?, kategori=?, habitat=?, jumlah=?, tanggal_masuk=?, keterangan=?, gambar=? WHERE id=?");
        $stmt->bind_param("sssssisssi", $kode_hewan, $nama_hewan, $nama_latin, $kategori, $habitat, $jumlah, $tanggal_masuk, $keterangan, $gambar, $id);
        
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = 'Data berhasil diupdate!';
            $_SESSION['flash_type'] = 'success';
            header('Location: hewan.php');
            exit;
        } else {
            $error = 'Gagal update: ' . $conn->error;
        }
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT * FROM hewan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['flash_message'] = 'Data tidak ditemukan.';
    $_SESSION['flash_type'] = 'error';
    header('Location: hewan.php');
    exit;
}

$row = $result->fetch_assoc();
$stmt->close();
?>

<div class="page-title">
    <i class="fas fa-edit"></i> Edit Data Hewan
</div>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        Form Edit Data
    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($row['gambar']); ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kode Hewan</label>
                    <input type="text" class="form-control" name="kode_hewan" value="<?php echo htmlspecialchars($row['kode_hewan']); ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_masuk" value="<?php echo htmlspecialchars($row['tanggal_masuk']); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nama Hewan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama_hewan" value="<?php echo htmlspecialchars($row['nama_hewan']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nama Latin <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama_latin" value="<?php echo htmlspecialchars($row['nama_latin']); ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select" name="kategori" required>
                        <?php
                        $categories = ['Mamalia', 'Reptil', 'Aves (Burung)', 'Amfibi', 'Pisces (Ikan)', 'Insecta (Serangga)'];
                        foreach ($categories as $cat) {
                            $selected = ($row['kategori'] == $cat) ? 'selected' : '';
                            echo "<option value=\"$cat\" $selected>$cat</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="jumlah" min="1" value="<?php echo htmlspecialchars($row['jumlah']); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Habitat <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="habitat" value="<?php echo htmlspecialchars($row['habitat']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Keterangan Tambahan</label>
                <textarea class="form-control" name="keterangan" rows="3"><?php echo htmlspecialchars($row['keterangan']); ?></textarea>
            </div>

            <div class="mb-3 border p-3 bg-light rounded">
                <label class="form-label fw-bold">Gambar Saat Ini:</label><br>
                <?php if($row['gambar'] && file_exists('uploads/' . $row['gambar'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" class="img-thumbnail mb-3" style="max-height: 150px;">
                <?php else: ?>
                    <div class="alert alert-secondary py-2">Tidak ada gambar tersimpan.</div>
                <?php endif; ?>
                
                <label class="form-label fw-bold">Ganti Gambar (Opsional)</label>
                <input type="file" class="form-control mb-2" id="gambarInput" name="gambar" accept="image/*">
                <div class="mt-2 text-center">
                    <img id="imgPreview" src="" alt="Preview Gambar Baru" class="img-thumbnail" style="max-height: 200px; display: none;">
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update Data</button>
            <a href="hewan.php" class="btn btn-outline-secondary"><i class="fas fa-times"></i> Batal</a>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
