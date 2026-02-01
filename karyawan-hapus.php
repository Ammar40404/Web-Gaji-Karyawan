<?php
require 'koneksi.php';

if (isset($_GET['kode_karyawan'])) {
    $kode_karyawan = $_GET['kode_karyawan'];

    // hapus data karyawan 
    $sql = "DELETE FROM karyawan WHERE kode_karyawan = ?";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("s", $kode_karyawan);

  
        if ($stmt->execute()) {
            header("Location: karyawan.php");
            exit();
        } else {
            die("Error: Gagal menghapus data karyawan. " . $stmt->error);
        }

       
        $stmt->close();
    } else {
        die("Error: " . $koneksi->error);
    }
} else {
    die("Error: Kode Karyawan tidak ditemukan.");
}


$koneksi->close();
?>