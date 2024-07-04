<?php include('../includes/header.php'); ?>

<style>
    .landing-page {
        background-image: url('../assets/img/pelayanan.png');
        background-size: cover;
        background-position: center;
        height: 100vh; /* Set tinggi sesuai dengan viewport height */
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative; /* Menetapkan posisi relatif untuk kontainer */
        overflow: hidden; /* Mengatur overflow agar tetap di dalam kontainer */
        color: #fff; /* Warna teks putih agar kontras dengan latar belakang */
    }

    .landing-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4); /* Warna overlay transparan */
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        z-index: 1; /* Menempatkan overlay di atas gambar */
    }

    .landing-content {
        padding: 0 15px;
        position: relative; /* Menetapkan posisi relatif untuk konten di dalam overlay */
        z-index: 2; /* Menempatkan konten di atas overlay */
    }
</style>

<div class="container-fluid landing-page">
    <div class="landing-overlay">
        <div class="row">
            <div class="col-md-12 landing-content">
                <h1 class="display-4"><strong>Selamat Datang di Sistem Informasi Publik</strong></h1>
                <p class="lead">Sistem ini dirancang untuk memudahkan administrasi dan pelayanan di poliklinik kami.</p>
                <a href="periksa.php" class="btn btn-primary btn-lg">Periksa Pasien</a>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Layanan</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="periksa_pasien.php">Periksa Pasien</a></li>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Tambahan</h5>
                    <p class="card-text">Tambahkan informasi penting atau berita terbaru seputar poliklinik atau layanan kesehatan.</p>
                    <a href="informasi_lain.php" class="btn btn-info">Lihat Informasi Lainnya</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
