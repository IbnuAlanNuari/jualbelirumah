// script.js

// Fungsi untuk menampilkan alert saat tombol di klik
document.addEventListener('DOMContentLoaded', function () {
    const alertButton = document.getElementById('alertButton');
    
    if (alertButton) {
        alertButton.addEventListener('click', function() {
            alert('Tombol berhasil diklik!');
        });
    }
});

// Menangani pencarian secara live (AJAX)
document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah form submit biasa

    let searchQuery = document.getElementById('searchQuery').value;
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "search_properties.php?query=" + searchQuery, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('propertyList').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
});

let loadMore = 0;  // Ini untuk kontrol halaman
let currentCategory = ''; // Untuk menyimpan kategori yang aktif (new / best)

function showProperties(category) {
    currentCategory = category;
    loadMore = 0; // Reset loadMore setiap kategori berubah
    loadProperties(); // Memuat properti berdasarkan kategori
}

function loadProperties() {
    let category = currentCategory;
    let url = `load_properties.php?category=${category}&load_more=${loadMore}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const propertyList = document.getElementById("propertyList");
            if (data.properties.length > 0) {
                data.properties.forEach(property => {
                    let images = property.images.split(',');
                    let propertyHTML = `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div id="carousel${property.id}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        ${images.map((img, index) => 
                                            `<button type="button" data-bs-target="#carousel${property.id}" data-bs-slide-to="${index}" class="${index === 0 ? 'active' : ''}" aria-current="true"></button>`
                                        ).join('')}
                                    </div>
                                    <div class="carousel-inner">
                                        ${images.map((img, index) => 
                                            `<div class="carousel-item ${index === 0 ? 'active' : ''}">
                                                <img src="assets/images/${img.trim()}" class="d-block w-100" alt="${property.title}">
                                            </div>`
                                        ).join('')}
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel${property.id}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carousel${property.id}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">${property.title}</h5>
                                    <p class="card-text">${property.description.substring(0, 100)}...</p>
                                    <p class="card-text">Harga: Rp ${property.price}</p>
                                    <a href="detail.php?id=${property.id}" class="btn btn-primary w-100">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    `;
                    propertyList.innerHTML += propertyHTML;
                });
            }

            // Perbarui tombol "Lihat Produk Lainnya"
            if (data.has_more) {
                document.getElementById('loadMoreContainer').style.display = 'block';
            } else {
                document.getElementById('loadMoreContainer').style.display = 'none';
            }
        });
}

function loadMoreProperties() {
    loadMore++;
    loadProperties();
}

document.addEventListener('DOMContentLoaded', function () {
    const resizeCards = () => {
        const cards = document.querySelectorAll('.col-lg-4.col-md-6.col-sm-6.col-12');
        const screenWidth = window.innerWidth;

        cards.forEach(card => {
            if (screenWidth <= 576) { // Untuk layar kecil (mobile)
                card.classList.remove('col-12'); // Hapus kelas full-width
                card.classList.add('col-6'); // Tambahkan kelas untuk setengah lebar
            } else {
                card.classList.remove('col-6'); // Hapus kelas untuk setengah lebar
                card.classList.add('col-12'); // Tambahkan kelas full-width untuk layar besar
            }
        });
    };

    // Jalankan fungsi saat halaman dimuat
    resizeCards();

    // Jalankan fungsi saat ukuran layar berubah
    window.addEventListener('resize', resizeCards);
});

