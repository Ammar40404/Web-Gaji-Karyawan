<?php
require 'koneksi.php';

if (isset($_GET['no_ref'])) {
    $no_ref = $_GET['no_ref'];

    // Hapus Dtaa
    $sql = "DELETE FROM slip_gaji WHERE no_ref = ?";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("s", $no_ref);

        if ($stmt->execute()) {
            header("Location: penggajian.php");
            exit();
        } else {
            die("Error: Gagal menghapus data penggajian. " . $stmt->error);
        }

        $stmt->close();
    } else {
        die("Error: " . $koneksi->error);
    }
} else {
    die("Error: No. Ref tidak ditemukan.");
}

$koneksi->close();
?>