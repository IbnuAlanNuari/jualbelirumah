<?php
// Bagian PHP tetap sama
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../db.php'; // Koneksi ke database

// Jumlah data yang ingin ditampilkan per halaman
$limit = 5;

// Menentukan halaman saat ini, jika tidak ada maka halaman 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Menentukan offset berdasarkan halaman
$offset = ($page - 1) * $limit;

// Mengambil total data untuk menghitung jumlah halaman
$total_result = $conn->query("SELECT COUNT(*) AS total FROM properties");
$total_row = $total_result->fetch_assoc();
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $limit);

// Menerima input pencarian dari form
$search_title = isset($_GET['title']) ? $_GET['title'] : '';
$search_type = isset($_GET['house_type']) ? $_GET['house_type'] : '';
$search_category = isset($_GET['category']) ? $_GET['category'] : '';

// Query untuk mengambil data properti berdasarkan pencarian dan pagination
$sql = "SELECT * FROM properties WHERE title LIKE '%$search_title%' AND house_type LIKE '%$search_type%' AND category LIKE '%$search_category%' LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo $_SESSION['message'];
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Properti - PT MITRA USAHA SYARIAH</title>
    <link rel="icon" href="../assets/images/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
    .carousel-item img {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100 top-0">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="../assets/images/logo.png" alt="Logo" class="me-2" style="height: 40px;">
                    <span class="fs-6 d-inline d-md-none">PT MITRA</span>
                    <span class="fs-5 d-none d-md-inline">PT MITRA USAHA SYARIAH</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="add_property.php" class="nav-link">Tambah Properti</a></li>
                        <li class="nav-item"><a href="view_contacts.php" class="nav-link">Ulasan</a></li>                        
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div>
        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-center bg-dark text-white">
                            <h5>Daftar Properti</h5>
                        </div>
                        <div class="card-body">
                            <!-- Form Pencarian -->
                            <form class="mb-3" method="get">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="title" placeholder="Judul" value="<?= $search_title ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="house_type" placeholder="Tipe Rumah" value="<?= $search_type ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="category">
                                            <option value="">Pilih Kategori</option>
                                            <option value="0" <?= $search_category == '0' ? 'selected' : '' ?>>Jual</option>
                                            <option value="1" <?= $search_category == '1' ? 'selected' : '' ?>>Sewa</option>
                                            <option value="2" <?= $search_category == '2' ? 'selected' : '' ?>>Sold Out</option>
                                            <option value="3" <?= $search_category == '3' ? 'selected' : '' ?>>Take Over</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Judul</th>
                                            <th>Tipe Rumah</th>
                                            <th>Luas Tanah</th>
                                            <th>Luas Bangunan</th>
                                            <th>Harga</th>
                                            <th>Lokasi</th>
                                            <th>Status</th>
                                            <th>Kategori</th>
                                            <th>Gambar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['title'] . "</td>";
                                            echo "<td>" . $row['house_type'] . "</td>";
                                            echo "<td>" . $row['land_area'] . "</td>";
                                            echo "<td>" . $row['building_area'] . "</td>";
                                            echo "<td>Rp " . number_format((int)$row['price'], 0, ',', '.') . "</td>";
                                            echo "<td>" . $row['location'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";

                                            // Display category
                                            switch ($row['category']) {
                                                case '0': echo "<td>Jual</td>"; break;
                                                case '1': echo "<td>Sewa</td>"; break;
                                                case '2': echo "<td>Sold Out</td>"; break;
                                                case '3': echo "<td>Take Over</td>"; break;
                                                default: echo "<td>Unknown</td>"; break;
                                            }

                                            // Display image carousel
                                            echo "<td>";
                                            $images = explode(',', $row['images']);
                                            if (count($images) > 0 && !empty($images[0])) {
                                                $carousel_id = "carousel-" . $row['id'];
                                                echo "<div id='$carousel_id' class='carousel slide'>";
                                                echo "<div class='carousel-inner'>";
                                                foreach ($images as $index => $image) {
                                                    $active_class = $index === 0 ? "active" : "";
                                                    echo "<div class='carousel-item $active_class'>";
                                                    echo "<img src='../assets/images/" . trim($image) . "' alt='Image'>";
                                                    echo "</div>";
                                                }
                                                echo "</div>";
                                                echo "<a class='carousel-control-prev' href='#$carousel_id' role='button' data-bs-slide='prev'>";
                                                echo "<span class='carousel-control-prev-icon' aria-hidden='true'></span>";
                                                echo "<span class='visually-hidden'>Previous</span>";
                                                echo "</a>";
                                                echo "<a class='carousel-control-next' href='#$carousel_id' role='button' data-bs-slide='next'>";
                                                echo "<span class='carousel-control-next-icon' aria-hidden='true'></span>";
                                                echo "<span class='visually-hidden'>Next</span>";
                                                echo "</a>";
                                                echo "</div>";
                                            } else {
                                                echo "<img src='../assets/images/default.jpg' width='50' alt='No Image'>";
                                            }
                                            echo "</td>";

                                            // Action buttons
                                            echo "<td>
                                                    <a href='add_property.php?edit=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                                    <a href='add_property.php?delete=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                                </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div> <!-- End of Responsive Table -->

                            <!-- Pagination -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?= $page == 1 ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>

                                    <?php
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'>";
                                        echo "<a class='page-link' href='?page=$i'>$i</a>";
                                        echo "</li>";
                                    }
                                    ?>

                                    <li class="page-item <?= $page == $total_pages ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2022 PT MITRA USAHA SYARIAH.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
