<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Issues</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: Arial, sans-serif;
        }

        .welcome-container {
            background-color: #161b22;
            border-radius: 8px;
            padding: 40px; 
            text-align: center;
            display: flex;
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            width: 70%; 
            height: 350px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .welcome-container h1 {
            font-size: 30px;
            margin-bottom: 10px;
        }          

        .welcome-container p {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .welcome-container a {
            color: #58a6ff;
            text-decoration: none;
        }

        .welcome-container a:hover {
            text-decoration: underline;
        }

        .welcome-container button,
        .welcome-container input[type="submit"] {
            background-color: #2f8b2f; 
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
            margin:6px;
            transition: background-color 0.3s ease;
        }

        .welcome-container form {
            display: inline;
        }
    </style>
</head>
<body>
<?php
session_start(); 

if (!isset($_SESSION['email'])) {
    header("Location: http://localhost:8000/app/Auth/Login.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_unset(); 
    session_destroy(); 
    header("Location: http://localhost:8000/app/Auth/Login.php"); 
    exit;
}
?>

<div class="welcome-container">
        <h1>Welcome <?php echo htmlspecialchars($_SESSION['email']); ?> !!</h1>
        <p>Tickets are used to track todos, bugs, feature requests, and more. As tickets are created, they'll appear here in a searchable and filterable list. To get started, you should <a href="Create.php">create a Ticket</a>.</p>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="submit" name="logout" value="Logout"/>
        </form>
        <a href="Index.php">
            <button>View</button>
        </a>
    </div>
</body>
</html>
