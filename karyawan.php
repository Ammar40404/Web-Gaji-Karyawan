<?php
// Memanggil file koneksi
include 'koneksi.php';

// Debug: Check if $koneksi is set
if (!isset($koneksi)) {
    die("Koneksi database tidak ditemukan. Periksa file koneksi.php.");
}

$query = "SELECT * FROM karyawan";
$result = $koneksi->query($query);

if (!$result) {
    die("Query gagal: " . $koneksi->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'layout-header.php'; ?>

<body id="page-top">
    <div id="wrapper">
        <?php include 'layout-sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'layout-topbar.php'; ?>

                <div class="container-fluid">
                    <h2 class="mb-3">Daftar Karyawan</h2>
                    <div class="text-right mb-3">
                        <!-- Tombol Tambah Karyawan -->
                        <a href="karyawan-tambah.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Karyawan
                        </a>
                        <!-- Tombol Cetak -->
                        <a href="cetak-karyawan.php" target="_blank" class="btn btn-success ml-2">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    </div>
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Kode Karyawan</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>No Telepon</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['kode_karyawan']); ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars($row['jabatan']); ?></td>
                                    <td><?= htmlspecialchars($row['no_telp']); ?></td>
                                    <td><?= htmlspecialchars($row['email']); ?></td>
                                    <td class="d-flex">
                                        <a href="karyawan-edit.php?kode_karyawan=<?= htmlspecialchars($row['kode_karyawan']); ?>" class="btn btn-warning btn-sm mr-2">Edit</a>
                                        <a href="karyawan-hapus.php?kode_karyawan=<?= htmlspecialchars($row['kode_karyawan']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php include 'layout-footer.php'; ?>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>

<?php
$koneksi->close();
?>