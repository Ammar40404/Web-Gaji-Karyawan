<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars(trim($_POST['nama']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));
    $no_telepon = htmlspecialchars(trim($_POST['no_telepon']));
    $email = htmlspecialchars(trim($_POST['email']));

    $sql = "INSERT INTO perusahaan (nama, alamat, no_telepon, email) VALUES (?, ?, ?, ?)";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("ssss", $nama, $alamat, $no_telepon, $email);
        if ($stmt->execute()) {
            header("Location: perusahaan.php");
            exit;
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
                    <h2 class="mb-3">Tambah Perusahaan</h2>
                    <form action="perusahaan-tambah.php" method="POST">
                        <div class="form-group">
                            <label for="nama">Nama Perusahaan</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="nama">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
                        </div>
                        <div class="form-group">
                            <label for="no_telepon">No Telepon</label>
                            <input type="text" class="form-control" id="no_telepon" name="no_telepon" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
