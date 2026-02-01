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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Karyawan</title>
    <!-- Bootstrap CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                font-size: 12px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
        }
    </style>
</head>

<body onload="window.print()"> <!-- Langsung mencetak saat halaman dimuat -->
    <div class="container mt-4">
        <h2 class="text-center mb-4">Daftar Karyawan</h2>
        <div class="text-right mb-3 no-print">
            <a href="karyawan.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
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
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$koneksi->close();
?>