<?php
// Include database connection
include('db.php');

// Ambil kata kunci pencarian dari parameter URL
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Query untuk mencari properti berdasarkan judul, deskripsi, atau lokasi
$sql = "SELECT * FROM properties WHERE title LIKE ? OR description LIKE ? OR location LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $searchQuery . "%"; // Membuat pola pencarian
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Properti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="assets/images/logo.png" alt="Logo" class="me-2"
                    style="height: 40px;">PT MITRA USAHA SYARIAH</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="new_property.php" class="nav-link">Property Terbaru</a></li>
                    <li class="nav-item"><a href="best_property.php" class="nav-link">Properti Terbaik</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container mt-5 pt-5">
    <h1 class="text-center mb-4">Hasil Pencarian Properti</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
            <?php 
                $images = isset($row['images']) && !empty($row['images']) 
                    ? explode(',', $row['images']) 
                    : ['default.jpg'];
            ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-light">
                    <!-- Carousel Slider untuk Gambar -->
                    <div id="carousel-<?php echo $row['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="assets/images/<?php echo trim($image); ?>" class="d-block w-100" 
                                    alt="Image for <?php echo htmlspecialchars($row['title']); ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($images) > 1): ?>
                        <!-- Kontrol Carousel -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?php echo $row['id']; ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?php echo $row['id']; ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <!-- Status Properti -->
                        <p class="card-text"><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                        <!-- Kategori Properti -->
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
                        <!-- Harga Properti -->
                        <p class="h6"><strong>Harga:</strong> Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                        <!-- Tombol Detail -->
                        <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn btn-dark w-100">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center">Tidak ada properti yang ditemukan dengan kata kunci:
            <strong><?php echo htmlspecialchars($searchQuery); ?></strong>
        </p>
    <?php endif; ?>
</div>
<div class="text-center mt-4">
            <a href="new_property.php" class="btn btn-outline-dark">Kembali ke Halaman Utama</a>
        </div>
<p>
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2022 PT MITRA USAHA SYARIAH.</p>
</footer>
</p>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
