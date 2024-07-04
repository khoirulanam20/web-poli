<?php
include('../config/database.php'); // Pastikan path dan nama file database.php yang benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari form
    $id_periksa = $_POST['id_periksa'];
    $tgl_periksa = $_POST['tgl_periksa'];
    $catatan = $_POST['catatan'];
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];

    // Query untuk update data periksa
    $sql = "UPDATE periksa SET tgl_periksa=?, catatan=?, id_pasien=?, id_dokter=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $tgl_periksa, $catatan, $id_pasien, $id_dokter, $id_periksa);

    if ($stmt->execute()) {
        // Jika update berhasil, redirect kembali ke halaman daftar periksa
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        // Jika terjadi kesalahan dalam query
        echo "Error: " . $conn->error;
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi database
$conn->close();
?>
