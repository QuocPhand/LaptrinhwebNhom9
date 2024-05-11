<?php
session_start();
require_once 'database.php';


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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FastFood</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/header.css" />
    <link rel="stylesheet" href="style.css">
    <!-- JS -->
    <script src="jquery-3.7.1.min.js"></script> 
</head>
<style>
</style>

<body>
    <!-- Menu -->
    <div class="menu">
        <nav>
            <label class="logo">
                <img src="images/Logo.jpg" alt="Menu Image" class="menu-image">
            </label>

            <ul>
                <li><a href="index.php" class="active">Trang Chủ</a></li>
                <li><a href="#order">Đặt Hàng</a></li>
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
                <li><a href="./index.php?page_layout=search" class="active"><i class="fa-solid fa-magnifying-glass"></i></a></li>
            </ul>
        </nav>
    </div>
    <?php 
        $page_layout = isset($_GET['page_layout']) ? $_GET['page_layout'] : ''; 
        switch($page_layout) {
            case 'menu':
                include_once("menu.php");
                break;
            case "info":
                include_once("info.php");
                break;
            case "search":
                include_once("search.php");
                break;
            case "discount":
                include_once("discount.php");
                break;    
            default:
                include_once("home.php");
                break;
        }
    ?>
    <!-- End Menu -->
    <!-- End Hero Section -->
    <!-- Order -->
    <div class="section" id="order">
        <div class="txt">
            <h1>Đặt Hàng</h1>
        </div>

        <div class="container flex">
            <div class="box" id="burger">
                <img src="images/bg.jpg" alt="">
                <h3>Burger</h3>
                <p>Burger nướng thơm lừng với lớp thịt bò chất lượng, kẹp giữa hai miếng bánh mềm mại.</p>
            </div>

            <div class="box active" id="drink">
                <img src="images/nuoc.png" alt="">
                <h3>Drink</h3>
                <p>Nước uống luôn tươi mát, phong phú đa dạng từ cổ điển đến những món mới mẻ độc đáo.</p>
            </div>

            <div class="box" id="abv">
                <img src="images/ga.png" alt="">
                <h3>Chicken</h3>
                <p>Gà rán giòn tan, thấm đẫm gia vị, và luôn được phục vụ nóng hổi, tạo nên hương vị khó quên.</p>
            </div>
        </div>
        <!-- End How It Works -->

        <!-- Footer -->
        <div class="footer">
            <div class="container flex">
                <div class="footer-about">
                    <h2>Về Chúng Tôi</h2>
                    <p>
                        FastFeast là chuỗi nhà hàng thức ăn nhanh chất lượng cao, cam kết phục vụ món ăn ngon miệng
                        và nhanh chóng từ năm 2024. Chúng tôi tập trung vào nguyên liệu tươi và bền vững, mang đến
                        cho bạn trải nghiệm ẩm thực đích thực và thân thiện với cộng đồng qua mỗi bữa ăn.
                    </p>
                </div>

                <div class="footer-category">
                    <h2>Menu</h2>

                    <ul>
                        <li><a href="dishes.php?category=1">Burger</a></li>
                        <li><a href="dishes.php?category=2">Nước Uống</a></li>
                        <li><a href="dishes.php?category=3">Gà</a></li>
                    </ul>
                </div>

                <div class="quick-links">
                    <h2>Thông Tin</h2>

                    <ul>
                    <li><a href="./index.php?page_layout=info" class="active">Câu Chuyện</li>
                    <li><a href="./index.php?page_layout=discount">Khuyến Mãi</li>
                    <li><a href="https://www.youtube.com/@MrBeast">Youtube</li>
                    <li><a href="https://www.facebook.com/tonducthanguniversity">Facebook</li>
                </ul>
                </div>
            </div>
        </div>
        <!-- End Footer -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/8b0381ba24.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            document.querySelectorAll('.box').forEach(box => {
                box.addEventListener('click', function() {

                    const boxId = this.id;
                    if (boxId === 'burger') {
                        window.location.href = 'dishes.php?category=1';
                    } else if (boxId === 'drink') {
                        window.location.href = 'dishes.php?category=2';
                    } else if (boxId === 'abv') {
                        window.location.href = 'dishes.php?category=3';
                    }
                });
            });

            document.querySelector('a[href="#order"]').addEventListener('click', function(event) {
                event.preventDefault();
                var targetElement = document.getElementById('order');
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        </script>
</body>

</html>