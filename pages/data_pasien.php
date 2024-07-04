<?php include('../includes/header.php'); ?>

<h2>Data Pasien</h2>

<div class="data-form">
    <h3>Input Pasien Baru</h3>
    <form action="../actions/simpan_pasien.php" method="POST">
        <label for="nama_pasien">Nama Pasien:</label>
        <input type="text" id="nama_pasien" name="nama_pasien" required>

        <label for="alamat_pasien">Alamat:</label>
        <input type="text" id="alamat_pasien" name="alamat_pasien" required>

        <label for="no_hp_pasien">No. HP:</label>
        <input type="text" id="no_hp_pasien" name="no_hp_pasien" required>

        <button type="submit">Simpan</button>
    </form>
</div>

<div class="data-list">
    <h3>Daftar Pasien</h3>
    <table>
        <tr>
            <th>Nama Pasien</th>
            <th>Alamat</th>
            <th>No. HP</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Koneksi database
        include('../config/database.php');

        // Query ambil data pasien
        $sql = "SELECT * FROM pasien";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . $row['alamat'] . "</td>";
                echo "<td>" . $row['no_hp'] . "</td>";
                echo "<td><a href='../actions/ubah_pasien.php?id=" . $row['id'] . "'>Ubah</a> | <a href='../actions/hapus_pasien.php?id=" . $row['id'] . "' onclick='return confirm(\"Anda yakin ingin menghapus data ini?\")'>Hapus</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Belum ada data pasien.</td></tr>";
        }

        // Tutup koneksi
        $conn->close();
        ?>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
