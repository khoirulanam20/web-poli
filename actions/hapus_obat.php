<?php
session_start(); // Mulai sesi untuk menyimpan pesan status

// Include konfigurasi database
include('../config/database.php');

// Jika parameter id ada dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_obat = $_GET['id'];

    // Hapus obat dari tabel obat
    $sql_delete_obat = "DELETE FROM obat WHERE id = ?";
    $stmt_obat = $conn->prepare($sql_delete_obat);
    $stmt_obat->bind_param("i", $id_obat);

    if ($stmt_obat->execute()) {
        $_SESSION['status'] = "Data obat berhasil dihapus.";
    } else {
        $_SESSION['status'] = "Terjadi kesalahan saat menghapus data obat: " . $stmt_obat->error;
    }

    // Redirect kembali ke halaman dashboard
    header("Location: ../pages/dashboard.php");
    exit();
} else {
    // Jika parameter tidak valid, redirect ke halaman utama atau halaman error
    $_SESSION['status'] = "ID obat tidak valid.";
    header("Location: ../index.php");
    exit();
}

// Tutup koneksi database
$conn->close();
?>
