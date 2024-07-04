<?php include('../includes/header.php'); ?>

<style>
    .landing-page {
        background-image: url('../assets/img/pelayanan.png');
        background-size: cover;
        background-position: center;
        height: 95vh;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        color: #fff;
    }

    .landing-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        z-index: 1;
    }

    .landing-content {
        padding: 0 15px;
        position: relative;
        z-index: 2;
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
                    <h5 class="card-title">Cek Body Mass Index</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" id="current-date"></li>
                    </ul>
                    <ul class="list-group list-group-flush">
                    <a href="bmi.php" class="btn btn-info">Hitung BMI</a>
                    </ul>
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

<!-- jQuery, Popper.js, Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Mengambil tanggal hari ini dengan JavaScript
    document.addEventListener("DOMContentLoaded", function() {
        var currentDate = new Date();
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        var formattedDate = currentDate.toLocaleDateString('id-ID', options);

        document.getElementById("current-date").innerText = formattedDate;
    });
</script>
</body>
</html>
