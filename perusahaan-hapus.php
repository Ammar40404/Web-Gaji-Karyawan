<?php
include 'koneksi.php';

if (!isset($koneksi)) {
    die("Koneksi database tidak ditemukan. Periksa file koneksi.php.");
}

if (isset($_GET['id'])) {
    $id_perusahaan = $_GET['id'];

    $query = "DELETE FROM perusahaan WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_perusahaan);

    if ($stmt->execute()) {
        header("Location: perusahaan.php?status=deleted");
        exit();
    } else {
        die("Gagal menghapus data: " . $stmt->error);
    }
} else {
    header("Location: perusahaan.php");
    exit();
}


$koneksi->close();
?>
