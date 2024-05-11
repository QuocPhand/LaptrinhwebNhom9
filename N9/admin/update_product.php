<?php
include("database.php");
// Set the timezone to Asia/Ho_Chi_Minh
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (isset($_POST['product_id']) && isset($_POST['product_name']) && isset($_POST['product_price']) || isset($_POST['hide_product'])) {
        // Sanitize input data to prevent SQL injection
        $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
        $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
        $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
        $product_discount = isset($_POST['product_discount']) ? mysqli_real_escape_string($conn, $_POST['product_discount']) : null;
        $hide_product = isset($_POST['hide_product']) ? 1 : 0;
        // Get the current date and time
        $updated_at = date('Y-m-d H:i:s');

        // Set the deleted status based on the hide_product checkbox
        $product_deleted = $hide_product;

        // Handle image selection
        if (isset($_FILES['product_image']['name']) && $_FILES['product_image']['size'] > 0) {
            // Process image upload
            $target_dir = "../";
            $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
            $fileName = basename($_FILES["product_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["product_image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } 
            else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["product_image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } 
            else {
                // Upload the image
                if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                    // Update product information with new image and updated_at
                    $sql = "UPDATE product SET title='$product_name', price='$product_price', discount='$product_discount', updated_at='$updated_at', deleted = '$product_deleted', img ='$fileName' WHERE id='$product_id'";
                } 
                else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } 
        else {
            // Update product information without changing the image
            $sql = "UPDATE product SET title='$product_name', price='$product_price', discount='$product_discount', updated_at='$updated_at', deleted = '$product_deleted' WHERE id='$product_id'";
        }

        // Execute the SQL query
        if (mysqli_query($conn, $sql)) {
            echo "Product updated successfully.";
            header("Location: admin.php");
        } else {
            echo "Error updating product: " . mysqli_error($conn);
        }
    } else {
        echo "All fields are required.";
    }
}

// Close the database connection
mysqli_close($conn);
?>
