<?php
require_once 'includes/header.php';

$id = $_GET['id'] ?? 0;
if (!$id) {
    header('Location: hewan.php');
    exit;
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
    <i class="fas fa-info-circle"></i> Informasi Detail Hewan
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-1"></i> Rincian Data</span>
        <div>
            <a href="edit_hewan.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Edit Data
            </a>
            <a href="hewan.php" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="border rounded p-2 bg-light">
                    <?php if($row['gambar'] && file_exists('uploads/' . $row['gambar'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gambar" class="detail-img">
                    <?php else: ?>
                        <img src="assets/img/no-image.png" alt="No Image" class="detail-img">
                        <p class="text-muted mt-2 small">Foto tidak tersedia</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table">
                    <tr>
                        <th width="30%">Kode Hewan</th>
                        <td><?php echo htmlspecialchars($row['kode_hewan']); ?></td>
                    </tr>
                    <tr>
                        <th>Nama Hewan</th>
                        <td><?php echo htmlspecialchars($row['nama_hewan']); ?></td>
                    </tr>
                    <tr>
                        <th>Nama Latin</th>
                        <td><?php echo htmlspecialchars($row['nama_latin']); ?></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                    </tr>
                    <tr>
                        <th>Habitat Asal</th>
                        <td><?php echo htmlspecialchars($row['habitat']); ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Populasi</th>
                        <td><?php echo htmlspecialchars($row['jumlah']); ?> Ekor</td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk</th>
                        <td><?php echo date('d-m-Y', strtotime($row['tanggal_masuk'])); ?></td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td><?php echo nl2br(htmlspecialchars($row['keterangan'])); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
