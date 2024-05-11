<!DOCTYPE html>
<html lang="en">
<?php
    include("database.php");  
    error_reporting(0);  
    session_start(); 
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/admin.js"></script>
</head>
<body>
    <div class="menu">
        <nav>
            <label class="logo mt-1">
                <img src="Logo.jpg" alt="Menu Image" class="menu-image">
            </label>

            <ul class="right-menu d-flex justify-content-end">
                <li class="admin-dropdown">
                    <div><i class="fas fa-user mr-2"></i>Admin <i class="fas fa-caret-down"></i></div>
                    <ul class="dropdown-content">
                        <li><i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất</li>
                    </ul>
                </li>
                <li class="function-dropdown-icon">
                    <i class="fas fa-bars" onclick="toggleFunctionDropdown()"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class = "function-dropdown" id = "functionDropdown">
        <ul>
            <li></li>
            <li><a href="admin.php?page_layout=add"><i class="fas fa-plus mr-2"></i>Thêm sản phẩm</a></li>
            <li><a href="admin.php?page_layout=delete"><i class="fas fa-trash mr-2"></i>Xóa sản phẩm</a></li>
            <li><a href="admin.php?page_layout=update"><i class="fas fa-edit mr-2"></i>Sửa sản phẩm</a></li>
            <li><a href="admin.php?page_layout=analytic"><i class="fas fa-chart-line mr-2"></i>Thống kê doanh thu</a></li>
            <li><a href="../index.php"><i class="fas fa-chart-line mr-2"></i>Trang chủ</a></li>
            <li></li>
        </ul>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="sidebar">
                <ul>
                    <li></li>
                    <li><a href="admin.php?page_layout=add"><i class="fas fa-plus mr-2"></i>Thêm sản phẩm</a></li>
                    <li><a href="admin.php?page_layout=delete"><i class="fas fa-trash mr-2"></i>Quản lý sản phẩm</a></li>
                    <li><a href="admin.php?page_layout=analytic"><i class="fas fa-chart-line mr-2"></i>Thống kê doanh thu</a></li>
                    <li><a href="admin.php?page_layout=order"><i class="fas fa-store-alt"></i> Quản lý đơn hàng </a></li>
                    <li><a href="../index.php"><i class="fas fa-chart-line mr-2"></i>Trang chủ</a></li>
                    <li></li>
                </ul>
            </div>
            
            <div class = "main-content d-flex justify-content-center">
                <div class="container">
                <?php 
                    $page_layout = isset($_GET['page_layout']) ? $_GET['page_layout'] : ''; 
                    switch($page_layout) {
                        case 'analytic':
                            include_once("analytic.php");
                            break;
                        case 'add':
                            include_once("add_product.php");
                            break;
                        case 'delete':
                            include_once("manage.php");
                            break;     
                        case 'order':
                            include_once("order_manage.php");
                            break;     
                        default:
                            break;
                    }

                ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
