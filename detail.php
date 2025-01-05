<?php
include 'db.php';

// Ambil ID dari URL dan validasi
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Cek apakah ID valid
if ($id <= 0) {
    die("Properti tidak valid.");
}

// Query untuk mendapatkan detail properti
$query = "SELECT * FROM properties WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();



// Jika properti tidak ditemukan
if (!$row) {
    die("Properti tidak ditemukan.");
}

// Ambil gambar properti
$images = isset($row['images']) && !empty($row['images']) 
    ? explode(',', $row['images']) 
    : ['default.jpg']; // Jika tidak ada gambar, gunakan gambar default
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Properti - <?php echo htmlspecialchars($row['title']); ?></title>
    <link rel="icon" href="assets/images/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- Brand Logo di sebelah kiri -->
                <a class="navbar-brand d-flex align-items-center" href="index">
                    <img src="assets/images/logo.png" alt="Logo" class="me-2" style="height: 40px;">
                    <span class="fs-6 d-inline d-md-none">PT MITRA</span> <!-- Nama kecil untuk layar kecil -->
                    <span class="fs-5 d-none d-md-inline">PT MITRA USAHA SYARIAH</span>
                    <!-- Nama lengkap untuk layar besar -->
                </a>
                <!-- Toggler untuk layar kecil -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar Links di sebelah kanan -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="index" class="nav-link">Home</a></li>
                        <li class="nav-item"><a href="new_property" class="nav-link">Property Baru</a></li>
                        <li class="nav-item"><a href="best_property" class="nav-link">Property Terbaik</a></li>
                        <li class="nav-item"><a href="contact" class="nav-link">Kontak</a></li>
                    </ul>
                </div>
            </div>
        </nav>

    </header>

    <!-- Detail Properti -->
    <div class="container py-5 mt-5">
        <h1 class="mb-4"><?php echo htmlspecialchars($row['title']); ?></h1>

        <!-- Gambar Properti -->
        <div class="row">
            <div class="col-12">
                <!-- Carousel Utama -->
                <div id="imageCarousel" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-inner">
                        <?php
                foreach ($images as $index => $image) {
                    $activeClass = $index === 0 ? 'active' : '';
                    echo "
                        <div class='carousel-item $activeClass'>
                            <img src='assets/images/" . htmlspecialchars(trim($image)) . "' 
                                 class='d-block w-100 img-fluid fixed-size' 
                                 alt='Properti' 
                                 data-bs-toggle='modal' 
                                 data-bs-target='#imageModal'>";
                    echo "</div>";
                }
                ?>
                    </div>
                    <!-- Navigasi Carousel -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal untuk Gambar -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body p-0 text-center">
                        <!-- Carousel Independen di dalam Modal -->
                        <div id="modalCarousel" class="carousel slide" data-bs-ride="false">
                            <div class="carousel-inner">
                                <?php
                        foreach ($images as $index => $image) {
                            $activeClass = $index === 0 ? 'active' : '';
                            echo "
                                <div class='carousel-item $activeClass'>
                                    <img src='assets/images/" . htmlspecialchars(trim($image)) . "' 
                                         class='img-fluid' 
                                         alt='Properti'>
                                </div>";
                        }
                        ?>
                            </div>
                            <!-- Navigasi Carousel Modal -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#modalCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#modalCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Properti -->
        <p class="card-text">
            <strong>Kategori:</strong>
            <?php 
                            switch ($row['category']) {
                                case '0': echo "Jual"; break;
                                case '1': echo "Sewa"; break;
                                case '2': echo "Sold Out"; break;
                                case '3': echo "Take Over Jual"; break;
                                default: echo "Unknown"; break;
                            }
                            ?>
        </p>
        <div class="property-details">
            <p><strong>Properti Tersedia:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
            <p><strong>Tipe Rumah:</strong> <?php echo htmlspecialchars($row['house_type']); ?></p>
            <p><strong>Luas Tanah:</strong> <?php echo htmlspecialchars($row['land_area']); ?> m<sup>2</sup></p>
            <p><strong>Luas Bangunan:</strong> <?php echo htmlspecialchars($row['building_area']); ?> m<sup>2</sup></p>
            <p><strong>Harga:</strong> Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
            <p><strong>Deskripsi:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

            <!-- Link WhatsApp -->
            <div class="contact-section mt-4">
                <p class="mb-2"><strong>Ingin bertanya lebih lanjut tentang rumah ini?</strong></p>
                <a href="https://wa.me/+6289665550003" target="_blank"
                    class="btn btn-success d-inline-flex align-items-center gap-2">
                    <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                </a>
                <a href="javascript:history.back();" class="btn btn-secondary d-inline-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <!-- Bagikan ke Media Sosial -->
            <div class="share-section mt-4">
                <p class="mb-2"><strong>Bagikan Properti Ini:</strong></p>
                <div class="d-flex gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://mitrausahasyariah.my.id/detail?id=' . $row['id']); ?>"
                        target="_blank" class="btn btn-primary d-inline-flex align-items-center gap-2">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://mitrausahasyariah.my.id/detail?id=' . $row['id']); ?>&text=Properti%20Tersedia%20untuk%20Anda!"
                        target="_blank" class="btn btn-info text-white d-inline-flex align-items-center gap-2">
                        <i class="bi bi-twitter"></i> Twitter
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Lihat properti ini: https://mitrausahasyariah.my.id/detail?id=' . $row['id']); ?>"
                        target="_blank" class="btn btn-success d-inline-flex align-items-center gap-2">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2022 PT MITRA USAHA SYARIAH.</p>
    </footer>


    <!-- Tambahkan Bootstrap CSS dan JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- CSS untuk Carousel Utama -->
    <style>
    .fixed-size {
        width: 100%;
        height: 300px;
        /* Tinggi tetap untuk carousel utama */
        object-fit: cover;
        /* Menjaga proporsi tanpa distorsi */
    }
    </style>

    <script>
    // JavaScript untuk sinkronisasi slide utama dan modal
    const mainCarousel = document.getElementById('imageCarousel');
    const modalCarousel = document.getElementById('modalCarousel');

    mainCarousel.addEventListener('slide.bs.carousel', function(event) {
        const activeIndex = event.to;
        const modalCarouselInstance = bootstrap.Carousel.getInstance(modalCarousel);
        modalCarouselInstance.to(activeIndex); // Sinkronkan slide dengan modal
    });
    </script>

</body>

</html>