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
    <link rel="icon" href="assets/images/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .hero-section .col-md-6.order-1.order-md-2 {
            margin-top: 50px; /* Atur jarak sesuai kebutuhan */
        }
        /* CSS tambahan untuk tampilan mobile */
        @media (max-width: 768px) {
        .navbar-nav .nav-link {
            padding: 1rem;
        }
        .dropdown-menu {
            position: static;
            float: none;
        }
        }
    </style>
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

    <section class="hero-section d-flex align-items-center" style="height: 100vh; background-color: #f8f9fa;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 order-2 order-md-1">
                    <img src="assets/images/display.jpg" alt="Hero Image" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6 order-1 order-md-2 text-center text-md-start">
                    <h1 class="display-4 fw-bold">Temukan Rumah Impian Anda</h1>
                    <p class="lead">Jelajahi koleksi properti kami untuk menemukan rumah yang sesuai dengan kebutuhan
                        dan anggaran Anda.</p>
                    <a href="#properties" class="btn btn-primary btn-lg mt-3">Lihat Properti</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Daftar Properti -->
    <section id="properties" class="listing-properties py-5">
        <div class="container">
            <h2 class="text-center mb-4">Daftar Properti</h2>

            <!-- Properti Baru -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="btn btn-primary">Properti Baru</h3>
                <a href="new_property" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="row" id="newPropertiesList">
                <?php
            if ($result_new->num_rows > 0) {
                while ($row = $result_new->fetch_assoc()) {
                    $images = isset($row['images']) && !empty($row['images']) ? explode(',', $row['images']) : ['default.jpg'];
                    $images = array_slice($images, 0, 10); // Ambil maksimal 10 gambar

                    echo "<div class='col-md-4 col-sm-6 mb-4'>";
                    echo "<div class='card'>";

                    if (count($images) > 1) {
                        // Tampilkan carousel jika ada lebih dari satu gambar
                        echo "<div id='carouselNew" . $row['id'] . "' class='carousel slide' data-bs-ride='carousel'>";
                        echo "<div class='carousel-indicators'>";
                        foreach ($images as $index => $image) {
                            echo "<button type='button' data-bs-target='#carouselNew" . $row['id'] . "' data-bs-slide-to='$index' class='" . ($index === 0 ? 'active' : '') . "'></button>";
                        }
                        echo '</div>';

                        echo "<div class='carousel-inner'>";
                        foreach ($images as $index => $image) {
                            echo "<div class='carousel-item " . ($index === 0 ? 'active' : '') . "'><img src='assets/images/" . trim($image) . "' class='d-block w-100' alt='" . $row['title'] . "'></div>";
                        }
                        echo '</div>';

                        // Panah slide previous dan next
                        echo "<button class='carousel-control-prev' type='button' data-bs-target='#carouselNew" .
                            $row['id'] .
                            "' data-bs-slide='prev'>
                                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                                <span class='visually-hidden'>Previous</span>
                            </button>";
                        echo "<button class='carousel-control-next' type='button' data-bs-target='#carouselNew" .
                            $row['id'] .
                            "' data-bs-slide='next'>
                                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                                <span class='visually-hidden'>Next</span>
                            </button>";
                        echo '</div>';
                    } else {
                        // Tampilkan gambar statis jika hanya ada satu gambar
                        echo "<img src='assets/images/" . trim($images[0]) . "' class='card-img-top' alt='" . htmlspecialchars($row['title']) . "'>";
                    }

                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . '</h5>'; // Judul properti
                    echo "<p class='card-text '><strong>Status:</strong> " . ucfirst(htmlspecialchars($row['status'])) . '</p>'; // Status properti
                    // Menampilkan kategori
                    echo "<p class='card-text'><strong>Kategori:</strong> ";
                    switch ($row['category']) {
                        case '0':
                            echo "Jual";
                            break;
                        case '1':
                            echo "Sewa";
                            break;
                        case '2':
                            echo "Sold Out";
                            break;
                        case '3':
                            echo "Take Over Jual";
                            break;
                        default:
                            echo "Unknown";
                            break;
                    }
                    echo "</p>";
                    echo "<p class='card-text'><strong>Harga:</strong> Rp " . number_format($row['price'], 0, ',', '.') . '</p>'; // Harga properti
                    echo "<a href='detail?id=" . $row['id'] . "' class='btn btn-primary w-100'>Lihat Detail</a>"; // Tombol detail
                    echo '</div>'; // Tutup card-body
                    echo '</div>'; // Tutup card
                    echo '</div>'; // Tutup kolom
                }
            } else {
                echo "<p class='text-center'>Tidak ada properti baru ditemukan.</p>";
            }
            ?>

            </div>

            <!-- Properti Terbaik -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="btn btn-success">Properti Terbaik</h3>
                <a href="best_property" class="btn btn-outline-success btn-sm">Lihat Semua</a>
            </div>
            <div class="row" id="bestPropertiesList">
                <?php
            if ($result_best->num_rows > 0) {
                while ($row = $result_best->fetch_assoc()) {
                    $images = isset($row['images']) && !empty($row['images']) ? explode(',', $row['images']) : ['default.jpg'];
                    $images = array_slice($images, 0, 10); // Ambil maksimal 10 gambar

                    echo "<div class='col-md-4 col-sm-6 mb-4'>";
                    echo "<div class='card'>";

                    if (count($images) > 1) {
                        // Tampilkan carousel jika ada lebih dari satu gambar
                        echo "<div id='carouselBest" . $row['id'] . "' class='carousel slide' data-bs-ride='carousel'>";
                        echo "<div class='carousel-indicators'>";
                        foreach ($images as $index => $image) {
                            echo "<button type='button' data-bs-target='#carouselBest" . $row['id'] . "' data-bs-slide-to='$index' class='" . ($index === 0 ? 'active' : '') . "'></button>";
                        }
                        echo '</div>';

                        echo "<div class='carousel-inner'>";
                        foreach ($images as $index => $image) {
                            echo "<div class='carousel-item " . ($index === 0 ? 'active' : '') . "'><img src='assets/images/" . trim($image) . "' class='d-block w-100' alt='" . $row['title'] . "'></div>";
                        }
                        echo '</div>';

                        // Panah slide previous dan next
                        echo "<button class='carousel-control-prev' type='button' data-bs-target='#carouselBest" .
                            $row['id'] .
                            "' data-bs-slide='prev'>
                                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                                <span class='visually-hidden'>Previous</span>
                            </button>";
                        echo "<button class='carousel-control-next' type='button' data-bs-target='#carouselBest" .
                            $row['id'] .
                            "' data-bs-slide='next'>
                                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                                <span class='visually-hidden'>Next</span>
                            </button>";
                        echo '</div>';
                    } else {
                        // Tampilkan gambar statis jika hanya ada satu gambar
                        echo "<img src='assets/images/" . trim($images[0]) . "' class='card-img-top' alt='" . htmlspecialchars($row['title']) . "'>";
                    }

                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . '</h5>'; // Judul properti
                    echo "<p class='card-text text-muted'><strong>Status:</strong> " . ucfirst(htmlspecialchars($row['status'])) . '</p>'; // Status properti
                    echo "<p class='card-text'><strong>Harga:</strong> Rp " . number_format($row['price'], 0, ',', '.') . '</p>';

                    // Tombol detail
                    echo "<a href='detail?id=" . $row['id'] . "' class='btn btn-success w-100'>Lihat Detail</a>";
                    echo '</div>'; // Tutup card-body
                    echo '</div>'; // Tutup card
                    echo '</div>'; // Tutup kolom
                }
            } else {
                echo "<p class='text-center'>Tidak ada properti terbaik ditemukan.</p>";
            }
            ?>

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
                        Perusahaan ini bergerak di bidang jasa, terutama jasa jual, beli dan sewa properti serta
                        kendaraan roda empat
                    </p>
                    <p>
                        Pada Tahun 2022 PT Mitra Usaha Syariah memulai bisnis agent properti di Kota Cilegon-
                        Banten. Selama masa merintis, kami memberikan layanan yang terbaik untuk informasi
                        dan saran mengenai properti agar mendapatkan hunian atau properti yang sesuai
                    </p>
                    <p>
                        Kami bekerjasama dengan hampir seluruh Bank Pemerintah dan Swasta dalam hal
                        penyedia Kredit KPR, dsb. Dan juga ada bekerjasama dengan ratusan Developer atau
                        pengembang dalam hal penjualan properti primary. Disamping itu kami juga mempunyai
                        puluhan ribu relasi dan database dalam penjualan secondary market.
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