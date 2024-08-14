<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            background-color: #f4f4f4;
            margin: 0;
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
            border-radius: 8px;
            padding: 20px;
        }

        legend {
            font-size: 1.5em;
            padding: 0 10px;
            color: #333;
        }

        label {
            font-size: 1em;
            color: #333;
            margin-top: 10px;
        }

        input[type="email"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        p {
            text-align: center;
            margin-top: 15px;
            font-size: 1em;
            color: #333;
        }

        a {
            color: #28a745;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

      
        @media (max-width: 480px) {
            form {
                padding: 15px;
            }

            fieldset {
                padding: 15px;
            }

            legend {
                font-size: 1.2em;
            }

            input[type="email"],
            input[type="password"],
            input[type="submit"] {
                font-size: 0.9em;
            }

            p {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
<?php
include __DIR__ . '/../config/config.php';
session_start();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email; 
            echo "<script>alert('Logged In Successfully !!'); window.location.href='http://localhost:8000/app/Ticket/Welcome.php';</script>";
        } else {
            echo "<div>Password does not match</div>";
        }
    } else {
        echo "<div>Email does not exist</div>";
    }
}
?>
    <form action="" method="POST">
        <fieldset>
            <legend align="center">Login</legend>
            <label for="email">E-mail</label><br>
            <input type="email" id="email" name="email" required/><br>

            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required/><br>

            <input type="submit" name="submit" value="Sign In"/>

            <p>Don't have an account? <a href="Register.php">Sign Up</a></p>
        </fieldset>
    </form>
</body>
</html>
