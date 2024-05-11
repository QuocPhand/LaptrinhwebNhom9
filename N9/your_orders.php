<?php
session_start();
require_once("database.php");

// Kiểm tra nếu người dùng đã đăng nhập
if (empty($_SESSION["user_id"])) {
  echo "<script>alert('Bạn cần đăng nhập để xem các đơn đặt hàng.');</script>";
  echo "<script>window.location = 'login.php';</script>";
  exit;
}

// Lấy thông tin đơn hàng của người dùng
$userId = $_SESSION["user_id"];
$query = "SELECT orders.*, order_details.product_id, order_details.price, order_details.num, product.title AS product_name 
          FROM orders
          LEFT JOIN order_details ON orders.id = order_details.order_id
          LEFT JOIN product ON order_details.product_id = product.id
          WHERE orders.user_id = ? ORDER BY orders.order_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$orders = [];
while ($row = $result->fetch_assoc()) {
  $orders[$row['id']]['order_date'] = $row['order_date'];
  $orders[$row['id']]['user_note'] = $row['user_note'];
  $orders[$row['id']]['status'] = $row['status'];
  $orders[$row['id']]['details'][] = $row;
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="style.css">
  <title>Đơn Đặt Hàng Của Tôi</title>
</head>
<style>
  .container {
    margin-top: 100px !important;
  }

  .container h2 {
    margin-bottom: 20px;
  }

  body {
    background-image: url("images/bg.webp");
  }
</style>

<body class="bg-light">
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
          echo  '<li><a href="your_orders.php">Your Orders</a></li>';
          echo  '<li><a href="logout.php">Logout</a></li>';
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

  <div class="container mt-5">
    <h2>Đơn Đặt Hàng Của Tôi</h2>
    <?php foreach ($orders as $orderId => $order) : ?>
      <div class="card mb-3">
        <div class="card-header">
          Đơn Hàng ID: <?= $orderId ?> - Ngày: <?= date("d-m-Y H:i", strtotime($order['order_date'])) ?>
        </div>
        <div class="card-body">
          <h5 class="card-title">Ghi chú: <?= htmlspecialchars($order['user_note']) ?></h5>
          <h6 class="card-subtitle mb-2 text-muted">Trạng Thái: <?= ($order['status'] == 0) ? 'Đang xử lý' : 'Hoàn tất'; ?></h6>
          <table class="table">
            <thead>
              <tr>
                <th>Tên Sản Phẩm</th>
                <th>Số Lượng</th>
                <th>Giá</th>
                <th>Thành Tiền</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $totalAmount = 0;
              foreach ($order['details'] as $detail) :
                $totalAmount += $detail['price'];
              ?>
                <tr>
                  <td><?= htmlspecialchars($detail['product_name']) ?></td>
                  <td><?= $detail['num'] ?></td>
                  <td><?= number_format(($detail['price'] / $detail['num'])) ?></td>
                  <td><?= number_format($detail['price']) ?></td>
                </tr>
              <?php endforeach; ?>
              <tr>
                <td colspan="3" class="text-right"><strong>Tổng Tiền:</strong></td>
                <td><strong><?= number_format($totalAmount) ?></strong></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/8b0381ba24.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>

</html>