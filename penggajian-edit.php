<?php
require 'koneksi.php';

if (isset($_GET['no_ref'])) {
    $no_ref = $_GET['no_ref'];

    $sql = "SELECT * FROM slip_gaji WHERE no_ref = ?";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("s", $no_ref);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $slip_gaji = $result->fetch_assoc();
        } else {
            die("Slip gaji tidak ditemukan.");
        }
        $stmt->close();
    } else {
        die("Error: " . $koneksi->error);
    }
} else {
    die("No. Ref tidak ditemukan.");
}

$karyawanQuery = "SELECT kode_karyawan, nama FROM karyawan";
$karyawanResult = $koneksi->query($karyawanQuery);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_ref = htmlspecialchars(trim($_POST['no_ref']));
    $tgl = htmlspecialchars(trim($_POST['tgl']));
    $total_gaji = htmlspecialchars(trim($_POST['total_gaji']));
    $kode_karyawan = htmlspecialchars(trim($_POST['kode_karyawan']));

    // Update data 
    $sql = "UPDATE slip_gaji 
            SET tgl = ?, total_gaji = ?, kode_karyawan = ?
            WHERE no_ref = ?";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("ssss", $tgl, $total_gaji, $kode_karyawan, $no_ref);
        if ($stmt->execute()) {
            header("Location: penggajian.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $koneksi->close();
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
                    <h2 class="mb-3">Edit Penggajian</h2>
                    <form action="penggajian-edit.php?no_ref=<?= htmlspecialchars($no_ref); ?>" method="POST">
                        <div class="form-group">
                            <label for="no_ref">No. Ref</label>
                            <input type="text" class="form-control" id="no_ref" name="no_ref" value="<?= htmlspecialchars($slip_gaji['no_ref']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tgl">Tanggal</label>
                            <input type="date" class="form-control" id="tgl" name="tgl" value="<?= htmlspecialchars($slip_gaji['tgl']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="total_gaji">Total Gaji</label>
                            <input type="text" class="form-control" id="total_gaji" name="total_gaji" value="<?= htmlspecialchars($slip_gaji['total_gaji']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="kode_karyawan">Karyawan</label>
                            <select class="form-control" id="kode_karyawan" name="kode_karyawan" required>
                                <option value="">Pilih Karyawan</option>
                                <?php while ($karyawan = $karyawanResult->fetch_assoc()) : ?>
                                    <option value="<?= $karyawan['kode_karyawan']; ?>" <?= ($slip_gaji['kode_karyawan'] == $karyawan['kode_karyawan']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($karyawan['nama']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="penggajian.php" class="btn btn-danger">Batal</a>
                    </form>
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