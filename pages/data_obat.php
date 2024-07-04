<?php
include('../config/database.php');
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="data-form">
                <h3>Input Obat Baru</h3>
                <form action="../actions/simpan_obat.php" method="POST">
                    <div class="form-group">
                        <label for="nama_obat">Nama Obat:</label>
                        <input type="text" class="form-control" id="nama_obat" name="nama_obat" required>
                    </div>

                    <div class="form-group">
                        <label for="kemasan_obat">Kemasan:</label>
                        <input type="text" class="form-control" id="kemasan_obat" name="kemasan" required>
                    </div>

                    <div class="form-group">
                        <label for="harga_obat">Harga:</label>
                        <input type="text" class="form-control" id="harga_obat" name="harga" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>

        <div class="col-md-6">
    <div class="data-list">
        <h3>Daftar Obat</h3>
        <table class="table">
            <thead class="table">
                <tr>
                    <th>Nama Obat</th>
                    <th>Kemasan</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query ambil data obat
                $sql = "SELECT * FROM obat";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['nama_obat'] . "</td>";
                        echo "<td>" . $row['kemasan'] . "</td>";
                        echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                        echo "<td>
                                <div class='d-flex flex-column'>
                                    <a href='ubah_obat.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary mb-1'>Ubah</a>
                                    <a href='../actions/hapus_obat.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Anda yakin ingin menghapus data ini?\")'>Hapus</a>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Belum ada data obat.</td></tr>";
                }

                // Tutup koneksi
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>


    </div>
</div>

<?php include('../includes/footer.php'); // Pastikan path dan nama file footer.php yang benar ?>
