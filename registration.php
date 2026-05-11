<?php
session_start();

if(isset($_SESSION["user"])){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class = "container">
        <?php
        if(isset($_POST["submit"])){
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $repeatPassword = $_POST["repeat_password"];
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $error = array();
            if(empty($fullName) or empty($email) or empty($password) or empty($repeatPassword)){
                array_push($error, "All fields are required.");
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                array_push($error, "Invalid email format.");
            }
            if(strlen($password) < 8){
                array_push($error, "Password must be at least 8 characters long.");
            }
            if($password !== $repeatPassword){
                array_push($error, "Passwords do not match.");
            }
            require_once"database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($connection, $sql);
            $rowCount = mysqli_num_rows($result);
            if($rowCount > 0){
                array_push($error, "Email already exists!");
            }


            if(count($error) > 0){
                foreach($error as $err){
                    echo "<div class='alert alert-danger'>$err</div>";
                }
            } else { 
                require_once"database.php";
                $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($connection);
                $preparestmt= mysqli_stmt_prepare($stmt, $sql);
                if($preparestmt){
                    mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>Registration successful! Welcome, $fullName.</div>";
                } else {
                    die("Something went wrong.");   

            }
        }
        }
        ?>
        <form action="registration.php" method = "post">
            <div class = "form-group">
                <input type="text" class= "form-control" name = "fullname" placeholder = "Full Name:">
            </div>
            <div class = "form-group">
                <input type="email" class= "form-control"name = "email" placeholder = "Email:">
            </div>
            <div class = "form-group">
                <input type="password" class= "form-control" name = "password" placeholder = "Password:">
            </div>
            <div class = "form-group">
                <input type="password" class= "form-control" name = "repeat_password" placeholder = "Repeat Password:">
            </div>
            <div class = "form-btn">
                <input type="submit" value="Register" name = "submit" class = "btn btn-primary">
            </div>
        </form>
        <div>
            <p>Already registered? <a href="login.php">Login here</a></p>

        </div>

    </div>
</body>
</html>