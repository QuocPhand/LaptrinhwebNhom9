<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = intval($_POST['status']);

    if ($status !== 0 && $status !== 1) {
        echo json_encode(['success' => false, 'message' => 'Giá trị trạng thái không hợp lệ.']);
        exit();
    }

    $update_query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $status, $order_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không có thay đổi trạng thái đơn hàng.']);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi chuẩn bị truy vấn SQL.']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không được cho phép.']);
?>
