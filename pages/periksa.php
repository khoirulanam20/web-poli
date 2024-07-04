<?php
include('../includes/header.php'); // Pastikan path dan nama file header.php yang benar

// Koneksi database
include('../config/database.php');

// Variabel untuk menyimpan pesan status dari aksi simpan
$status = '';

// Jika form periksa disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_pasien = $_POST['nama_pasien'];
    $no_hp_pasien = $_POST['no_hp_pasien'];
    $alamat_pasien = $_POST['alamat_pasien'];
    $id_dokter = $_POST['pilih_dokter'];
    $tanggal_periksa = $_POST['tanggal_periksa'];
    $catatan = $_POST['catatan'];
    $obat = $_POST['obat']; // Array obat karena multiple select

    // Lakukan validasi atau proses penyimpanan sesuai kebutuhan

    // Simpan data pasien jika belum ada
    $sql_check_pasien = "SELECT * FROM pasien WHERE nama = ?";
    $stmt_check_pasien = $conn->prepare($sql_check_pasien);
    $stmt_check_pasien->bind_param("s", $nama_pasien);
    $stmt_check_pasien->execute();
    $result_check_pasien = $stmt_check_pasien->get_result();

    if ($result_check_pasien->num_rows == 0) {
        // Insert data pasien baru
        $sql_insert_pasien = "INSERT INTO pasien (nama, no_hp, alamat) VALUES (?, ?, ?)";
        $stmt_insert_pasien = $conn->prepare($sql_insert_pasien);
        $stmt_insert_pasien->bind_param("sss", $nama_pasien, $no_hp_pasien, $alamat_pasien);
        $stmt_insert_pasien->execute();

        // Ambil ID pasien yang baru saja di-insert
        $id_pasien = $conn->insert_id;
    } else {
        // Ambil ID pasien yang sudah ada
        $row_pasien = $result_check_pasien->fetch_assoc();
        $id_pasien = $row_pasien['id'];
    }

    // Contoh: Simpan data periksa ke dalam tabel 'periksa'
    $sql_insert_periksa = "INSERT INTO periksa (id_pasien, id_dokter, tgl_periksa, catatan) 
                           VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql_insert_periksa);
    $stmt->bind_param("iiss", $id_pasien, $id_dokter, $tanggal_periksa, $catatan);

    if ($stmt->execute()) {
        $status = "Data periksa berhasil disimpan.";
    } else {
        $status = "Terjadi kesalahan: " . $stmt->error;
    }

    // Simpan detail obat yang diresepkan ke dalam tabel 'detail_periksa'
    if (!empty($obat)) {
        $id_periksa = $conn->insert_id; // Ambil ID periksa yang baru saja di-insert
        foreach ($obat as $id_obat) {
            $sql_insert_detail = "INSERT INTO detail_periksa (id_periksa, id_obat) VALUES (?, ?)";
            $stmt_detail = $conn->prepare($sql_insert_detail);
            $stmt_detail->bind_param("ii", $id_periksa, $id_obat);
            $stmt_detail->execute();
        }
    }
}

// Query ambil data periksa
$sql_periksa = "SELECT periksa.id, periksa.id_pasien, periksa.id_dokter, periksa.tgl_periksa, periksa.catatan,
                pasien.nama AS nama_pasien, pasien.alamat AS alamat_pasien, pasien.no_hp AS no_hp_pasien,
                dokter.nama AS nama_dokter, dokter.alamat AS alamat_dokter, dokter.no_hp AS no_hp_dokter
                FROM periksa
                INNER JOIN pasien ON periksa.id_pasien = pasien.id
                INNER JOIN dokter ON periksa.id_dokter = dokter.id";
$result_periksa = $conn->query($sql_periksa);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periksa Pasien</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <h2>Periksa Pasien</h2>
            
            <?php if (!empty($status)) : ?>
                <div class="alert alert-success"><?php echo $status; ?></div>
            <?php endif; ?>

            <div class="periksa-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <label for="nama_pasien">Nama Pasien:</label>
                        <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" required>
                    </div>

                    <div class="form-group">
                        <label for="no_hp_pasien">No. HP Pasien:</label>
                        <input type="text" class="form-control" id="no_hp_pasien" name="no_hp_pasien" required>
                    </div>

                    <div class="form-group">
                        <label for="alamat_pasien">Alamat Pasien:</label>
                        <textarea class="form-control" id="alamat_pasien" name="alamat_pasien" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="pilih_dokter">Pilih Dokter:</label>
                        <select class="form-control" id="pilih_dokter" name="pilih_dokter" required>
                            <?php
                            // Query ambil data dokter
                            $sql_dokter = "SELECT * FROM dokter";
                            $result_dokter = $conn->query($sql_dokter);

                            if ($result_dokter->num_rows > 0) {
                                while ($row = $result_dokter->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['nama'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Tidak ada dokter tersedia</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_periksa">Tanggal Periksa:</label>
                        <input type="date" class="form-control" id="tanggal_periksa" name="tanggal_periksa" required>
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan:</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="obat">Pilih Obat:</label>
                        <select class="form-control" id="obat" name="obat[]" multiple required>
                            <?php
                            // Query untuk mengambil data obat dari database
                            $sql_obat = "SELECT * FROM obat";
                            $result_obat = $conn->query($sql_obat);

                            // Memeriksa apakah ada data obat yang ditemukan
                            if ($result_obat->num_rows > 0) {
                                // Menampilkan pilihan obat dalam dropdown
                                while ($row = $result_obat->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['nama_obat'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Tidak ada obat tersedia</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="periksa-list">
                <h3>Daftar Periksa Pasien</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Pasien</th>
                            <th>Nama Dokter</th>
                            <th>Tanggal Periksa</th>
                            <th>Catatan</th>
                            <th>Obat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_periksa->num_rows > 0) {
                            while ($row = $result_periksa->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['nama_pasien'] . "</td>";
                                echo "<td>" . $row['nama_dokter'] . "</td>";
                                echo "<td>" . $row['tgl_periksa'] . "</td>";
                                echo "<td>" . $row['catatan'] . "</td>";

                                // Ambil data obat yang diresepkan untuk periksa ini
                                $sql_obat_periksa = "SELECT obat.nama_obat FROM detail_periksa 
                                                    INNER JOIN obat ON detail_periksa.id_obat = obat.id
                                                    WHERE detail_periksa.id_periksa = " . $row['id'];
                                $result_obat_periksa = $conn->query($sql_obat_periksa);

                                echo "<td>";
                                if ($result_obat_periksa->num_rows > 0) {
                                    while ($row_obat = $result_obat_periksa->fetch_assoc()) {
                                        echo $row_obat['nama_obat'] . "<br>";
                                    }
                                } else {
                                    echo "-";
                                }
                                echo "</td>";

                                // Aksi: Ubah, Hapus, dan Nota (disesuaikan dengan kebutuhan Anda)
                                echo "<td>
                                        <div class='d-block'>
                                            <a href='../actions/hapus_admin.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm mb-1' onclick='return confirm(\"Anda yakin ingin menghapus data ini?\")'>Hapus</a>
                                            <a href='ubah_periksa_admin.php?id_periksa=" . $row['id'] . "' class='btn btn-warning btn-sm mb-1'>Ubah</a>
                                            <a href='../pages/nota.php?id_periksa=" . $row['id'] . "' class='btn btn-primary btn-sm'>Nota</a>
                                        </div>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Belum ada data periksa pasien.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php include('../includes/footer.php'); // Pastikan path dan nama file footer.php yang benar ?>
