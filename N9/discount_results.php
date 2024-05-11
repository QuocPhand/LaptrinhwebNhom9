<?php
require 'database.php';

// Query to get products with discounts
$query = "SELECT * FROM product WHERE discount > 0";
$result = mysqli_query($conn, $query);

// Initialize an array to hold the product data
$products = array();

// Loop through the result set and collect the product data
while ($row = mysqli_fetch_assoc($result)) {
    $originalPrice = $row['price'];
    $discount = $row['discount'];
    $discountedPrice = $originalPrice - $discount;

    $products[] = array(
        'category_id' => $row['category_id'],
        'img' => $row['img'],
        'title' => $row['title'],
        'originalPrice' => $originalPrice,
        'discountedPrice' => $discountedPrice
    );
}

// Free the result set
mysqli_free_result($result);

// Close the database connection
mysqli_close($conn);

// Set the content type to JSON and echo the data
header('Content-Type: application/json');
echo json_encode($products);
?>
