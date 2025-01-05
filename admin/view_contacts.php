<?php
include '../db.php'; // Koneksi ke database

// Cek apakah ada permintaan untuk menghapus pesan
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // Mengambil ID dari URL dan mengkonversinya menjadi integer
    $delete_sql = "DELETE FROM contact_form WHERE id = $id";

    if ($conn->query($delete_sql) === TRUE) {
        // Jika berhasil menghapus, redirect ke halaman yang sama
        header("Location: view_contacts.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Ambil semua pesan dari database
$sql = "SELECT * FROM contact_form ORDER BY date_sent DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PT MITRA USAHA SYARIAH</title>
    <link rel="icon" href="../assets/images/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100 top-0">
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
                        <li class="nav-item"><a href="add_property.php" class="nav-link">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="container py-5 mt-5">
        <h2>Pesan Kontak</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . nl2br($row['message']) . "</td>";
                    echo "<td>" . $row['date_sent'] . "</td>";
                    echo "<td>
                                        <a href='view_contacts.php?delete=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pesan ini?\");'>Delete</a>
                                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Tidak ada pesan</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2022 PT MITRA USAHA SYARIAH.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>