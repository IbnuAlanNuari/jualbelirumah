<?php
include '../db.php'; // Koneksi ke database

// Tentukan jumlah pesan per halaman
$messages_per_page = 5;

// Tentukan halaman saat ini, default ke halaman 1 jika tidak ada
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start_from = ($page - 1) * $messages_per_page;

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

// Ambil semua pesan dari database dengan pagination
$sql = "SELECT * FROM contact_form ORDER BY date_sent DESC LIMIT $start_from, $messages_per_page";
$result = $conn->query($sql);

// Ambil total jumlah pesan untuk menghitung total halaman
$total_sql = "SELECT COUNT(*) FROM contact_form";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_row()[0];
$total_pages = ceil($total_rows / $messages_per_page);
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
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="../assets/images/logo.png" alt="Logo" class="me-2" style="height: 40px;">
                    <span class="fs-5 d-none d-md-inline">PT MITRA USAHA SYARIAH</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="add_property.php" class="nav-link">Tambah Properti</a></li>
                        <li class="nav-item"><a href="table.php" class="nav-link">Tabel</a></li>
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

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="view_contacts.php?page=1" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'><a class='page-link' href='view_contacts.php?page=$i'>$i</a></li>";
                }
                ?>
                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="view_contacts.php?page=<?php echo $total_pages; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2022 PT MITRA USAHA SYARIAH.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
