<?php
    include("database.php");  

    if(isset($_GET['range'])) {     
        $range = mysqli_real_escape_string($conn, $_GET['range']);

        $query = "SELECT o.id AS order_id, o.name, o.order_date,
                            SUM(od.price * od.num) AS total
                    FROM orders o
                    JOIN order_details od ON o.id = od.order_id
                    WHERE o.status = 1 AND (DATEDIFF(CURDATE(), o.order_date) <= $range)
                    GROUP BY o.id, o.name, o.order_date;";

        $result = mysqli_query($conn, $query);

        if ($result) {
            $orders = array();

            while ($row = mysqli_fetch_assoc($result)) {
                $orders[] = $row;
            }

            echo json_encode($orders);
        } else {
            echo json_encode(array('error' => 'Failed to fetch data'));
        }
    } else {
        echo json_encode(array('error' => 'range parameter is missing'));
    }
?>