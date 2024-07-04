<?php
include('../config/database.php');

// Memastikan form disubmit dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $nama_pasien = $_POST['nama_pasien'];
    $alamat_pasien = $_POST['alamat_pasien'];
    $no_hp_pasien = $_POST['no_hp_pasien'];

    // Query untuk menyimpan data ke dalam tabel 'pasien'
    $sql = "INSERT INTO pasien (nama, alamat, no_hp) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nama_pasien, $alamat_pasien, $no_hp_pasien);

    // Eksekusi query
    if ($stmt->execute()) {
        header("Location: ../pages/data_pasien.php?status=sukses");
    } else {
        header("Location: ../pages/data_pasien.php?status=gagal&error=" . urlencode("Gagal menyimpan data pasien"));
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    // Jika tidak disubmit dengan metode POST, kembalikan ke halaman sebelumnya
    header("Location: ../pages/data_pasien.php");
}
?>
