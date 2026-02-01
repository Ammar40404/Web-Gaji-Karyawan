<?php
// Memanggil file koneksi
include 'koneksi.php';

// Debug: Check if $koneksi is set
if (!isset($koneksi)) {
    die("Koneksi database tidak ditemukan. Periksa file koneksi.php.");
}

// Ambil ID perusahaan dari parameter URL
if (isset($_GET['id'])) {
    $id_perusahaan = $_GET['id'];

    // Query untuk mendapatkan data perusahaan berdasarkan ID
    $query = "SELECT * FROM perusahaan WHERE id = ?";
    if ($stmt = $koneksi->prepare($query)) {
        $stmt->bind_param("s", $id_perusahaan);
        $stmt->execute();
        $result = $stmt->get_result();
        $perusahaan = $result->fetch_assoc();
        $stmt->close();
    }
}

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_perusahaan = htmlspecialchars(trim($_POST['id']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));
    $nama_perusahaan = htmlspecialchars(trim($_POST['nama']));
    $no_telepon = htmlspecialchars(trim($_POST['no_telepon']));
    $email = htmlspecialchars(trim($_POST['email']));

    // Query untuk update data
    $query = "UPDATE perusahaan SET nama=?, alamat=?, no_telepon=?, email=? WHERE id=?";
    if ($stmt = $koneksi->prepare($query)) {
        $stmt->bind_param("sssss", $nama_perusahaan, $alamat, $no_telepon, $email, $id_perusahaan);
        if ($stmt->execute()) {
            echo "Data perusahaan berhasil diperbarui!";
            header("Location: perusahaan.php");
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
                    <h2 class="mb-3">Edit Perusahaan</h2>
                    <form action="perusahaan-edit.php?id=<?= htmlspecialchars($id_perusahaan); ?>" method="POST">
                        <div class="form-group">
                            <label for="id">ID Perusahaan</label>
                            <input type="text" class="form-control" id="id" name="id" value="<?= htmlspecialchars($perusahaan['id']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Perusahaan</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($perusahaan['nama']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nama">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?= htmlspecialchars($perusahaan['alamat']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="no_telepon">No Telepon</label>
                            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= htmlspecialchars($perusahaan['no_telepon']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($perusahaan['email']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="perusahaan.php" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
                <?php include 'layout-footer.php'; ?>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>