<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login");
    exit();
}

include '../db.php'; // Menyertakan koneksi ke database

// Inisialisasi pesan notifikasi
$message = "";

// Proses form submit untuk menambah atau mengedit properti
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['property_title'];
    $description = $_POST['property_description'];
    $price = str_replace('.', '', $_POST['property_price']); // Hapus titik dari format input
    $location = $_POST['property_location'];
    $category = $_POST['property_category'];  // Mengambil kategori dari form
    $status = $_POST['property_status'];  // Status properti
    $house_type = $_POST['property_house_type'];  // Tipe rumah
    $land_area = $_POST['property_land_area'];  // Luas tanah
    $building_area = $_POST['property_building_area'];  // Luas bangunan

    // Menyimpan data properti baru ke dalam database
    if (isset($_POST['property_id']) && $_POST['property_id'] != '') {
        // Update properti yang sudah ada
        $property_id = $_POST['property_id'];
        $sql = "UPDATE properties SET title = ?, description = ?, price = ?, location = ?, category = ?, status = ?, house_type = ?, land_area = ?, building_area = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsdssddi", $title, $description, $price, $location, $category, $status, $house_type, $land_area, $building_area, $property_id);
    } else {
        // Menambahkan properti baru
        $sql = "INSERT INTO properties (title, description, price, location, category, status, house_type, land_area, building_area, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsdssdd", $title, $description, $price, $location, $category, $status, $house_type, $land_area, $building_area);
        
    }

    if ($stmt->execute()) {
        // Jika ada gambar, proses penguploadannya
        if (!empty($_FILES['property_images']['name'][0])) {
            $property_id = isset($property_id) ? $property_id : $stmt->insert_id;
            $uploaded_images = [];
            $upload_dir = '../assets/images/';
            foreach ($_FILES['property_images']['tmp_name'] as $index => $tmp_name) {
                $image_name = time() . '_' . $_FILES['property_images']['name'][$index];
                if (move_uploaded_file($tmp_name, $upload_dir . $image_name)) {
                    $uploaded_images[] = $image_name;
                }
            }

            if (!empty($uploaded_images)) {
                // Update properti dengan nama gambar yang baru
                $images = implode(',', $uploaded_images);
                $sql_images = "UPDATE properties SET images = ? WHERE id = ?";
                $stmt_images = $conn->prepare($sql_images);
                $stmt_images->bind_param("si", $images, $property_id);
                $stmt_images->execute();
            }
        }
        // Redirect ke halaman properti setelah berhasil
        header('Location: add_property.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Simpan pesan dalam session
if (!empty($message)) {
    $_SESSION['message'] = $message;
    header("Location: add_property.php");
    exit();
}

// Proses untuk menghapus properti
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM properties WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Properti berhasil dihapus.";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }

    header("Location: add_property.php");
    exit();
}

// Ambil data properti jika ingin mengedit
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $sql = "SELECT * FROM properties WHERE id = $id";
    $result = $conn->query($sql);
    $property = $result->fetch_assoc();
}


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PT MITRA USAHA SYARIAH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100 top-0">
            <div class="container">
                <a class="navbar-brand" href="#"> <img src="../assets/images/logo.png" alt="Logo" class="me-2"
                        style="height: 40px;"> <span class="fs-5">PT MITRA USAHA SYARIAH</span></a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="view_contacts" class="nav-link">Ulasan</a></li>
                        <li class="nav-item"><a href="#tambah" class="nav-link">Tambah Properti</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container py-4 mt-3">
        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Daftar Properti -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center bg-dark text-white">
                        <h5>Daftar Properti</h5>
                    </div>
                    <div class="card-body">
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
        // Ambil semua properti dari database
        $result = $conn->query("SELECT id, title, house_type, land_area, building_area, price, location, status, category, images FROM properties ORDER BY created_at DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['house_type'] . "</td>";
            echo "<td>" . $row['land_area'] . "</td>"; // Menghilangkan "m²"
            echo "<td>" . $row['building_area'] . "</td>"; // Menghilangkan "m²"
            echo "<td>Rp " . number_format((int)$row['price'], 0, ',', '.') . "</td>";
            echo "<td>" . $row['location'] . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            
            // Menampilkan kategori dengan konversi
            switch ($row['category']) {
                case '0':
                    echo "<td>Jual</td>";
                    break;
                case '1':
                    echo "<td>Sewa</td>";
                    break;
                case '2':
                    echo "<td>Sold Out</td>";
                    break;
                case '3':
                    echo "<td>Take Over</td>";
                    break;
                default:
                    echo "<td>Unknown</td>";
                    break;
            }
                                                     
                                // Proses untuk menampilkan gambar
                                echo "<td>";
                                $images = explode(',', $row['images']); // Pisahkan nama gambar berdasarkan koma
                                if (count($images) > 0 && !empty($images[0])) {
                                    foreach ($images as $image) {
                                        echo "<img src='../assets/images/" . trim($image) . "' width='50' class='me-1' alt='Image'>";
                                    }
                                } else {
                                    echo "<img src='../assets/images/default.jpg' width='50' alt='No Image'>"; // Gambar default jika tidak ada gambar
                                }
                                echo "</td>";

                                // Tombol aksi
                                echo "<td>
                                        <a href='add_property.php?edit=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='add_property.php?delete=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                    </td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulir untuk Menambahkan atau Mengedit Properti -->
    <div class="row mt-5">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div id="tambah" class="card-header text-center bg-dark text-white">
                    <h5><?php echo isset($property) ? 'Edit Properti' : 'Tambah Properti'; ?></h5>
                </div>
                <div class="card-body">
                    <form action="add_property.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="property_id"
                            value="<?php echo isset($property) ? $property['id'] : ''; ?>">
                        <div class="mb-3">
                            <label for="property_title" class="form-label">Judul Properti</label>
                            <input type="text" id="property_title" name="property_title" class="form-control"
                                value="<?php echo isset($property) ? $property['title'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="property_status" class="form-label">Status Properti</label>
                            <select id="property_status" name="property_status" class="form-control" required>
                                <option value="" disabled <?php echo !isset($property) ? 'selected' : ''; ?>>Pilih
                                    Status</option>
                                <option value="SHM"
                                    <?php echo isset($property) && $property['status'] == 'SHM' ? 'selected' : ''; ?>>
                                    SHM</option>
                                <option value="SHGB"
                                    <?php echo isset($property) && $property['status'] == 'SHGB' ? 'selected' : ''; ?>>
                                    SHGB</option>
                                <option value="SHGU"
                                    <?php echo isset($property) && $property['status'] == 'SHGU' ? 'selected' : ''; ?>>
                                    SHGU</option>
                                <option value="SHP"
                                    <?php echo isset($property) && $property['status'] == 'SHP' ? 'selected' : ''; ?>>
                                    SHP</option>
                                <option value="SHSRS"
                                    <?php echo isset($property) && $property['status'] == 'SHSRS' ? 'selected' : ''; ?>>
                                    SHSRS</option>
                                <option value="Tanah Girik"
                                    <?php echo isset($property) && $property['status'] == 'Tanah Girik' ? 'selected' : ''; ?>>
                                    Tanah Girik</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="property_house_type" class="form-label">Tipe Rumah</label>
                            <input type="text" id="property_house_type" name="property_house_type" class="form-control"
                                value="<?php echo isset($property) ? $property['house_type'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="property_land_area" class="form-label">Luas Tanah</label>
                            <input type="number" id="property_land_area" name="property_land_area" class="form-control"
                                value="<?php echo isset($property) ? $property['land_area'] : ''; ?>" required step="1">
                        </div>

                        <div class="mb-3">
                            <label for="property_building_area" class="form-label">Luas Bangunan</label>
                            <input type="number" id="property_building_area" name="property_building_area"
                                class="form-control"
                                value="<?php echo isset($property) ? $property['building_area'] : ''; ?>" required
                                step="1">
                        </div>

                        <div class="mb-3">
                            <label for="property_price" class="form-label">Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="property_price" name="property_price" class="form-control"
                                    value="<?php echo isset($property) ? number_format($property['price'], 0, ',', '.') : ''; ?>"
                                    oninput="formatRupiah(this)" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="property_location" class="form-label">Lokasi</label>
                            <input type="text" id="property_location" name="property_location" class="form-control"
                                value="<?php echo isset($property) ? $property['location'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="property_description" class="form-label">Deskripsi Properti</label>
                            <textarea id="property_description" name="property_description" class="form-control"
                                rows="5"
                                required><?php echo isset($property) ? $property['description'] : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="property_category" class="form-label">Kategori Properti</label>
                            <select id="property_category" name="property_category" class="form-control" required>
                                <option value="0"
                                    <?php echo isset($property) && $property['category'] == 'jual' ? 'selected' : ''; ?>>
                                    Jual</option>
                                <option value="1"
                                    <?php echo isset($property) && $property['category'] == 'sewa' ? 'selected' : ''; ?>>
                                    Sewa</option>
                                <option value="2"
                                    <?php echo isset($property) && $property['category'] == 'sold_out' ? 'selected' : ''; ?>>
                                    Sold Out</option>
                                <option value="3"
                                    <?php echo isset($property) && $property['category'] == 'take_over' ? 'selected' : ''; ?>>
                                    Take Over</option>
                            </select>

                        </div>
                        <div class="mb-3">
                            <label for="property_images" class="form-label">Foto Properti (Bisa lebih dari satu)</label>
                            <input type="file" id="property_images" name="property_images[]" class="form-control"
                                multiple>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <?php echo isset($property) ? 'Perbarui Properti' : 'Tambah Properti'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2022 PT MITRA USAHA SYARIAH.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Fungsi untuk memformat angka menjadi format Rupiah
    function formatRupiah(input) {
        let value = input.value.replace(/\./g, '').replace(/[^0-9]/g, ''); // Hapus semua titik, hanya angka
        input.value = new Intl.NumberFormat('id-ID').format(value); // Format ulang
    }
    </script>
</body>

</html>