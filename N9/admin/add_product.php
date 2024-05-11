<?php
    include("database.php");
    // Set the timezone to Asia/Ho_Chi_Minh
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $error = '';
    $name = '';
    $price = '';
    $discount = null;
    $created_at = date('Y-m-d H:i:s');
    $deleted = 0;
    $categoryid = 0;

    if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['category'])) {
        $name =  $_POST['name'];
        $price = $_POST['price']; 
        $categoryid = $_POST['category'];
        $discount = isset($_POST['discount']) ? $_POST['discount'] : null;
        
        if (empty($name)) {
            $error = 'Hãy nhập tên sản phẩm';
        } 
        else if (intval($price) <= 0) {
            $error = 'Giá của sản phẩm không hợp lệ';
        } 
        else if ($_FILES['image']['error'] != UPLOAD_ERR_OK) {
            $error = 'Vui lòng upload ảnh của sản phẩm';
        } 
        else {
            $fileName = $_FILES['image']['name'];
            $fileTmpName = $_FILES['image']['tmp_name'];
            $fileDestination = '../images/' . $fileName;
            $fileName = 'images/' . $fileName;

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $sql = "INSERT INTO product (category_id, title, price, discount, created_at, updated_at, deleted, img) 
                        VALUES ($categoryid, '$name', '$price', '$discount', '$created_at', NULL, $deleted, '$fileName')";

                if (mysqli_query($conn, $sql)) {
                    $productId = mysqli_insert_id($conn);
                    header("Location: admin.php");
                } 
                else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    exit;
                }
            } 
            else {
                $error = "Error uploading file";
            }
            exit;
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thêm sản phẩm mới</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .bg {
            background: #eceb7b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 border rounded my-5 p-4  mx-3">
                <p class="mb-5"><a href="admin.php">Quay lại</a></p>
                <h3 class="text-center text-secondary mt-2 mb-3 mb-3">Thêm sản phẩm mới</h3>
                <form method="post" action="add_product.php" novalidate enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="name">Tên sản phẩm</label>
                        <input value="<?= $name?>" name="name" required class="form-control" type="text" placeholder="Tên sản phẩm" id="name">
                    </div>
                    <div class="form-group">
                        <label for="price">Giá bán</label>
                        <input value="<?= $price?>" name="price" required class="form-control" type="number" placeholder="Giá bán" id="price">
                    </div>
                    <div class="form-group">
                        <label for="discount">Giảm giá</label>
                        <input value="<?= $discount?>" name="discount" required class="form-control" type="number" placeholder="Giảm giá" id="discount">
                    </div>
                    <div class="form-group">
                        <label for="discount">Danh mục sảm phẩm</label>
                        <label for="category">Danh mục sản phẩm</label>
                        <select name="category" id="category" class="form-control category">
                            <option value="-1">Chọn danh mục</option>
                            <?php
                                require("database.php");
                                $sql = "SELECT * FROM category";
                                $result = mysqli_query($conn, $sql);
                                if(mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){
                                        echo "<option value='".$row['id']."'>".$row['name']."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input name="image" type="file" class="custom-file-input" id="customFile" accept="image/gif, image/jpeg, image/png, image/bmp">
                            <label class="custom-file-label" for="customFile">Ảnh minh họa</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php
                            if (!empty($error)) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                        ?>
                        <button type="submit" class="btn btn-primary px-5 mr-2" name = "submit">Thêm</button>
                    </div>
                </form>

            </div>
        </div>

    </div>
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
</body>
</html>

