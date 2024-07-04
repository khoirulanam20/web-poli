<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include('../config/database.php');

$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';
unset($_SESSION['status']);

$query_pasien = "SELECT COUNT(*) as total_pasien FROM pasien";
$result_pasien = mysqli_query($conn, $query_pasien);
$row_pasien = mysqli_fetch_assoc($result_pasien);
$query_dokter = "SELECT COUNT(*) as total_dokter FROM dokter";
$result_dokter = mysqli_query($conn, $query_dokter);
$row_dokter = mysqli_fetch_assoc($result_dokter);

$query_obat = "SELECT COUNT(*) as total_obat FROM obat";
$result_obat = mysqli_query($conn, $query_obat);
$row_obat = mysqli_fetch_assoc($result_obat);

$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';
unset($_SESSION['status']);

$query_pasien = "SELECT * FROM pasien";
$result_pasien = mysqli_query($conn, $query_pasien);
$query_dokter = "SELECT * FROM dokter";
$result_dokter = mysqli_query($conn, $query_dokter);


$query_obat = "SELECT * FROM obat";
$result_obat = mysqli_query($conn, $query_obat);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom styles -->
    <style>
        .sidebar {
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #343a40; /* Warna latar belakang sidebar */
            padding-top: 50px;
            overflow-y: auto; /* Tambahkan scroll jika menu terlalu panjang */
            z-index: 1000;
            transition: all 0.3s;
        }
        .sidebar a {
            padding: 10px 15px;
            font-size: 18px;
            color: #f8f9fa; /* Warna teks pada menu sidebar */
            display: block;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057; /* Warna latar belakang saat hover */
        }
        .sidebar .active {
            background-color: #495057; /* Warna latar belakang untuk menu aktif */
        }
        .sidebar .nav-link {
            font-weight: 500;
        }
        .sidebar .nav-item {
            margin-bottom: 1px; /* Jarak antar item menu */
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            border: 1px solid rgba(0,0,0,.125);
            border-radius: .25rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }
        .card-body {
            padding: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#" onclick="loadContent('dashboard_admin.php')">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadContent('data_obat.php')">Data Obat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadContent('data_dokter.php')">Data Dokter</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadContent('periksa_admin.php')">Periksa</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="logout()">Logout</a>
            </li>
        </ul>
    </div>

    <div class="main-content" id="main-content">
        <!-- Tampilkan pesan status jika ada -->
        <?php if (!empty($status)): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $status; ?>
            </div>
        <?php endif; ?>

        <!-- Konten utama dashboard disini -->
        <h1 class="mb-4">Selamat Datang di Dashboard</h1>
        <p>Halo, <?php echo $_SESSION['username']; ?>. Anda telah berhasil login.</p>

        <!-- Informasi statistik -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Pasien</h5>
                        <p class="card-text"><?php echo $row_pasien['total_pasien']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Dokter</h5>
                        <p class="card-text"><?php echo $row_dokter['total_dokter']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Obat</h5>
                        <p class="card-text"><?php echo $row_obat['total_obat']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel data pasien -->
        <div class="mt-4">
            <h2>Data Pasien</h2>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">No. HP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_pasien)): ?>
                    <tr>
                        <th scope="row"><?php echo $row['id']; ?></th>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['alamat']; ?></td>
                        <td><?php echo $row['no_hp']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel data dokter -->
        <div class="mt-4">
            <h2>Data Dokter</h2>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">No. HP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_dokter)): ?>
                    <tr>
                        <th scope="row"><?php echo $row['id']; ?></th>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['alamat']; ?></td>
                        <td><?php echo $row['no_hp']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel data obat -->
        <div class="mt-4">
            <h2>Data Obat</h2>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nama Obat</th>
                        <th scope="col">Kemasan</th>
                        <th scope="col">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_obat)): ?>
                    <tr>
                        <th scope="row"><?php echo $row['id']; ?></th>
                        <td><?php echo $row['nama_obat']; ?></td>
                        <td><?php echo $row['kemasan']; ?></td>
                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS dan jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Fungsi untuk memuat konten dashboard tanpa refresh halaman menggunakan fetch API
        function loadContent(page) {
            fetch(page)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('main-content').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }

        // Fungsi untuk logout
        function logout() {
            // Tampilkan konfirmasi sebelum melakukan logout
            if (confirm('Apakah Anda yakin ingin logout?')) {
                fetch('logout.php')
                    .then(response => response.text())
                    .then(data => {
                        // Redirect ke halaman login setelah logout berhasil
                        window.location.href = 'login.php';
                    })
                    .catch(error => console.error('Error:', error));
            }
        }


    </script>
</body>
</html>
