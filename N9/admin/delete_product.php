<?php
// delete_product.php

include("database.php");

if(isset($_POST['productId'])) {
  $productId = $_POST['productId'];
  // If image deletion is successful, proceed to delete the product
  $sql = "DELETE FROM product WHERE id = $productId";
  if(mysqli_query($conn, $sql)) {
    header("Location: admin.php");
  }
  else {
      echo json_encode(array("error" => "Error deleting image from gallery"));
  }
}
?>
