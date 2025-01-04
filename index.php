<?php
// Menghubungkan dengan db.php
include 'db.php';

// Query untuk mengambil data properti terbaru, dibatasi 3 item
$sql_new = 'SELECT * FROM properties WHERE created_at >= NOW() - INTERVAL 30 DAY ORDER BY created_at DESC LIMIT 3';
$result_new = $conn->query($sql_new);

// Query untuk mengambil data properti terbaik berdasarkan harga terendah, dibatasi 3 item
$sql_best = 'SELECT * FROM properties WHERE price > 0 ORDER BY price ASC LIMIT 3';
$result_best = $conn->query($sql_best);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT MITRA USAHA SYARIAH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="index">
                    <img src="assets/images/logo.png" alt="Logo" class="me-2" style="height: 40px;">
                    <span class="fs-5">PT MITRA USAHA SYARIAH</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="index" class="nav-link">Home</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#properties" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Property
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="new_property">Property Terbaru</a></li>
                                <li><a class="dropdown-item" href="best_property">Property Terbaik</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="#about-us" class="nav-link">Tentang Kami</a></li>
                        <li class="nav-item"><a href="contact" class="nav-link">Kontak</a></li>
                        <li class="nav-item"><a href="admin/login.php" target="_blank" class="nav-link">Admin</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center" style="height: 100vh; background-color: #f8f9fa;">
        <div class="container">
            <div class="row align-items-center">
                <!-- Gambar -->
                <div class="col-md-6 order-2 order-md-1">
                    <img src="assets/images/display.jpg" alt="Hero Image" class="img-fluid rounded shadow">
                </div>
                <!-- Teks -->
                <div class="col-md-6 order-1 order-md-2 text-center text-md-start">
                    <h1 class="display-4 fw-bold">Temukan Rumah Impian Anda</h1>
                    <p class="lead">Jelajahi koleksi properti kami untuk menemukan rumah yang sesuai dengan kebutuhan
                        dan anggaran Anda.</p>
                    <a href="#properties" class="btn btn-primary btn-lg mt-3">Lihat Properti</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="about-us" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <!-- Text Tentang Kami -->
                <div class="col-md-6 order-2 order-md-1" style="text-align: justify;">
                    <h2 class="fw-bold">Tentang Kami</h2>
                    <p>
                        PT MITRA USAHA SYARIAH didirikan berdasarkan Perseroan Terbatas Perorangan
                        tanggal 1 Mei 2022. Perusahaan tersebut berkedudukan di Kota Cilegon - Banten dan
                        telah mendapatkan pengesahan oleh Menteri Hukum dan Hak Asasi Manusia Republik
                        Indonesia melalul Surat Keputusan No. AHU-016032.AH.01.30.Tahun 2022.
                    </p>
                    <p>
                        Kami bekerjasama dengan hampir seluruh Bank Pemerintah dan Swasta dalam hal
                        penyedia Kredit KPR, dsb. Dan juga ada bekerjasama dengan ratusan Developer atau
                        pengembang dalam hal penjualan properti primary.
                    </p>
                    <a href="contact.php" class="btn btn-dark btn-lg mt-3">Hubungi Kami</a>
                </div>
                <!-- Gambar -->
                <div class="col-md-6 order-1 order-md-2 text-center">
                    <img src="assets/images/hero-image.png" alt="Tentang Kami" class="img-fluid rounded shadow-lg"
                        style="max-width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2022 PT MITRA USAHA SYARIAH.</p>
    </footer>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>