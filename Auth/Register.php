<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction:column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        fieldset {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
        }

        legend {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #28a745;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        span {
            color: red;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            form {
                padding: 15px;
            }

            legend {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<?php 
include __DIR__ . '/../config/config.php';
     
$emailErr = $passwordlen = $passwordErr = $emailExit = '';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if(empty($name) || empty($email) || empty($password) || empty($confirm_password)){
        echo "<div>Please fill all the fields.</div>";
    } else {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $emailErr = 'Invalid email';
        }

        if(strlen($password) < 8){
            $passwordlen = 'Password must be at least 8 characters long';
        }

        if($password !== $confirm_password){
            $passwordErr = 'Passwords do not match';
        }
        
        $query="SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        $rowCount=mysqli_num_rows($result);
        if($rowCount>0){
            $emailExit='Email already exists!';
        } 

        if(empty($emailErr) && empty($passwordlen) && empty($passwordErr) && empty($emailExit)){
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $query);
            if($prepareStmt){
                mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $passwordhash);
                mysqli_stmt_execute($stmt);
                echo "<script>alert('You have Registered Successfully !!'); 
                window.location.href='http://localhost:8000/app/Auth/Login.php';
                </script>";
            } else {
                die('Something went wrong');
            }
        }
    }
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <fieldset>
        <legend align="center">Register</legend>
        <label for="name">Name</label><br>
        <input type="text" id="name" name="name" required/><br>

        <label for="email">E-mail</label><br>
        <input type="email" id="email" name="email" required/><br>
        <span><?php echo $emailExit;?></span><br>
        <span><?php echo $emailErr;?></span><br>

        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required/><br>
        <span><?php echo $passwordlen;?></span><br>

        <label for="confirm_password">Confirm Password</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required/><br>
        <span><?php echo $passwordErr;?></span><br>

        <input type="submit" name="submit" value="Sign Up"/>

        <p>Already have an account? <a href="Login.php">Sign In</a></p>
    </fieldset>
</form>
</body>
</html>
