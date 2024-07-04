<?php
include('../includes/header.php');

// API Key dari GNews
$apiKey = '7dd986e743bd4c70e7d65531aa582504';

// URL untuk mengambil berita kesehatan dari GNews
$url = 'https://gnews.io/api/v4/top-headlines?topic=health&lang=en&token=' . $apiKey;

// Mengambil data berita dari GNews
$response = file_get_contents($url);

// Cek apakah response berhasil diambil
if ($response === FALSE) {
    die('Error occurred while fetching data from GNews');
}

$newsData = json_decode($response, true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Kesehatan</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            flex: 1;
        }
        .card-text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Informasi Kesehatan</h1>
        <?php if(isset($newsData['articles']) && !empty($newsData['articles'])): ?>
            <div class="row">
                <?php foreach($newsData['articles'] as $article): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php if($article['image']): ?>
                                <img src="<?php echo $article['image']; ?>" class="card-img-top" alt="...">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $article['title']; ?></h5>
                                <p class="card-text"><?php echo $article['description']; ?></p>
                                <a href="<?php echo $article['url']; ?>" target="_blank" class="btn btn-primary">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Tidak ada berita yang tersedia saat ini.</p>
        <?php endif; ?>
    </div>
    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
