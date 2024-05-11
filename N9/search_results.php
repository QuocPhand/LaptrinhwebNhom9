<?php
require 'database.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (empty($query)) {
    http_response_code(400);
    echo json_encode(['error' => 'Query cannot be empty']);
    exit;
}

$query = mysqli_real_escape_string($conn, $query);

$sql = "SELECT id, title, price, img FROM product WHERE title LIKE '%$query%'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query error']);
    exit;
}

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

mysqli_free_result($result);

mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($products);
?>
