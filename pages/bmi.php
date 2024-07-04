<?php include('../includes/header.php'); ?> <!-- Include header jika diperlukan -->

<body>
    <div class="container">
        <h2 class="text-center">Hitung BMI (Body Mass Index)</h2>
        <div class="mt-3">
            <p>BMI (Body Mass Index) adalah sebuah indeks yang menggambarkan proporsi berat badan seseorang terhadap tinggi badan. Rumusnya adalah berat badan (kg) dibagi dengan kuadrat tinggi badan (meter).</p>
            <p>BMI memberikan gambaran tentang apakah seseorang berada di bawah berat badan normal, berat badan normal, berlebih, atau mengalami obesitas.</p>
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> <!-- Form untuk mengirim data ke halaman ini sendiri -->
            <div class="form-group">
                <label for="berat_badan">Berat Badan (kg):</label>
                <input type="text" class="form-control" id="berat_badan" name="berat_badan" required>
                <!-- Input untuk masukkan berat badan -->
            </div>
            <div class="form-group">
                <label for="tinggi_badan">Tinggi Badan (cm):</label>
                <input type="text" class="form-control" id="tinggi_badan" name="tinggi_badan" required>
                <!-- Input untuk masukkan tinggi badan -->
            </div>
            <button type="submit" class="btn btn-primary" name="hitung_bmi">Hitung BMI</button>
            <!-- Tombol submit untuk menghitung BMI -->
        </form>

        <?php
        // Proses hitung BMI dan tampilkan hasil jika tombol submit ditekan
        if (isset($_POST['hitung_bmi'])) {
            // Ambil nilai berat badan dan tinggi badan dari form
            $berat_badan = $_POST['berat_badan'];
            $tinggi_badan = $_POST['tinggi_badan'];

            // Validasi input (contoh sederhana, sebaiknya gunakan validasi lebih detail)
            if (!empty($berat_badan) && !empty($tinggi_badan)) {
                // Konversi tinggi badan dari cm ke meter
                $tinggi_meter = $tinggi_badan / 100;

                // Hitung BMI
                $bmi = $berat_badan / ($tinggi_meter * $tinggi_meter);

                // Tampilkan hasil
                echo "<div class='alert alert-info mt-3'>";
                echo "<p><strong>BMI Anda:</strong> " . number_format($bmi, 1) . "</p>";
                echo "<p><strong>Interpretasi:</strong> ";
                if ($bmi < 18.5) {
                    echo "Berat badan kurang";
                } elseif ($bmi >= 18.5 && $bmi < 24.9) {
                    echo "Berat badan normal";
                } elseif ($bmi >= 25 && $bmi < 29.9) {
                    echo "Berat badan berlebih";
                } elseif ($bmi >= 30) {
                    echo "Obesitas";
                }
                echo "</p>";
                echo "</div>";

                // Tabel untuk klasifikasi BMI
                echo "<div class='mt-3'>";
                echo "<h4>Klasifikasi BMI</h4>";
                echo "<table class='table table-bordered'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Nilai BMI</th>";
                echo "<th>Interpretasi</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                echo "<tr><td><18.5</td><td>Berat badan kurang</td></tr>";
                echo "<tr><td>18.5 - 24.9</td><td>Berat badan normal</td></tr>";
                echo "<tr><td>25 - 29.9</td><td>Berat badan berlebih</td></tr>";
                echo "<tr><td>>= 30</td><td>Obesitas</td></tr>";
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-danger mt-3'>Masukkan berat badan dan tinggi badan.</div>";
                // Pesan error jika input kosong
            }
        }
        ?>
    </div>
</body>
</html>
<?php include('../includes/footer.php'); ?>
