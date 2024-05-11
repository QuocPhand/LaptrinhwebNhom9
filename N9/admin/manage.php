<link rel="stylesheet" href="analytic.css">
<!-- Content goes here -->
<body>
  <div class="container mt-5" id="header">
    <h2 class="text-center mb-4">Admin Panel</h2>
    <!-- Category Selection -->
    <div class="mb-4">
      <h4>Select Category</h4>
      <select id="select" class="form-control category">
        <option value="-1">Category</option>
        <?php
          require("database.php");
          $sql = "SELECT * FROM category";
          $result = mysqli_query($conn, $sql);
          if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)){
        ?>
        <option value="<?php echo $row['id']; ?>"> <?php echo $row['name']; ?></option>
        <?php  
            }
          }
        ?>
      </select>
    </div>
    <!-- Product List -->
    <div id="productList">
      <h4>Product List</h4>
      <table class="table-bordered table table-hover text-center product">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Updated day</th>
            <th>Image</th>
          </tr>
        </thead>
        <tbody>
          <!-- Product rows will be dynamically added here -->
        </tbody>
      </table>
    </div>
    <!-- Add Product Button -->
    <button id="addProductBtn" class="btn btn-primary mt-3">Add Product</button>
    <!-- Add Category Button -->
    <button id="addCategoryBtn" class="btn btn-primary mt-3">Add Category</button>
  </div>
  <!-- Add a placeholder for the success message -->
  <div id="successMessage" class="alert alert-success" style="display: none;"></div>

  <script>
    $(document).ready(function(){
      // Function to open add_product.php page using AJAX
      function openAddProductPage() {
        $.ajax({
          url: 'add_product.php',
          type: 'GET',
          success: function(response) {
            $('#header').html(response);
          }
        });
      }

      // Function to open add_category.php page using AJAX
      function openAddCategoryPage() {
        $.ajax({
          url: 'add_category.php',
          type: 'GET',
          success: function(response) {
            $('#header').html(response);
          }
        });
      }

      // Handle click event for Add Product Button
      $('#addProductBtn').click(function() {
        openAddProductPage();
      });

      // Handle click event for Add Category Button
      $('#addCategoryBtn').click(function() {
        openAddCategoryPage();
      });

      // Function to fetch products from the database based on category
      function fetchProducts(category) {
        $.ajax({
          url: 'fetch_products.php',
          type: 'POST',
          data: { category: category },
          success: function(response) {
            $('#productList tbody').html(response);
          }
        });
      }

      $(".category").change(function(){
        var categoryId = $(this).val();
        if (categoryId != -1) // Retrieve the selected category ID
          fetchProducts(categoryId);
      });

      // Event delegation for delete_product button
      $('#productList').on('click', '.delete_product', function() {
        var productId = $(this).data("product-id");
        var confirmation = confirm("Bạn có muốn xóa sản phẩm này không ?");
        
        if (confirmation) {
          $.ajax({
            type: "POST",
            url: "delete_product.php",
            data: { productId: productId },
            dataType: "json", // Specify the data type as JSON
            success: function(response) {
              // Handle success response
              console.log(response);
              // Update the product list
              $('#productList tbody').html(response.html);
              // Display the success message
              $('#successMessage').html(response.message).fadeIn().delay(3000).fadeOut();
            },
            error: function(xhr, status, error) {
              // Handle error
              console.error(xhr.responseText);
            }
          });
        }
      });

      // Event delegation for edit_product button
      $('#productList').on('click', '.edit_product', function() {
          var productId = $(this).data("product-id");
          // Redirect to the edit_product.php page with the product ID in the URL
          window.location.href = 'edit_product.php?id=' + productId;
      });
    });
  </script>