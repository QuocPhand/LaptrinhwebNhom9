<?php
include("database.php");

if(isset($_POST['category'])) {
  $category = $_POST['category'];

  // Fetch products and their images based on category from the database
  $sql = "SELECT p.id, p.title, p.price, p.discount, p.updated_at, p.img, p.deleted
          FROM product p 
          WHERE p.category_id = $category";
  $result = mysqli_query($conn, $sql);

  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      // Check if the product is deleted
      $deleted_class = ($row['deleted'] == 1) ? 'deleted-product' : '';

      echo '<tr class="' . $deleted_class . '">' .
             '<td>' . $row['id'] . '</td>' .
             '<td>' . $row['title'] . '</td>' .
             '<td>' . $row['price'] . '</td>' .
             '<td>' . $row['discount'] . '</td>' .
             '<td>' . $row['updated_at'] . '</td>' .
             '<td><img src="../' . $row['img'] . '" alt="Thumbnail" style="width:100px;height:auto;"></td>' .
             '<td>' .
               '<button class="btn btn-sm btn-warning edit_product" data-product-id="' . $row['id'] . '"><i class="fas fa-edit"></i></button>' .
               '<button class="btn btn-sm btn-danger delete_product" data-product-id="' . $row['id'] . '"><i class="fas fa-trash"></i></button>' .
             '</td>' .
           '</tr>';
    }
  } else {
    echo '<tr><td colspan="5">No products found for this category</td></tr>';
  }
}
?>
