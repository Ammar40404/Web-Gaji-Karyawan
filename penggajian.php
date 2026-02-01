<?php
// Memanggil file koneksi
include 'koneksi.php';

// Query untuk mengambil data slip gaji beserta detail gaji
$query = "SELECT sg.no_ref, sg.tgl, sg.total_gaji, sg.kode_karyawan, k.nama 
          FROM slip_gaji sg
          JOIN karyawan k ON sg.kode_karyawan = k.kode_karyawan";
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
                    <h2 class="mb-3">Daftar Penggajian</h2>
                    <div class="text-right mb-3">
                        <a href="penggajian-tambah.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Penggajian
                        </a>
                    </div>
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>No. Ref</th>
                                <th>Tanggal</th>
                                <th>Total Gaji</th>
                                <th>Kode Karyawan</th>
                                <th>Nama Karyawan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['no_ref']); ?></td>
                                    <td><?= htmlspecialchars($row['tgl']); ?></td>
                                    <td><?= htmlspecialchars($row['total_gaji']); ?></td>
                                    <td><?= htmlspecialchars($row['kode_karyawan']); ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td class="d-flex">
                                        <a href="penggajian-edit.php?no_ref=<?= htmlspecialchars($row['no_ref']); ?>" class="btn btn-warning btn-sm mr-2">Edit</a>
                                        <a href="penggajian-hapus.php?no_ref=<?= htmlspecialchars($row['no_ref']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</a>
                                        <a href="cetak-slip-gaji.php?no_ref=<?= htmlspecialchars($row['no_ref']); ?>" class="btn btn-info btn-sm" target="_blank">Cetak Slip</a>
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