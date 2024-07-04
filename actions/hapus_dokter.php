<?php
session_start();
include('../config/database.php');

// Pastikan request method adalah GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Ambil id_dokter dari parameter URL
    $id_dokter = $_GET['id_dokter'];

    // Validasi jika id_dokter tidak ada atau kosong
    if (empty($id_dokter)) {
        $_SESSION['status'] = "ID dokter tidak valid.";
        header("Location: ../pages/dashboard.php");
        exit;
    }

    // Query hapus data dokter berdasarkan id_dokter
    $sql_delete_dokter = "DELETE FROM dokter WHERE id = ?";
    $stmt_delete_dokter = $conn->prepare($sql_delete_dokter);
    $stmt_delete_dokter->bind_param("i", $id_dokter);

    if ($stmt_delete_dokter->execute()) {
        $_SESSION['status'] = "Data dokter berhasil dihapus.";
    } else {
        $_SESSION['status'] = "Terjadi kesalahan saat menghapus data dokter: " . $stmt_delete_dokter->error;
    }

    // Redirect kembali ke halaman dashboard.php
    header("Location: ../pages/dashboard.php");
    exit;
} else {
    // Jika bukan metode GET, redirect kembali ke halaman dashboard.php
    $_SESSION['status'] = "Akses tidak valid untuk halaman ini.";
    header("Location: ../pages/dashboard.php");
    exit;
}

$conn->close(); // Tutup koneksi database
?>
