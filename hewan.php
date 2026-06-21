<?php
require_once 'includes/header.php';

$query = "SELECT * FROM hewan ORDER BY id DESC";
$result = $conn->query($query);
?>

<div class="page-title">
    <i class="fas fa-paw"></i> Data Hewan
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-table me-1"></i> Daftar Inventaris Hewan</span>
        <a href="tambah_hewan.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Data
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable">
                <thead class="table-dark">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%" class="text-center">Gambar</th>
                        <th width="15%">Kode Hewan</th>
                        <th width="20%">Nama Hewan</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Jumlah</th>
                        <th width="25%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result->num_rows > 0): 
                        $no = 1;
                        while($row = $result->fetch_assoc()): 
                    ?>
                    <tr>
                        <td class="text-center align-middle"><?php echo $no++; ?></td>
                        <td class="text-center align-middle">
                            <?php if($row['gambar'] && file_exists('uploads/' . $row['gambar'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gambar" class="table-img">
                            <?php else: ?>
                                <img src="assets/img/no-image.png" alt="No Image" class="table-img">
                            <?php endif; ?>
                        </td>
                        <td class="align-middle fw-bold"><?php echo htmlspecialchars($row['kode_hewan']); ?></td>
                        <td class="align-middle">
                            <?php echo htmlspecialchars($row['nama_hewan']); ?><br>
                            <small class="text-muted"><i><?php echo htmlspecialchars($row['nama_latin']); ?></i></small>
                        </td>
                        <td class="align-middle"><?php echo htmlspecialchars($row['kategori']); ?></td>
                        <td class="align-middle"><?php echo htmlspecialchars($row['jumlah']); ?> Ekor</td>
                        <td class="text-center align-middle">
                            <a href="detail_hewan.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white" title="Detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="edit_hewan.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="hapus_hewan.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger btn-delete" title="Hapus">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    endif; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
