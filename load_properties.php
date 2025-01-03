<?php
include('db.php');

// Ambil kategori dan load_more dari parameter URL
$category = isset($_GET['category']) ? $_GET['category'] : 'new';
$loadMore = isset($_GET['load_more']) ? (int) $_GET['load_more'] : 0;
$limit = 6;
$offset = $loadMore * $limit;

// Query properti berdasarkan kategori
if ($category == 'best') {
    $sql = "SELECT * FROM properties WHERE is_best = 1 ORDER BY created_at DESC LIMIT ? OFFSET ?";
} else {
    $sql = "SELECT * FROM properties WHERE is_new = 1 ORDER BY created_at DESC LIMIT ? OFFSET ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$properties = [];
while ($row = $result->fetch_assoc()) {
    $properties[] = $row;
}

// Menentukan apakah masih ada properti yang bisa dimuat
$hasMore = count($properties) === $limit;

echo json_encode([
    'properties' => $properties,
    'has_more' => $hasMore
]);

$stmt->close();
$conn->close();
?>
