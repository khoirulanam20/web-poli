<?php
include('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id']) &&
        isset($_POST['nama_obat']) && isset($_POST['kemasan']) && isset($_POST['harga'])) {

        $id_obat = $_POST['id'];
        $nama_obat = $_POST['nama_obat'];
        $kemasan = $_POST['kemasan'];
        $harga = $_POST['harga'];

        // Query update data obat
        $sql = "UPDATE obat SET nama_obat = ?, kemasan = ?, harga = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $nama_obat, $kemasan, $harga, $id_obat);

        if ($stmt->execute()) {
            header("Location: ../pages/data_obat.php?id_obat=$id_obat&update_success=true");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Parameter tidak valid.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
