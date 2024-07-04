<?php
session_start();

// Jika pengguna sudah login, redirect ke halaman dashboard atau halaman yang sesuai
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Include file koneksi ke database atau menggunakan koneksi yang telah disediakan sebelumnya
include('../config/database.php');

// Variabel untuk menyimpan pesan status dari proses registrasi
$status = '';

// Proses registrasi jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Anda dapat menambahkan validasi lain sesuai kebutuhan seperti konfirmasi password, validasi email, dsb.
    // Contoh sederhana hanya untuk tujuan demonstrasi
    // Silakan sesuaikan dengan kebutuhan keamanan aplikasi Anda

    // Hash password menggunakan metode default PHP
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk memasukkan data pengguna baru ke dalam database
    $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        $status = "Registrasi berhasil. Silakan login.";
    } else {
        $status = "Registrasi gagal. Silakan coba lagi.";
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna Baru</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Register
                </div>
                
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                    </form>
                    <div class="mt-3">
                        <?php if(!empty($status)) { ?>
                            <div class="alert alert-info"><?php echo $status; ?></div>
                        <?php } ?>
                        <a href="login.php">Sudah memiliki akun? Login di sini.</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
