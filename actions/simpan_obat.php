<?php
session_start();
include('../config/database.php');

// Variabel untuk menyimpan pesan status dari aksi simpan
$status = '';

// Jika form simpan obat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_obat = $_POST['nama_obat'];
    $kemasan = $_POST['kemasan'];
    $harga = $_POST['harga'];

    // Insert data obat baru
    $sql_insert_obat = "INSERT INTO obat (nama_obat, kemasan, harga) VALUES (?, ?, ?)";
    $stmt_insert_obat = $conn->prepare($sql_insert_obat);
    $stmt_insert_obat->bind_param("ssi", $nama_obat, $kemasan, $harga);

    if ($stmt_insert_obat->execute()) {
        $status = "Data obat berhasil disimpan.";
    } else {
        $status = "Terjadi kesalahan: " . $stmt_insert_obat->error;
    }

    // Set session status dan redirect ke dashboard
    $_SESSION['status'] = $status;
    header("Location: ../pages/dashboard.php");
    exit;
}
?>
