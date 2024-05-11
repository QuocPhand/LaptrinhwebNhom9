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
    padding: 15px;
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
        <h1>Register</h1>
        
        <?php
            if (isset($_POST["submit"])) {
                $fullName = $_POST["fullname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $passwordRepeat = $_POST["repeat_password"];
                $createAt = date('Y-m-d H:i:s');
                $upgradeAt = date('Y-m-d H:i:s');
                $delete = 0;
                $phonenum = $_POST["phonenumber"];
                $role_id = 0; 
                $address = $_POST["address"];
                $errors = array();
                if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
                    array_push($errors,"All fields are required");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Email is not valid");
                }
                if ($password!==$passwordRepeat) {
                    array_push($errors,"Password does not match");
                }
                require_once "database.php";
                $sql = "SELECT * FROM user WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);
                if ($rowCount>0) {
                    array_push($errors,"Email already exists!");
                }
                if (count($errors)>0) {
                    foreach ($errors as  $error) {
                        echo "<div class='alert alert-warning'>$error</div>";
                }
                }else{
                    $insert = "INSERT INTO user VALUES('','$fullName','$email','$phonenum','$address','$password','$role_id','$createAt','$upgradeAt','$delete')";
                    $query= mysqli_query($conn,$insert);
                    if($query){
                        ?>
                        <script>
                            swal({
                                title: "Regerter Success!",
                                text: "You have successfully registered",
                                icon: "success",
                            });
                        </script>
                        <?php
                    }
                }
            }
        ?>  
        <form action="register.php" method="post">
            
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="phonenumber" placeholder="Phone Number:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="address" placeholder="Address:">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-success" value="Register" name="submit">
            </div>
            <p>Already have an account? <a href="login.php">login now</a></p>
            <img class="image" src="images/im0.jpg" />
        </form>
        
    </div>
    
</body>
</html>