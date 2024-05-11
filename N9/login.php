<?php
session_start();
include 'database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<style>
    body  {
  background-image: url("images/bg.webp");
}
   h1 {
    position: relative;
    top: 0x; 
    left: 177px; 
   
}
    .image {
    position: absolute;
    right: 0;
    top: 0;
    width: 489px; 
    height: 640px;
    border-radius: 0px 62px 62px 0px; 
    object-fit: cover; 
}
        body {
    padding: 50px;
    box-sizing: border-box;
}
    select {
        
        border-radius: 7px; 
        padding: 7px; 
        border: 1px solid #ccc; 
        outline: none;

        width: 100%;
    }
    select option[name="choose"] {
     display: none;
    }

.container {
    background: white;
    border-radius: 62px;
    width: 1100px;
    height: 640px;
    margin: 0 auto;
    padding: 50px;
    position: relative;
    overflow: hidden;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
}


.form-group{
    width: 500px;
    padding: 20px;
}
.custom-select{
    width: 500px;
    padding: 15px;
}
.form-btn{
    position: relative;
    left: 200px;
}

    </style>
<body>
    <div class="container">
        <img class="image" src="images/im0.jpg" />
        <h1>Login</h1>
          
        <form action="login.php" method="post">
            
            <div class="form-group">
                <h5>Username</h5>
                <input type="text" class="form-control" name="fullname" placeholder="Username:">
            </div>
            <div class="form-group">
                <h5>Password</h5>
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-success" value="Login" name="submit">
            </div>
            <img class="image" src="images/im0.jpg" />
        </form>
        
    </div>
    <?php
if (isset($_POST["submit"])) {
    $username = $_POST['fullname'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE name = '$username' AND password='$password'";
    $result=mysqli_query($conn,$sql);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $_SESSION["user_id"] = $row['id'];
            if ($row['role_id'] == 0) {
				header("location:index.php");
            } elseif ($row['role_id'] == 1) {
                header("location:admin/admin.php");
            }
        } else {
            echo "Invalid username or password!";
        }
    } else {
        echo "Error executing SQL query: " . mysqli_error($conn);
    }
}
?>
</body>
</html>