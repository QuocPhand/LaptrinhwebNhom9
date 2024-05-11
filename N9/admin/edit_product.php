<?php
function get_product_and_image_by_id($product_id){
    require_once("database.php");
    $product_and_image = array();

    // Query the database for the product information
    $product_query = "SELECT * FROM product WHERE id = $product_id";
    $product_result = mysqli_query($conn, $product_query);

    // Check the product query result
    if ($product_result && mysqli_num_rows($product_result) > 0) {
        $product = mysqli_fetch_assoc($product_result);
        $product_and_image['product'] = $product;
    } else {
        $product_and_image['product'] = false;
    }
    // Return the product and image information
    return $product_and_image;
}
?>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Edit Product</h1>
        <?php
        // Get the product information from the database
        $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $product = get_product_and_image_by_id($product_id);
        ?>
        <form action="update_product.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $product['product']['id']; ?>">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product']['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="product_price">Price:</label>
                <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" value="<?php echo htmlspecialchars($product['product']['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="product_discount">Discount:</label>
                <input type="number" class="form-control" id="product_discount" name="product_discount" value="<?php echo htmlspecialchars($product['product']['discount']); ?>">
            </div>
            <div class="form-group">
                <label for="current_image">Current Image:</label>
                <?php if ($product['product']['img']): ?>
                    <br>
                    <img src="../<?php echo $product['product']['img']; ?>" alt="Current Image" style="max-width: 200px; margin-top: 10px;">
                <?php else: ?>
                    <p>No current image available.</p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="product_image">New Image:</label>
                <input type="file" class="form-control-file" id="product_image" name="product_image">
                <small class="form-text text-muted">Leave blank to keep the current image.</small>
            </div>
            <div class="form-group">
                <label for="hide_product">Hide Product:</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="hide_product" name="hide_product" <?php if($product['product']['deleted']) echo "checked"; ?>>
                    <label class="form-check-label" for="hide_product">Hide this product</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-4">Update</button>
        </form>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBud7TlRbs/ic4AwGcFZOxg5DpPt8EgeUIgIwzjWfXQKWA3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
</body>
</html>
