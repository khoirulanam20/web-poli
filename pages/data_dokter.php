<?php
session_start();
include('../config/database.php');

// Variabel untuk menyimpan pesan status dari aksi simpan
$status = '';

// Jika form simpan dokter disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_dokter = $_POST['nama'];
    $alamat_dokter = $_POST['alamat'];
    $no_hp_dokter = $_POST['no_hp'];

    // Insert data dokter baru
    $sql_insert_dokter = "INSERT INTO dokter (nama, alamat, no_hp) VALUES (?, ?, ?)";
    $stmt_insert_dokter = $conn->prepare($sql_insert_dokter);
    $stmt_insert_dokter->bind_param("sss", $nama_dokter, $alamat_dokter, $no_hp_dokter);

    if ($stmt_insert_dokter->execute()) {
        $status = "Data dokter berhasil disimpan.";
    } else {
        $status = "Terjadi kesalahan: " . $stmt_insert_dokter->error;
    }

    // Set session status dan redirect ke dashboard
    $_SESSION['status'] = $status;
    header("Location: ../pages/dashboard.php#");
    exit;
}

// Query ambil data dokter
$sql_dokter = "SELECT * FROM dokter";
$result_dokter = $conn->query($sql_dokter);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dokter</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">

            <div class="data-form">
                <h3>Input Dokter Baru</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <label for="nama">Nama Dokter:</label>
                        <input type="text" class="form-control" id="nama_dokter" name="nama" required>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <input type="text" class="form-control" id="alamat_dokter" name="alamat" required>
                    </div>

                    <div class="form-group">
                        <label for="no_hp">No. HP:</label>
                        <input type="text" class="form-control" id="no_hp_dokter" name="no_hp" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="data-list">
                <h3>Daftar Dokter</h3>
                <table class="table">
                    <thead class="table">
                        <tr>
                            <th>Nama Dokter</th>
                            <th>Alamat</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_dokter->num_rows > 0) {
                            while ($row = $result_dokter->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['nama'] . "</td>";
                                echo "<td>" . $row['alamat'] . "</td>";
                                echo "<td>" . $row['no_hp'] . "</td>";
                                echo "<td>
                                        <div class='d-flex flex-column'>
                                            <a href='ubah_dokter.php?id_dokter=" . $row['id'] . "' class='btn btn-sm btn-primary'>Ubah</a>
                                            <a href='../actions/hapus_dokter.php?id_dokter=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Anda yakin ingin menghapus data ini?\")'>Hapus</a>
                                        </div>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Belum ada data dokter.</td></tr>";
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
