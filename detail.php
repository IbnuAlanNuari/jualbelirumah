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
            <div class="container">
                <a class="navbar-brand" href="index"><img src="assets/images/logo.png" alt="Logo" class="me-2"
                        style="height: 40px;">PT MITRA USAHA SYARIAH</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="index" class="nav-link">Home</a></li>
                        <li class="nav-item"><a href="new_property" class="nav-link">Property baru</a></li>
                        <li class="nav-item"><a href="best_property" class="nav-link">Property Terbaik</a></li>
                        <li class="nav-item"><a href="contact" class="nav-link">Kontak</a></li>
                </div>
            </div>
        </nav>
    </header>

    <!-- Detail Properti -->
    <div class="container py-5 mt-5">
        <h1 class="mb-4"><?php echo htmlspecialchars($row['title']); ?></h1>

        <!-- Gambar Properti -->
        <div class="row">
            <?php
        foreach ($images as $index => $image) {
            echo "<div class='col-md-4 mb-4'>";
            echo "<img src='assets/images/" . htmlspecialchars(trim($image)) . "' class='img-fluid img-thumbnail' alt='Properti'>";
            echo "</div>";

            // Tambahkan div.row baru setiap 3 gambar
            if (($index + 1) % 3 === 0 && $index + 1 < count($images)) {
                echo "</div><div class='row'>";
            }
        }
        ?>
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
            </div>

            <!-- Bagikan ke Media Sosial -->
            <div class="share-section mt-4">
                <p class="mb-2"><strong>Bagikan Properti Ini:</strong></p>
                <div class="d-flex gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://yourwebsite.com/detail?id=' . $row['id']); ?>"
                        target="_blank" class="btn btn-primary d-inline-flex align-items-center gap-2">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://yourwebsite.com/detail?id=' . $row['id']); ?>&text=Properti%20Tersedia%20untuk%20Anda!"
                        target="_blank" class="btn btn-info text-white d-inline-flex align-items-center gap-2">
                        <i class="bi bi-twitter"></i> Twitter
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Lihat properti ini: https://yourwebsite.com/detail?id=' . $row['id']); ?>"
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>