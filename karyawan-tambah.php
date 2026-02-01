<?php
include 'koneksi.php';

function generateKodeKaryawan($koneksi) {
    $query = "SELECT MAX(kode_karyawan) as last_kode FROM karyawan";
    $result = $koneksi->query($query);
    $row = $result->fetch_assoc();
    $last_kode = $row['last_kode'];

    if ($last_kode) {
        $next_number = (int) $last_kode + 1; 
    } else {
        $next_number = 1; 
    }

    return $next_number;
}

$perusahaanQuery = "SELECT id, nama FROM perusahaan";
$perusahaanResult = $koneksi->query($perusahaanQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_karyawan = generateKodeKaryawan($koneksi); 
    $nama = htmlspecialchars(trim($_POST['nama']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));
    $jabatan = htmlspecialchars(trim($_POST['jabatan']));
    $no_telp = htmlspecialchars(trim($_POST['no_telp']));
    $email = htmlspecialchars(trim($_POST['email']));
    $no_rekening = htmlspecialchars(trim($_POST['no_rekening']));
    $rek_bank = htmlspecialchars(trim($_POST['rek_bank']));
    $id = htmlspecialchars(trim($_POST['id']));

    $sql = "INSERT INTO karyawan (kode_karyawan, nama, alamat, jabatan, no_telp, email, no_rekening, rek_bank, id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("isssssssi", $kode_karyawan, $nama, $alamat, $jabatan, $no_telp, $email, $no_rekening, $rek_bank, $id);
        if ($stmt->execute()) {
            header("Location: karyawan.php");
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
                    <h2 class="mb-3">Tambah Karyawan</h2>
                    <form action="karyawan-tambah.php" method="POST">
                        <div class="form-group">
                            <label for="kode_karyawan">Kode Karyawan</label>
                            <input type="text" class="form-control" id="kode_karyawan" name="kode_karyawan" value="<?= generateKodeKaryawan($koneksi); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
                        </div>
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="no_rekening">No Rekening</label>
                            <input type="text" class="form-control" id="no_rekening" name="no_rekening" required>
                        </div>
                        <div class="form-group">
                            <label for="rek_bank">Rekening Bank</label>
                            <input type="text" class="form-control" id="rek_bank" name="rek_bank" required>
                        </div>
                        <div class="form-group">
                            <label for="id">Perusahaan</label>
                            <select class="form-control" id="id" name="id" required>
                                <option value="">Pilih Perusahaan</option>
                                <?php while ($perusahaan = $perusahaanResult->fetch_assoc()) : ?>
                                    <option value="<?= $perusahaan['id']; ?>"><?= htmlspecialchars($perusahaan['nama']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="karyawan.php" class="btn btn-danger">Batal</a>
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