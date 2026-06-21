<?php
require_once 'includes/header.php';

$query = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($query);
?>

<div class="page-title">
    <i class="fas fa-users"></i> Manajemen User
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-table me-1"></i> Daftar User</span>
        <a href="tambah_user.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable">
                <thead class="table-dark">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="30%">Nama Lengkap</th>
                        <th width="20%">Username</th>
                        <th width="20%">Password</th>
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
                        <td class="align-middle fw-bold"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td class="align-middle"><?php echo htmlspecialchars($row['username']); ?></td>
                        <td class="align-middle"><?php echo htmlspecialchars($row['password']); ?></td>
                        <td class="text-center align-middle">
                            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <?php if ($row['id'] != $_SESSION['user_id']): ?>
                            <a href="hapus_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus user ini?');">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                            <?php else: ?>
                            <button class="btn btn-sm btn-secondary" disabled title="Tidak dapat menghapus diri sendiri"><i class="fas fa-trash"></i> Hapus</button>
                            <?php endif; ?>
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
