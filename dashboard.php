<?php
require_once 'includes/header.php';

$query_total = "SELECT COUNT(*) as total FROM hewan";
$result_total = $conn->query($query_total);
$total_hewan = $result_total->fetch_assoc()['total'];

$query_kat = "SELECT COUNT(DISTINCT kategori) as total FROM hewan";
$result_kat = $conn->query($query_kat);
$total_kategori = $result_kat->fetch_assoc()['total'];

$query_pop = "SELECT SUM(jumlah) as total FROM hewan";
$result_pop = $conn->query($query_pop);
$total_populasi = $result_pop->fetch_assoc()['total'] ?? 0;

$query_latest = "SELECT * FROM hewan ORDER BY id DESC LIMIT 5";
$latest_hewan = $conn->query($query_latest);
?>

<div class="page-title">
    <i class="fas fa-home"></i> Dashboard
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white" style="background-color: #13492f;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Jenis Hewan</h5>
                        <h2 class="mb-0"><?php echo $total_hewan; ?></h2>
                    </div>

                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link text-decoration-none" href="hewan.php">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white" style="background-color: #2b7c4a;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Populasi</h5>
                        <h2 class="mb-0"><?php echo $total_populasi; ?></h2>
                    </div>

                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <span class="small text-white">Berdasarkan jumlah ekor</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white" style="background-color: #62b368;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Kategori Hewan</h5>
                        <h2 class="mb-0"><?php echo $total_kategori; ?></h2>
                    </div>

                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <span class="small text-white">Mamalia, Reptil, dll</span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-table me-1"></i> Data Hewan Terbaru
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Hewan</th>
                        <th>Kategori</th>
                        <th>Tanggal Masuk</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($latest_hewan->num_rows > 0): $no=1; ?>
                        <?php while($row = $latest_hewan->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_hewan']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row['tanggal_masuk'])); ?></td>
                                <td class="text-center">
                                    <a href="detail_hewan.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data hewan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
