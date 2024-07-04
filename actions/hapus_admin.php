<?php
// Include konfigurasi database
include('../config/database.php');

// Jika parameter id ada dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_periksa = $_GET['id'];

    // Hapus detail_periksa terkait periksa
    $sql_delete_detail = "DELETE FROM detail_periksa WHERE id_periksa = ?";
    $stmt_detail = $conn->prepare($sql_delete_detail);
    $stmt_detail->bind_param("i", $id_periksa);
    $stmt_detail->execute();

    // Hapus periksa
    $sql_delete_periksa = "DELETE FROM periksa WHERE id = ?";
    $stmt_periksa = $conn->prepare($sql_delete_periksa);
    $stmt_periksa->bind_param("i", $id_periksa);
    $stmt_periksa->execute();

    // Ambil id_pasien terkait
    $sql_select_pasien = "SELECT id_pasien FROM periksa WHERE id = ?";
    $stmt_select_pasien = $conn->prepare($sql_select_pasien);
    $stmt_select_pasien->bind_param("i", $id_periksa);
    $stmt_select_pasien->execute();
    $result_select_pasien = $stmt_select_pasien->get_result();

    if ($result_select_pasien->num_rows > 0) {
        $row_pasien = $result_select_pasien->fetch_assoc();
        $id_pasien = $row_pasien['id_pasien'];

        // Hapus pasien jika tidak ada periksa yang terkait
        $sql_check_periksa = "SELECT COUNT(*) AS jumlah_periksa FROM periksa WHERE id_pasien = ?";
        $stmt_check_periksa = $conn->prepare($sql_check_periksa);
        $stmt_check_periksa->bind_param("i", $id_pasien);
        $stmt_check_periksa->execute();
        $result_check_periksa = $stmt_check_periksa->get_result();

        $row_check_periksa = $result_check_periksa->fetch_assoc();
        $jumlah_periksa = $row_check_periksa['jumlah_periksa'];

        if ($jumlah_periksa == 0) {
            // Hapus pasien jika tidak ada periksa yang terkait
            $sql_delete_pasien = "DELETE FROM pasien WHERE id = ?";
            $stmt_delete_pasien = $conn->prepare($sql_delete_pasien);
            $stmt_delete_pasien->bind_param("i", $id_pasien);
            $stmt_delete_pasien->execute();
        }
    }

    // Redirect kembali ke halaman sebelumnya
    header("Location: ../pages/dashboard.php");
    exit();
} else {
    // Jika parameter tidak valid, redirect ke halaman utama atau halaman error
    header("Location: ../index.php");
    exit();
}

// Tutup koneksi database
$conn->close();
?>
