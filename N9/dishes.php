<?php
session_start();
require_once 'database.php';

$category_id = isset($_GET['category']) ? (int) $_GET['category'] : 1;
$sql = "SELECT * FROM product WHERE category_id = $category_id and deleted = 0";
$all_product = $conn->query($sql);

if (isset($_POST['add'])) {
  $product_id = $_POST['product_id'];
  $product_quantity = 1;
  if (isset($_SESSION['cart'])) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
      echo "<script>alert('Product is already added in the cart')</script>";
      echo "<script>window.location='dishes.php'</script>";
    } else {
      $_SESSION['cart'][$product_id] = ['product_id' => $product_id, 'quantity' => $product_quantity];
    }
  } else {
    $_SESSION['cart'] = [$product_id => ['product_id' => $product_id, 'quantity' => $product_quantity]];
  }
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
  $_SESSION['username'] = $row['name'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dishes</title>
  <link rel="stylesheet" href="css/header.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
</head>
<style>
  body {
    background-image: url("images/bg.webp");
  }
</style>

<body>
  <div class="menu">
    <nav>
      <label class="logo">
        <img src="images/Logo.jpg" alt="Menu Image" class="menu-image">
      </label>

      <ul>
        <li><a href="index.php" class="active">Trang Chủ</a></li>
        <li><a href="index.php#order">Đặt Hàng</a></li>
        <?php
        if (!empty($_SESSION["user_id"])) {
          echo '<li><a href="your_orders.php">Your Orders</a></li>';
          echo '<li><a href="logout.php">Logout</a></li>';
        } else {
          echo '<li><a href="login.php">Login</a></li>
                  <li><a href="register.php">Register</a></li>';
        }
        ?>
        <li>
          <a href="cart.php" class="text-decoration-none text-dark position-relative">
            <i class="fas fa-shopping-cart"></i>
            <div class="position-absolute rounded-circle cart">
              <?php
              if (isset($_SESSION['cart'])) {
                $count = count($_SESSION['cart']);
                echo '<span id="cart_count">' . $count . '</span>';
              } else {
                echo '<span id="cart_count">0</span>';
              }
              ?>
            </div>
          </a>
        </li>
        <li>
          <a href="" class="text-decoration-none text-dark">
            <i class="fa-solid fa-user icon-unchanged"></i>
            <?php if (!empty($_SESSION['username'])) : ?>
              <?= htmlspecialchars($_SESSION['username']); ?>
            <?php endif; ?>
          </a>
        </li>
      </ul>
    </nav>
  </div>
  <main>
    <?php while ($row = mysqli_fetch_assoc($all_product)) : ?>
      <form method="POST" action="" class="product-form">
        <div class="image">
          <img style="width: 250px; height: 250px; border-radius: 10px"src="<?php echo htmlspecialchars($row["img"]); ?>" alt="">
        </div>
        <div class="caption">
          <p class="product_name"><?php echo htmlspecialchars($row["title"]); ?></p>
          <p class="price"><b>$<?php echo htmlspecialchars($row["price"]); ?></b></p>
          <p class="discount"><b><del>$<?php echo htmlspecialchars($row["discount"]); ?></del></b></p>
        </div>
        <button type="submit" name="add">Add to cart</button>
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['id']); ?>">
      </form>
    <?php endwhile; ?>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/8b0381ba24.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>

</html>