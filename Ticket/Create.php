<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Assignment Tool</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #0d1117;
            color: #c9d1d9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding-top: 20px;
            flex-direction: column;
        }

        .main-content {
            display: flex;
            flex-wrap: wrap;
            width: 95%;
            max-width: 1000px;
            border: 2px solid #30363d;
            border-radius: 8px;
            background-color: #161b22;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .container {
            flex: 2;
            padding: 20px;
            border-right: 2px solid #30363d;
        }

        .right-section {
            flex: 1;
            padding: 20px;
            max-width: 350px;
            background-color: #0d1117;
            border-radius: 8px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .input-group input,
        .input-group textarea,
        .right-section select {
            width:90%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #30363d;
            background-color: #0d1117;
            color: #c9d1d9;
            box-sizing: border-box;
        }

        .input-group textarea {
            resize: vertical;
        }

        .footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .footer button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: #238636;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .footer button:hover {
            background-color: #2ea043;
        }

        .right-section div span {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .right-section div a {
            color: #58a6ff;
            text-decoration: none;
        }

        .right-section div a:hover {
            text-decoration: underline;
        }

        .right-section select {
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }

            .container {
                border-right: none;
                border-bottom: 2px solid #30363d;
            }

            .right-section {
                margin-left: 0;
                margin-top: 0;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .container,
            .right-section {
                padding: 15px;
            }

            .footer button {
                width: 100%;
                margin-top: 15px;
                margin-right:20px;
                
            }
        }
    </style>
</head>
<body>

<?php
include __DIR__ . '/../config/config.php';
session_start();


echo '<form action="" method="post">
        <input type="submit" name="logout" value="Logout" style="background-color: #238636; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; position: absolute; top: 20px; right: 20px;">
      </form>';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$user_id = $_SESSION['user_id'];
$userquery = "SELECT * FROM users WHERE id=?";
$userresult = mysqli_prepare($conn, $userquery);
mysqli_stmt_bind_param($userresult, 'i', $user_id);
mysqli_stmt_execute($userresult);
$result = mysqli_stmt_get_result($userresult);
$row_r = mysqli_fetch_assoc($result);
$user_name = $row_r['name'];



if (isset($_POST['logout'])) {
    session_unset(); 
    session_destroy(); 
    header("Location: http://localhost:8000/app/Auth/Login.php"); 
    exit;
}


$userQuery = "SELECT name FROM users"; 
$userResult = mysqli_query($conn, $userQuery);

$users = [];
while ($row = mysqli_fetch_assoc($userResult)) {
    $users[] = $row['name'];
}


if (isset($_POST['submit'])) {
    $title = $_POST['title']; 
    $description = $_POST['description'];
    $assignees = $_POST['assignees'];
    $status = $_POST['status']; 
    $createdby = $_POST['createdby']; 
   
    $fileUpload = '';

    if (isset($_FILES['fileUpload'])) {
        $file_name = $_FILES['fileUpload']['name'];
        $filetmp = $_FILES['fileUpload']['tmp_name'];
        $folder = __DIR__ . '/../Images/' . $file_name;
      
        if (move_uploaded_file($filetmp, $folder)) {
            echo "<h2>File Uploaded successfully.</h2>";
            $fileUpload = $file_name;
        } else {
            echo "<h2>File not Uploaded !!</h2>";
        }
    }


    $query = "INSERT INTO createticket (title, description, fileUpload, assignees, status, createdby, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'ssssssi', $title, $description, $fileUpload, $assignees, $status, $createdby, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Ticket Created Successfully !!'); window.location.href='http://localhost:8000/app/Ticket/Index.php';</script>";
        } else {
            die('Error executing query: ' . mysqli_stmt_error($stmt));
        }
    } else {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
}
?>

<form class="main-content" action="" method="POST" enctype="multipart/form-data">
    <div class="container">
        <div class="input-group">
            <label for="title">Add a title</label>
            <input type="text" id="title" name="title" placeholder="Title" required/><br>
        </div>

        <div class="input-group">
            <label for="description">Add a description</label>
            <textarea 
                name="description"
                placeholder="Add your description here..."
                rows="14"
                required>
            </textarea>
            <div style="font-size: 12px; color: #8b949e; margin-top: 10px;">
                <input type="file" id="fileUpload" name="fileUpload">
            </div>
        </div>

        <div class="footer">
            <button type="submit" name="submit" value="Create new Ticket">Create new Ticket</button>
        </div>
    </div>

    <div class="right-section">
        <div>
            <span>Assignees</span>
            <select name="assignees"> 
                <option value="" disabled selected>Select Assignee</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo htmlspecialchars($user); ?>"><?php echo htmlspecialchars($user); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class='status'>
            <span>Status</span>
            <select name="status"> 
                <option value="" disabled selected>Select Status</option>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
                <option value="On Hold">On Hold</option>
            </select>
        </div>
       <div class='created-by'>
    <span>Created By</span>
    <select name="createdby">
        <option value="<?php echo htmlspecialchars($user_name); ?>" selected><?php echo htmlspecialchars($user_name); ?></option>
    </select>
</div>
    </div>
</form>

<a class="footer" href="Index.php">
    <button value="View">View Tickets</button>
</a>

</body>
</html>
