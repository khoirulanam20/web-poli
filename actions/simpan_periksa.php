<?php
// Pastikan request datang dari method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah variabel $_POST ada dan tidak kosong
    if (isset($_POST['nama_pasien']) && isset($_POST['pilih_dokter']) && isset($_POST['tanggal_periksa']) && isset($_POST['catatan']) && isset($_POST['obat'])) {
        // Ambil data dari form
        $nama_pasien = $_POST['nama_pasien'];
        $id_dokter = $_POST['pilih_dokter'];
        $tanggal_periksa = $_POST['tanggal_periksa'];
        $catatan = $_POST['catatan'];
        $obat = $_POST['obat']; // ini berupa array karena select multiple

        // Validasi data (jika diperlukan)
        // Misalnya, validasi bisa dilakukan dengan memeriksa apakah semua field terisi dengan benar.

        // Koneksi ke database
        include('../config/database.php');

        // Mulai transaksi
        $conn->begin_transaction();

        try {
            // Query untuk menyimpan data ke tabel 'periksa'
            $sql_periksa = "INSERT INTO periksa (id_pasien, id_dokter, tgl_periksa, catatan) VALUES (?, ?, ?, ?)";
            
            // Persiapkan statement SQL
            $stmt_periksa = $conn->prepare($sql_periksa);

            // Bind parameter ke statement SQL
            $stmt_periksa->bind_param("iiss", $nama_pasien, $id_dokter, $tanggal_periksa, $catatan);

            // Eksekusi statement
            $stmt_periksa->execute();

            // Ambil ID periksa yang baru saja di-generate
            $id_periksa = $stmt_periksa->insert_id;

            // Query untuk menyimpan obat yang diresepkan ke dalam tabel 'detail_periksa'
            $sql_detail = "INSERT INTO detail_periksa (id_periksa, id_obat) VALUES (?, ?)";
            
            // Persiapkan statement SQL
            $stmt_detail = $conn->prepare($sql_detail);

            // Bind parameter ke statement SQL
            $stmt_detail->bind_param("ii", $id_periksa, $id_obat);

            // Loop untuk menyimpan setiap obat yang dipilih
            foreach ($obat as $id_obat) {
                // Eksekusi statement untuk setiap obat
                $stmt_detail->execute();
            }

            // Commit transaksi jika berhasil
            $conn->commit();

            // Redirect kembali ke halaman periksa.php dengan status sukses
            header("Location: ../pages/periksa.php?status=sukses");
            exit();
        } catch (mysqli_sql_exception $e) {
            // Rollback transaksi jika terjadi error
            $conn->rollback();

            // Redirect kembali ke halaman periksa.php dengan status gagal dan pesan error
            header("Location: ../pages/periksa.php?status=gagal&error=" . urlencode($e->getMessage()));
            exit();
        }

        // Tutup statement dan koneksi
        $stmt_periksa->close();
        $stmt_detail->close();
        $conn->close();
    } else {
        // Jika ada variabel yang tidak terdefinisi atau kosong, redirect ke halaman yang sesuai
        header("Location: ../pages/periksa.php?status=gagal&error=Formulir tidak lengkap");
        exit();
    }
} else {
    // Jika bukan dari method POST, redirect ke halaman yang sesuai
    header("Location: ../pages/periksa.php");
    exit();
}
?>
