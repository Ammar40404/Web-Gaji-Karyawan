<?php
require 'koneksi.php';

// Ambil kode karyawan dari parameter
if (isset($_GET['kode_karyawan'])) {
    $kode_karyawan = $_GET['kode_karyawan'];

   
    $sql = "SELECT * FROM karyawan WHERE kode_karyawan = ?";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("i", $kode_karyawan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $karyawan = $result->fetch_assoc();
        } else {
            die("Karyawan tidak ditemukan.");
        }

        // Tutup statement
        $stmt->close();
    } else {
        die("Error: " . $koneksi->error);
    }
} else {
    die("Kode Karyawan tidak ditemukan.");
}

// Ambil data perusahaan untuk dropdown
$perusahaanQuery = "SELECT id, nama FROM perusahaan";
$perusahaanResult = $koneksi->query($perusahaanQuery);

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_karyawan = htmlspecialchars(trim($_POST['kode_karyawan']));
    $nama = htmlspecialchars(trim($_POST['nama']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));
    $jabatan = htmlspecialchars(trim($_POST['jabatan']));
    $no_telp = htmlspecialchars(trim($_POST['no_telp']));
    $email = htmlspecialchars(trim($_POST['email']));
    $no_rekening = htmlspecialchars(trim($_POST['no_rekening']));
    $rek_bank = htmlspecialchars(trim($_POST['rek_bank']));
    $id = htmlspecialchars(trim($_POST['id']));

    $sql = "UPDATE karyawan 
            SET nama = ?, alamat = ?, jabatan = ?, no_telp = ?, email = ?, no_rekening = ?, rek_bank = ?, id = ?
            WHERE kode_karyawan = ?";

    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("sssssssii", $nama, $alamat, $jabatan, $no_telp, $email, $no_rekening, $rek_bank, $id, $kode_karyawan);

        // Eksekusi query
        if ($stmt->execute()) {
            header("Location: karyawan.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Tutup statement
        $stmt->close();
    } else {
        echo "Error: " . $koneksi->error;
    }

    // Tutup koneksi
    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<!-- Include Header -->
<?php include 'layout-header.php'; ?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Include Sidebar -->
        <?php include 'layout-sidebar.php'; ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Include Topbar -->
                <?php include 'layout-topbar.php'; ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h2 class="mb-3">Edit Karyawan</h2>
                    <form action="karyawan-edit.php?kode_karyawan=<?= htmlspecialchars($kode_karyawan); ?>" method="POST">
                        <div class="form-group">
                            <label for="kode_karyawan">Kode Karyawan</label>
                            <input type="text" class="form-control" id="kode_karyawan" name="kode_karyawan" value="<?= htmlspecialchars($karyawan['kode_karyawan']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($karyawan['nama']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?= htmlspecialchars($karyawan['alamat']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= htmlspecialchars($karyawan['jabatan']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= htmlspecialchars($karyawan['no_telp']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($karyawan['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="no_rekening">No Rekening</label>
                            <input type="text" class="form-control" id="no_rekening" name="no_rekening" value="<?= htmlspecialchars($karyawan['no_rekening']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="rek_bank">Rekening Bank</label>
                            <input type="text" class="form-control" id="rek_bank" name="rek_bank" value="<?= htmlspecialchars($karyawan['rek_bank']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="id">Perusahaan</label>
                            <select class="form-control" id="id" name="id" required>
                                <option value="">Pilih Perusahaan</option>
                                <?php while ($perusahaan = $perusahaanResult->fetch_assoc()) : ?>
                                    <option value="<?= $perusahaan['id']; ?>" <?= ($karyawan['id'] == $perusahaan['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($perusahaan['nama']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="karyawan.php" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
            <!-- Include Footer -->
            <?php include 'layout-footer.php'; ?>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>