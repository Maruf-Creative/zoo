<?php
require_once 'includes/header.php';

$kode_hewan = $nama_hewan = $nama_latin = $kategori = $habitat = $jumlah = $tanggal_masuk = $keterangan = '';
$error = '';

$query_kode = "SELECT MAX(id) as max_id FROM hewan";
$res_kode = $conn->query($query_kode);
$row_kode = $res_kode->fetch_assoc();
$next_id = ($row_kode['max_id'] ?? 0) + 1;
$kode_hewan = 'HWN-' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_hewan    = trim($_POST['kode_hewan']);
    $nama_hewan    = trim($_POST['nama_hewan']);
    $nama_latin    = trim($_POST['nama_latin']);
    $kategori      = trim($_POST['kategori']);
    $habitat       = trim($_POST['habitat']);
    $jumlah        = (int)$_POST['jumlah'];
    $tanggal_masuk = trim($_POST['tanggal_masuk']);
    $keterangan    = trim($_POST['keterangan']);
    
    $gambar = null;
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
            $gambar = uniqid('hwn_') . '.' . $file_ext;
            if (!move_uploaded_file($file_tmp, 'uploads/' . $gambar)) {
                $error = 'Gagal upload gambar.';
                $upload_ok = false;
            }
        }
    } else {
        $error = 'Gambar wajib diupload.';
        $upload_ok = false;
    }

    if ($upload_ok && empty($error)) {
        $stmt = $conn->prepare("INSERT INTO hewan (kode_hewan, nama_hewan, nama_latin, kategori, habitat, jumlah, tanggal_masuk, keterangan, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssisss", $kode_hewan, $nama_hewan, $nama_latin, $kategori, $habitat, $jumlah, $tanggal_masuk, $keterangan, $gambar);
        
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = 'Data hewan berhasil ditambahkan!';
            $_SESSION['flash_type'] = 'success';
            header('Location: hewan.php');
            exit;
        } else {
            $error = 'Gagal menyimpan: ' . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="page-title">
    <i class="fas fa-plus-square"></i> Tambah Data Hewan
</div>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        Form Input Data Hewan Baru
    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kode Hewan</label>
                    <input type="text" class="form-control" name="kode_hewan" value="<?php echo htmlspecialchars($kode_hewan); ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_masuk" value="<?php echo htmlspecialchars($tanggal_masuk ?: date('Y-m-d')); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nama Hewan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama_hewan" value="<?php echo htmlspecialchars($nama_hewan); ?>" placeholder="Contoh: Harimau Sumatera" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nama Latin <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama_latin" value="<?php echo htmlspecialchars($nama_latin); ?>" placeholder="Contoh: Panthera tigris sumatrae" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php
                        $categories = ['Mamalia', 'Reptil', 'Aves (Burung)', 'Amfibi', 'Pisces (Ikan)', 'Insecta (Serangga)'];
                        foreach ($categories as $cat) {
                            $selected = ($kategori == $cat) ? 'selected' : '';
                            echo "<option value=\"$cat\" $selected>$cat</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="jumlah" min="1" value="<?php echo htmlspecialchars($jumlah ?: 1); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Habitat Asal <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="habitat" value="<?php echo htmlspecialchars($habitat); ?>" placeholder="Contoh: Hutan Hujan Tropis Sumatera" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Keterangan Tambahan</label>
                <textarea class="form-control" name="keterangan" rows="3" placeholder="Deskripsi mengenai kesehatan hewan, asal, dsb..."><?php echo htmlspecialchars($keterangan); ?></textarea>
            </div>

            <div class="mb-3 border p-3 bg-light rounded">
                <label class="form-label fw-bold">Upload Foto Hewan <span class="text-danger">*</span></label>
                <input type="file" class="form-control mb-2" id="gambarInput" name="gambar" accept="image/*" required>
                <div class="mt-2 text-center">
                    <img id="imgPreview" src="" alt="Preview Gambar" class="img-thumbnail" style="max-height: 200px; display: none;">
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
            <a href="hewan.php" class="btn btn-outline-secondary"><i class="fas fa-times"></i> Batal</a>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
