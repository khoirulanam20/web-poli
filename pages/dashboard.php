<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include('../config/database.php');

// Ambil pesan status dari session jika ada
$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';

// Hapus pesan status dari session setelah ditampilkan
unset($_SESSION['status']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    </style>
</head>
<body>
    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#" onclick="loadContent('dashboard.php')">Dashboard</a>
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
            fetch('logout.php')
                .then(response => response.text())
                .then(data => {
                    // Redirect ke halaman login setelah logout berhasil
                    window.location.href = 'login.php';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
