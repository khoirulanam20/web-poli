<?php
include('../includes/header.php'); // Pastikan path dan nama file header.php yang benar

// Koneksi database
include('../config/database.php');

// Pastikan parameter id_periksa tersedia dari URL
if (isset($_GET['id_periksa'])) {
    $id_periksa = $_GET['id_periksa'];

    // Query ambil data periksa pasien berdasarkan id_periksa
    $sql = "SELECT periksa.id, periksa.tgl_periksa, periksa.catatan,
                    periksa.id_pasien, periksa.id_dokter,
                    pasien.nama AS nama_pasien, dokter.nama AS nama_dokter
            FROM periksa
            INNER JOIN pasien ON periksa.id_pasien = pasien.id
            INNER JOIN dokter ON periksa.id_dokter = dokter.id
            WHERE periksa.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_periksa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">Ubah Periksa</h2>
                            <form action="../actions/update_periksa_admin.php" method="POST">
                                <input type="hidden" name="id_periksa" value="<?php echo $row['id']; ?>">
                                <div class="form-group">
                                    <label for="tgl_periksa">Tanggal Periksa:</label>
                                    <input type="date" id="tgl_periksa" name="tgl_periksa" class="form-control" value="<?php echo $row['tgl_periksa']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan:</label>
                                    <textarea id="catatan" name="catatan" class="form-control" required><?php echo $row['catatan']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="id_pasien">Nama Pasien:</label>
                                    <select id="id_pasien" name="id_pasien" class="form-control" required>
                                        <option value="<?php echo $row['id_pasien']; ?>"><?php echo $row['nama_pasien']; ?></option>
                                        <!-- Opsi pasien lainnya bisa ditambahkan dengan PHP -->
                                        <?php
                                        $sql_pasien = "SELECT * FROM pasien";
                                        $result_pasien = $conn->query($sql_pasien);
                                        if ($result_pasien->num_rows > 0) {
                                            while ($row_pasien = $result_pasien->fetch_assoc()) {
                                                if ($row_pasien['id'] != $row['id_pasien']) {
                                                    echo "<option value='" . $row_pasien['id'] . "'>" . $row_pasien['nama'] . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_dokter">Nama Dokter:</label>
                                    <select id="id_dokter" name="id_dokter" class="form-control" required>
                                        <option value="<?php echo $row['id_dokter']; ?>"><?php echo $row['nama_dokter']; ?></option>
                                        <!-- Opsi dokter lainnya bisa ditambahkan dengan PHP -->
                                        <?php
                                        $sql_dokter = "SELECT * FROM dokter";
                                        $result_dokter = $conn->query($sql_dokter);
                                        if ($result_dokter->num_rows > 0) {
                                            while ($row_dokter = $result_dokter->fetch_assoc()) {
                                                if ($row_dokter['id'] != $row['id_dokter']) {
                                                    echo "<option value='" . $row_dokter['id'] . "'>" . $row_dokter['nama'] . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="../pages/periksa.php" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    } else {
        echo "Data periksa tidak ditemukan.";
    }
} else {
    echo "Parameter id_periksa tidak valid.";
}

include('../includes/footer.php'); // Pastikan path dan nama file footer.php yang benar
?>
