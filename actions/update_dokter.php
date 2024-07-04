<?php
include('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_dokter']) && is_numeric($_POST['id_dokter']) &&
        isset($_POST['nama']) && isset($_POST['alamat']) && isset($_POST['no_hp'])) {

        $id_dokter = $_POST['id_dokter'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_hp = $_POST['no_hp'];

        // Query untuk update data dokter
        $sql = "UPDATE dokter SET nama = ?, alamat = ?, no_hp = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nama, $alamat, $no_hp, $id_dokter);

        if ($stmt->execute()) {
            header("Location: ../pages/data_dokter.php?update_success=true");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Parameter tidak valid.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
