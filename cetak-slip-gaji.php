<?php
// Memanggil file koneksi
require 'koneksi.php';

// Cek apakah parameter no_ref ada di URL
if (isset($_GET['no_ref'])) {
    $no_ref = $_GET['no_ref'];

    // Query untuk mendapatkan data slip gaji
    $sql = "SELECT sg.no_ref, sg.tgl, sg.total_gaji, sg.kode_karyawan, k.nama 
            FROM slip_gaji sg
            JOIN karyawan k ON sg.kode_karyawan = k.kode_karyawan
            WHERE sg.no_ref = ?";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("s", $no_ref);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $slip_gaji = $result->fetch_assoc();
        } else {
            die("Slip gaji tidak ditemukan.");
        }

        // Tutup statement
        $stmt->close();
    } else {
        die("Error: " . $koneksi->error);
    }

    // Query untuk mendapatkan detail gaji
    $sqlDetail = "SELECT dg.no, dg.nominal, kg.keterangan 
                  FROM detail_gaji dg
                  JOIN keterangan_gaji kg ON dg.no = kg.no
                  WHERE dg.no_ref = ?";
    if ($stmtDetail = $koneksi->prepare($sqlDetail)) {
        $stmtDetail->bind_param("s", $no_ref);
        $stmtDetail->execute();
        $detailResult = $stmtDetail->get_result();
        $detail_gaji = $detailResult->fetch_all(MYSQLI_ASSOC);
        $stmtDetail->close();
    } else {
        die("Error: " . $koneksi->error);
    }
} else {
    die("Error: No. Ref tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .slip-gaji {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 16px;
        }
        .detail {
            margin-bottom: 20px;
        }
        .detail table {
            width: 100%;
            border-collapse: collapse;
        }
        .detail table th, .detail table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="slip-gaji">
        <div class="header">
            <h1>SLIP GAJI</h1>
            <p>Periode: <?= htmlspecialchars($slip_gaji['tgl']); ?></p>
        </div>
        <div class="detail">
            <table>
                <tr>
                    <th>No. Ref</th>
                    <td><?= htmlspecialchars($slip_gaji['no_ref']); ?></td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td><?= htmlspecialchars($slip_gaji['nama']); ?></td>
                </tr>
                <tr>
                    <th>Kode Karyawan</th>
                    <td><?= htmlspecialchars($slip_gaji['kode_karyawan']); ?></td>
                </tr>
            </table>
        </div>
        <div class="detail">
        <div class="footer">
            <p>Total Gaji: <?= htmlspecialchars($slip_gaji['total_gaji']); ?></p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>