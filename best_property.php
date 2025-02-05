<?php
// Include koneksi ke database
include 'db.php'; // Pastikan file koneksi sesuai dengan yang digunakan

// Ambil parameter kategori dari URL
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Siapkan query berdasarkan kategori dan urutkan berdasarkan harga
if ($category !== '') {
    $query = "SELECT * FROM properties WHERE category = '" . $conn->real_escape_string($category) . "' ORDER BY price ASC"; // Berdasarkan kategori
} else {
    $query = "SELECT * FROM properties ORDER BY price ASC"; // Semua properti
}

// Eksekusi query
$result = $conn->query($query);

// Periksa apakah query berhasil
if (!$result) {
    die("Query error: " . $conn->error);
}
?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT MITRA USAHA SYARIAH</title>
    <link rel="icon" href="assets/images/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header class="bg-dark text-white text-center py-3">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- Logo dan Brand di sebelah kiri -->
                <a class="navbar-brand d-flex align-items-center" href="best_property">
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

                <!-- Menu Navbar -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="index" class="nav-link">Home</a></li>
                        <li class="nav-item"><a href="new_property" class="nav-link">Property Terbaru</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="new_property.php" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Kategori
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="best_property?category=0">Jual</a></li>
                                <li><a class="dropdown-item" href="best_property?category=1">Sewa</a></li>
                                <li><a class="dropdown-item" href="best_property?category=2">Sold Out</a></li>
                                <li><a class="dropdown-item" href="best_property?category=3">Take Over Jual</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="contact" class="nav-link">Kontak</a></li>
                    </ul>

                    <!-- Form Pencarian di sebelah kanan -->
                    <form class="d-flex ms-lg-3 mt-2 mt-lg-0" action="search_properties" method="GET">
                        <input class="form-control me-2" type="search" name="query" placeholder="Cari Properti"
                            aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Cari</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <div class="container py-5 mt-5" style="margin-top: 7rem;">
            <h2 class="text-center mb-4">Properti Terbaik</h2>

            <div class="row custom-row">
                <?php
        $count = 0; // Inisialisasi counter untuk jumlah properti yang ditampilkan
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Memproses gambar properti
                $images = isset($row['images']) && !empty($row['images']) ? explode(',', $row['images']) : ['default.jpg'];

                // Menampilkan kartu properti
                echo "<div class='col-md-6 mb-4 custom-card'>"; // Menambahkan kelas custom-card untuk dua kartu per baris di ponsel
                echo "<div class='card'>"; // Kartu properti

                if (count($images) > 1) {
                    echo "<div id='carousel-" . $row['id'] . "' class='carousel slide' data-bs-ride='carousel'>";
                    echo "<div class='carousel-inner'>";
                    foreach ($images as $index => $image) {
                        $activeClass = $index === 0 ? 'active' : ''; // Hanya gambar pertama yang aktif
                        echo "<div class='carousel-item $activeClass'>";
                        echo "<img src='assets/images/" . htmlspecialchars(trim($image)) . "' class='d-block w-100' alt='" . htmlspecialchars($row['title']) . "'>";
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "<button class='carousel-control-prev' type='button' data-bs-target='#carousel-" . $row['id'] . "' data-bs-slide='prev'>";
                    echo "<span class='carousel-control-prev-icon' aria-hidden='true'></span>";
                    echo "<span class='visually-hidden'>Previous</span>";
                    echo "</button>";
                    echo "<button class='carousel-control-next' type='button' data-bs-target='#carousel-" . $row['id'] . "' data-bs-slide='next'>";
                    echo "<span class='carousel-control-next-icon' aria-hidden='true'></span>";
                    echo "<span class='visually-hidden'>Next</span>";
                    echo "</button>";
                    echo "</div>";
                } else {
                    // Jika hanya ada satu gambar
                    echo "<img src='assets/images/" . htmlspecialchars(trim($images[0])) . "' class='card-img-top' alt='" . htmlspecialchars($row['title']) . "'>";
                }

                // Informasi properti
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>"; // Judul properti

                // Menampilkan status properti
                echo "<p class='card-text'><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>"; // Status properti

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

                // Harga properti
                echo "<p class='card-text'><strong>Harga:</strong> Rp " . number_format($row['price'], 0, ',', '.') . "</p>";

                // Tombol lihat detail
                echo "<a href='detail?id=" . $row['id'] . "' class='btn btn-success w-100'>Lihat Detail</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>"; // Tutup col-md-6

                $count++; // Menambah counter setiap kali kartu ditampilkan
            }
        } else {
            echo "<div class='text-center'>";
            echo "<p>Tidak ada properti yang tersedia.</p>";
            echo "<a href='new_property' class='btn btn-primary'>Kembali</a>"; // Tombol kembali
            echo "</div>";
        }
        ?>
            </div>
        </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>