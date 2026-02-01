<?php
include 'koneksi.php';

$karyawanQuery = "SELECT kode_karyawan, nama FROM karyawan";
$karyawanResult = $koneksi->query($karyawanQuery);


$keteranganQuery = "SELECT no, keterangan FROM keterangan_gaji";
$keteranganResult = $koneksi->query($keteranganQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_ref = htmlspecialchars(trim($_POST['no_ref']));
    $tgl = htmlspecialchars(trim($_POST['tgl']));
    $total_gaji = htmlspecialchars(trim($_POST['total_gaji']));
    $kode_karyawan = htmlspecialchars(trim($_POST['kode_karyawan']));
    $detail_gaji = $_POST['detail_gaji']; // Array dari detail gaji

    // Insert data ke tabel slip_gaji
    $sql = "INSERT INTO slip_gaji (no_ref, tgl, total_gaji, kode_karyawan) VALUES (?, ?, ?, ?)";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("ssss", $no_ref, $tgl, $total_gaji, $kode_karyawan);
        if ($stmt->execute()) {
            // Insert data ke tabel detail_gaji
            foreach ($detail_gaji as $detail) {
                $no = $detail['no'];
                $nominal = $detail['nominal'];
                $sqlDetail = "INSERT INTO detail_gaji (no, no_ref, nominal) VALUES (?, ?, ?)";
                if ($stmtDetail = $koneksi->prepare($sqlDetail)) {
                    $stmtDetail->bind_param("iss", $no, $no_ref, $nominal);
                    $stmtDetail->execute();
                    $stmtDetail->close();
                }
            }
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
                    <h2 class="mb-3">Tambah Penggajian</h2>
                    <form action="penggajian-tambah.php" method="POST">
                        <div class="form-group">
                            <label for="no_ref">No. Ref</label>
                            <input type="text" class="form-control" id="no_ref" name="no_ref" required>
                        </div>
                        <div class="form-group">
                            <label for="tgl">Tanggal</label>
                            <input type="date" class="form-control" id="tgl" name="tgl" required>
                        </div>
                        <div class="form-group">
                            <label for="total_gaji">Total Gaji</label>
                            <input type="text" class="form-control" id="total_gaji" name="total_gaji" required>
                        </div>
                        <div class="form-group">
                            <label for="kode_karyawan">Karyawan</label>
                            <select class="form-control" id="kode_karyawan" name="kode_karyawan" required>
                                <option value="">Pilih Karyawan</option>
                                <?php while ($karyawan = $karyawanResult->fetch_assoc()) : ?>
                                    <option value="<?= $karyawan['kode_karyawan']; ?>"><?= htmlspecialchars($karyawan['nama']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="penggajian.php" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
                <?php include 'layout-footer.php'; ?>
            </div>
        </div>
    </div>

    <script>
        let detailCounter = 1;

        function tambahDetailGaji() {
            const container = document.getElementById('detail-gaji-container');
            const newItem = document.createElement('div');
            newItem.classList.add('detail-gaji-item', 'mb-3');
            newItem.innerHTML = `
                <select class="form-control" name="detail_gaji[${detailCounter}][no]" required>
                    <option value="">Pilih Keterangan</option>
                    <?php
                    $keteranganResult->data_seek(0); // Reset pointer result
                    while ($keterangan = $keteranganResult->fetch_assoc()) : ?>
                        <option value="<?= $keterangan['no']; ?>"><?= htmlspecialchars($keterangan['keterangan']); ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="text" class="form-control mt-2" name="detail_gaji[${detailCounter}][nominal]" placeholder="Nominal" required>
            `;
            container.appendChild(newItem);
            detailCounter++;
        }
    </script>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>