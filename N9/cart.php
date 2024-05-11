<?php
session_start();
require_once("database.php");

$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $_SESSION['username'] = $row['name'];
}

function getProductDetails($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'], $_POST['product_id'], $_POST['quantity'])) {
    $productIdToUpdate = $_POST['product_id'];
    $newQuantity = max(1, intval($_POST['quantity']));
    if (isset($_SESSION['cart'][$productIdToUpdate])) {
        $_SESSION['cart'][$productIdToUpdate]['quantity'] = $newQuantity;
    }
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove'], $_POST['product_id'])) {
    $productIdToRemove = $_POST['product_id'];
    unset($_SESSION['cart'][$productIdToRemove]);
    header('Location: cart.php');
    exit;
}

$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $product = getProductDetails($item['product_id']);
        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
        $total += $product['price'] * $quantity;
    }
}
$deliveryCharges = 0;
$totalPayable = $total + $deliveryCharges;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order'], $_POST['name'], $_POST['note']) && !empty($_POST['name'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Bạn cần đăng nhập để đặt hàng');</script>";
        echo "<script>window.location = 'login.php';</script>";
        exit;
    }

    $name = $_POST['name'];
    $note = $_POST['note'];
    $userId = $_SESSION['user_id'];

    $insertOrder = $conn->prepare("INSERT INTO orders (user_id, name, user_note, order_date, status) VALUES (?, ?, ?, NOW(), 0)");
    $insertOrder->bind_param("iss", $userId, $name, $note);
    $success = $insertOrder->execute();

    if ($success) {
        $orderId = $conn->insert_id;

        foreach ($_SESSION['cart'] as $productId => $details) {
            $product = getProductDetails($productId);
            $price_product = $product['price'];
            $quantity = $details['quantity'];
            $price = $price_product * $quantity;

            $insertDetail = $conn->prepare("INSERT INTO order_details (order_id, product_id, price, num) VALUES (?, ?, ?, ?)");
            $insertDetail->bind_param("iiii", $orderId, $productId, $price, $quantity);
            $insertDetail->execute();
        }

        unset($_SESSION['cart']);
        echo "<script>alert('Đơn hàng của bạn đã được đặt thành công!');</script>";
        echo "<script>window.location = 'your_orders.php';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/header.css">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .orderbtn {
        margin-left: 220px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .note {
        padding: 20px;
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

    <div class="container-fluid">
        <div class="row px-5">
            <div class="col-md-7">
                <div class="shopping-cart">
                    <h6>Giỏ hàng của tôi</h6>
                    <hr>
                    <?php if (!empty($_SESSION['cart'])) : ?>
                        <?php foreach ($_SESSION['cart'] as $key => $value) : ?>
                            <?php $product = getProductDetails($value['product_id']); ?>
                            <?php if ($product) : ?>

                                <div class="border rounded" id="product_<?= $product['id']; ?>" data-price="<?= $product['price']; ?>">
                                    <div class="row bg-white">
                                        <div class="col-md-3 pl-0">
                                            <img name="image" src="<?= htmlspecialchars($product['img']); ?>" alt="<?= htmlspecialchars($product['title']); ?>" class="img-fluid">
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="pt-2"><?= htmlspecialchars($product['title']); ?></h5>
                                            <h5 class="pt-2">$<?= htmlspecialchars($product['price']); ?></h5>
                                            <form action="cart.php" method="post">
                                                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                                <input type="hidden" name="update">
                                                <input type="number" name="quantity" value="<?= $value['quantity']; ?>" min="1" max="99">
                                                <button type="submit" class="btn btn-warning mx-2 update-btn">Cập nhật</button>
                                            </form>
                                        </div>
                                        <div class="col-md-3 py-5">
                                            <form action="cart.php" method="post">
                                                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                                <button type="submit" class="btn btn-danger delete-btn" name="remove">Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>Giỏ hàng của bạn đang trống.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4 offset-md-1 border rounded mt-5 bg-white h-25">
                <div class="pt-4">
                    <h6>PRICE DETAILS</h6>
                    <hr>
                    <div class="row price-details">
                        <div class="col-md-6">
                            <?php
                            if (isset($_SESSION['cart'])) {
                                $count = count($_SESSION['cart']);
                                echo "<h6>Price($count items)</h6>";
                            } else {
                                echo "<h6>Price(0 items)</h6>";
                            }
                            ?>
                            <h6>Delivery Charges</h6>
                            <hr>
                            <h6>Amount Payable</h6>
                        </div>
                        <div class="col-md-6">
                            <h6>$<?php echo $total; ?></h6>
                            <h6 class="text-success">FREE</h6>
                            <hr>
                            <h6>$<?php echo $total; ?></h6>
                        </div>
                    </div>
                    <hr>
                    <h6>PAYMENT INFORMATION</h6>
                    <form method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <h6>Name</h6>
                                <input class="form-control" type="text" name="name" placeholder="Nhập tên của bạn" required aria-label="default input example">
                                <h6>Note</h6>
                                <input class="form-control note" type="text" name="note" placeholder="Thêm ghi chú cho đơn hàng" aria-label="default input example">
                            </div>
                        </div>
                        <button type="submit" name="order" class="btn btn-success orderbtn">Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/8b0381ba24.js"></script>

</body>

</html>