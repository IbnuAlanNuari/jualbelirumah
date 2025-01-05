<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT MITRA USAHA SYARIAH</title>
    <link rel="icon" href="assets/images/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100 top-0">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- Brand di sisi kiri -->
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
                <!-- Menu Navbar di sisi kanan -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="index" class="nav-link">Home</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="new_property.php" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Property
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="new_property">Property Terbaru</a></li>
                                <li><a class="dropdown-item" href="best_property">Property Terbaik</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="#maps" class="nav-link">Lokasi Kami</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    <section class="contact-us py-5 mt-5">
        <div class="container">
            <h2 class="text-center mb-4">Kontak Kami</h2>

            <!-- Informasi Kontak -->
            <div class="row">
                <div class="col-md-6">
                    <h4>Hubungi Kami</h4>
                    <p class="contact-info">
                        <strong>Email:</strong> <a
                            href="mailto:ptmitrausahasyariah@gmail.com">ptmitrausahasyariah@gmail.com</a><br>
                        <strong>Telepon:</strong> <a href="tel:+6289665550003">089665550003</a><br>
                        <strong>Alamat:</strong> Link Ciore Kwiste RT/RW 004/003 Kelurahan
                        Gerogol Kecamatan Gerogol Kota Cilegon
                    </p>

                    <h4 class="mt-4">WhatsApp Kami</h4>
                    <p class="contact-info">
                        <a href="https://wa.me/+6289665550003" target="_blank">
                            Klik untuk Chat di WhatsApp <i class="bi bi-whatsapp"></i>
                        </a>
                    </p>

                    <!-- Ikon Sosial Media -->
                    <div class="social-icons mt-4">
                        <a href="https://www.instagram.com/yourprofile" target="_blank" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.facebook.com/yourprofile" target="_blank" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/yourprofile" target="_blank" title="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-6">
                    <h4>Formulir Kontak</h4>
                    <form action="submit_contact.php" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Kirim Pesan</button>
                    </form>
                </div>
            </div>

            <!-- Google Map Embed -->
            <div id="maps" class="row mt-5">
                <div class="col-12">
                    <h4 class="text-center">Lokasi Kami</h4>
                    <div class="embed-responsive embed-responsive-16by9">
                        <!-- Ganti URL dengan link embed peta Anda -->
                        <iframe class="embed-responsive-item"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0505686665347!2d106.0306005!3d-5.9810092!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e418e0ae883be05%3A0x56b8e6b9871c47b8!2sLink%20Ciore%20Kwiste%20RT%2FRW%20004%2F003%2C%20Kelurahan%20Gerogol%2C%20Kecamatan%20Gerogol%2C%20Kota%20Cilegon!5e0!3m2!1sen!2sid!4v1611073739701!5m2!1sen!2sid"
                            width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen=""
                            aria-hidden="false" tabindex="0">
                        </iframe>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2022 PT MITRA USAHA SYARIAH.</p>
    </footer>

    <script src="scripts/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Optional: Add Bootstrap Icons CDN for social icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>

</html>