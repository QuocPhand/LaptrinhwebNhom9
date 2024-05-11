function updateOrderStatus(orderId, status) {
    $.ajax({
        url: 'order_manage_update.php',
        method: 'POST',
        data: {
            order_id: orderId,
            status: status
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Có lỗi xảy ra khi cập nhật trạng thái đơn hàng.');
        }
    });
}
