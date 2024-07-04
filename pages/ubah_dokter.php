<?php
include('../includes/header.php'); // Pastikan path dan nama file header.php yang benar
include('../config/database.php');

// Pastikan parameter id_dokter tersedia dan valid
if (isset($_GET['id_dokter']) && is_numeric($_GET['id_dokter'])) {
    $id_dokter = $_GET['id_dokter'];

    // Query ambil data dokter
    $sql = "SELECT * FROM dokter WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_dokter);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="data-form">
                        <h3>Ubah Data Dokter</h3>
                        <form action="../actions/update_dokter.php" method="POST">
                            <input type="hidden" name="id_dokter" value="<?php echo $row['id']; ?>">
                            <div class="form-group">
                                <label for="nama">Nama Dokter:</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $row['nama']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat:</label>
                                <textarea class="form-control" id="alamat" name="alamat" required><?php echo $row['alamat']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="no_hp">No. HP:</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo $row['no_hp']; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php
    } else {
        echo "<div class='alert alert-danger'>Data dokter tidak ditemukan.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Parameter id_dokter tidak valid.</div>";
}

include('../includes/footer.php'); // Pastikan path dan nama file footer.php yang benar
$conn->close();
?>