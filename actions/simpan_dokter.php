<?php
// Pastikan request datang dari method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah variabel $_POST ada dan tidak kosong
    if (isset($_POST['nama']) && isset($_POST['alamat']) && isset($_POST['no_hp'])) {
        // Ambil data dari form
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_hp = $_POST['no_hp'];

        // Validasi data (jika diperlukan)
        // Misalnya, validasi bisa dilakukan dengan memeriksa apakah semua field terisi dengan benar.

        // Koneksi ke database
        include('../config/database.php');

        // Query untuk menyimpan data dokter ke dalam tabel 'dokter'
        $sql = "INSERT INTO dokter (nama, alamat, no_hp) VALUES (?, ?, ?)";
        
        // Persiapkan statement SQL
        $stmt = $conn->prepare($sql);

        // Bind parameter ke statement SQL
        $stmt->bind_param("sss", $nama, $alamat, $no_hp);

        // Eksekusi statement
        if ($stmt->execute()) {
            // Jika penyimpanan sukses, redirect kembali ke halaman data dokter dengan pesan sukses
            header("Location: ../pages/data_dokter.php?status=sukses");
            exit();
        } else {
            // Jika terjadi error, redirect kembali ke halaman data dokter dengan pesan error
            header("Location: ../pages/data_dokter.php?status=gagal&error=" . urlencode($stmt->error));
            exit();
        }

        // Tutup statement dan koneksi
        $stmt->close();
        $conn->close();
    } else {
        // Jika ada variabel yang tidak terdefinisi atau kosong, redirect ke halaman yang sesuai
        header("Location: ../pages/data_dokter.php?status=gagal&error=Formulir tidak lengkap");
        exit();
    }
} else {
    // Jika bukan dari method POST, redirect ke halaman yang sesuai
    header("Location: ../pages/data_dokter.php");
    exit();
}
?>
