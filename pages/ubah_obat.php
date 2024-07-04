<?php
include('../includes/header.php'); // Pastikan path dan nama file header.php yang benar
include('../config/database.php');

// Pastikan parameter id tersedia dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_obat = $_GET['id'];

    // Query ambil data obat
    $sql = "SELECT * FROM obat WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_obat);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="data-form">
                        <h3>Ubah Data Obat</h3>
                        <form action="../actions/update_obat.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <div class="form-group">
                                <label for="nama_obat">Nama Obat:</label>
                                <input type="text" class="form-control" id="nama_obat" name="nama_obat" value="<?php echo $row['nama_obat']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="kemasan_obat">Kemasan:</label>
                                <input type="text" class="form-control" id="kemasan_obat" name="kemasan" value="<?php echo $row['kemasan']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="harga_obat">Harga:</label>
                                <input type="text" class="form-control" id="harga_obat" name="harga" value="<?php echo $row['harga']; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo "<div class='alert alert-danger'>Data obat tidak ditemukan.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Parameter id_obat tidak valid.</div>";
}

include('../includes/footer.php'); // Pastikan path dan nama file footer.php yang benar
$conn->close();
?>
