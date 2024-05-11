<?php
require 'database.php';

$query = "SELECT orders.id, orders.user_id, orders.name AS order_name, orders.user_note, orders.order_date, orders.status, user.name AS user_name
          FROM orders
          JOIN user ON orders.user_id = user.id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Lỗi truy vấn cơ sở dữ liệu: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quản lý đơn hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Quản lý đơn hàng</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID đơn hàng</th>
                    <th>ID người dùng</th>
                    <th>Tên khách hàng</th>
                    <th>Tên đơn hàng</th>
                    <th>Ghi chú</th>
                    <th>Ngày đặt hàng</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo $order['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_note']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td>
                            <select name="status" class="form-control" onchange="updateOrderStatus(<?php echo $order['id']; ?>, this.value)">
                                <option value="0" <?php echo $order['status'] == 0 ? 'selected' : ''; ?>>Đang xử lý</option>
                                <option value="1" <?php echo $order['status'] == 1 ? 'selected' : ''; ?>>Hoàn tất</option>
                            </select>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/order_manage.js"></script>
</body>
</html>
<?php
mysqli_close($conn);
?>
