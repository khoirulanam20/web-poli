<?php
include('../includes/header.php'); // Pastikan path dan nama file header.php yang benar

// Koneksi database
include('../config/database.php');

// Pastikan parameter id_periksa tersedia dari URL
if (isset($_GET['id_periksa'])) {
    $id_periksa = $_GET['id_periksa'];

    // Query ambil detail periksa pasien
    $sql_periksa = "SELECT periksa.id, periksa.tgl_periksa, periksa.catatan,
                    pasien.nama AS nama_pasien, pasien.alamat AS alamat_pasien, pasien.no_hp AS no_hp_pasien,
                    dokter.nama AS nama_dokter, dokter.alamat AS alamat_dokter, dokter.no_hp AS no_hp_dokter
                    FROM periksa
                    INNER JOIN pasien ON periksa.id_pasien = pasien.id
                    INNER JOIN dokter ON periksa.id_dokter = dokter.id
                    WHERE periksa.id = ?";
    
    $stmt_periksa = $conn->prepare($sql_periksa);
    $stmt_periksa->bind_param("i", $id_periksa);
    $stmt_periksa->execute();
    $result_periksa = $stmt_periksa->get_result();

    if ($result_periksa->num_rows > 0) {
        $row = $result_periksa->fetch_assoc();

        // Hitung total invoice
        $jasa_dokter = 150000; // Biaya jasa dokter default

        // Query ambil daftar obat yang diresepkan untuk periksa ini
        $sql_obat_periksa = "SELECT obat.nama_obat, obat.harga
                             FROM detail_periksa 
                             INNER JOIN obat ON detail_periksa.id_obat = obat.id
                             WHERE detail_periksa.id_periksa = ?";
        $stmt_obat_periksa = $conn->prepare($sql_obat_periksa);
        $stmt_obat_periksa->bind_param("i", $id_periksa);
        $stmt_obat_periksa->execute();
        $result_obat_periksa = $stmt_obat_periksa->get_result();

        ?>

        <div class="container">
            <h2 class="mt-4">Nota Periksa</h2>

            <table class="table table-bordered mt-3">
                <tr>
                    <td><strong>Nomor Periksa:</strong></td>
                    <td><?php echo $row['id']; ?></td>
                </tr>
                <tr>
                    <td><strong>Tanggal Periksa:</strong></td>
                    <td><?php echo date('d-m-Y', strtotime($row['tgl_periksa'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Pasien:</strong></td>
                    <td><?php echo $row['nama_pasien']; ?></td>
                </tr>
                <tr>
                    <td><strong>Alamat:</strong></td>
                    <td><?php echo $row['alamat_pasien']; ?></td>
                </tr>
                <tr>
                    <td><strong>No. HP:</strong></td>
                    <td><?php echo $row['no_hp_pasien']; ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Dokter:</strong></td>
                    <td><?php echo $row['nama_dokter']; ?></td>
                </tr>
                <tr>
                    <td><strong>Alamat Dokter:</strong></td>
                    <td><?php echo $row['alamat_dokter']; ?></td>
                </tr>
                <tr>
                    <td><strong>No. HP Dokter:</strong></td>
                    <td><?php echo $row['no_hp_dokter']; ?></td>
                </tr>
                <tr>
                    <td><strong>Jasa Dokter (Default: Rp 150.000):</strong></td>
                    <td>Rp <?php echo number_format($jasa_dokter, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td><strong>Daftar Obat (Harga):</strong></td>
                    <td>
                        <?php
                        $total_invoice = $jasa_dokter;
                        if ($result_obat_periksa->num_rows > 0) {
                            while ($row_obat = $result_obat_periksa->fetch_assoc()) {
                                echo $row_obat['nama_obat'] . " (Rp " . number_format($row_obat['harga'], 0, ',', '.') . ")<br>";
                                $total_invoice += $row_obat['harga'];
                            }
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Total Invoice:</strong></td>
                    <td>Rp <?php echo number_format($total_invoice, 0, ',', '.'); ?></td>
                </tr>
            </table>

            <a href="unduh_nota.php?id_periksa=<?php echo $id_periksa; ?>" class="btn btn-primary mt-3">Unduh PDF</a>
        </div>

    <?php
    } else {
        echo "<div class='alert alert-danger'>Data periksa tidak ditemukan.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Parameter id_periksa tidak valid.</div>";
}

include('../includes/footer.php'); // Pastikan path dan nama file footer.php yang benar
?>
