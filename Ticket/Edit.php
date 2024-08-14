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
    flex-direction: column;
}

.wrapper {
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 1000px;
    padding: 20px;
    box-sizing: border-box;
    background-color: #161b22;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
    align-items: center;
}

.main-content {
    display: flex;
    flex-wrap: wrap;
    border: 2px solid #30363d;
    border-radius: 8px;
    overflow: hidden;
    background-color: #161b22;
    width: 100%; 
}

.container {
    flex: 2;
    padding: 20px;
    border-right: 2px solid #30363d;
}

.right-section {
    flex: 1;
    padding: 20px;
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
    width: 100%;
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
    }

    .input-group input,
    .input-group textarea,
    .right-section select {
        font-size: 14px;
    }

    .input-group label {
        font-size: 14px;
    }

    .footer button {
        font-size: 14px;
    }
}

</style>
</head>
<body>
<?php
session_start(); 


echo '<form action="" method="post">
        <input type="submit" name="logout" value="Logout" style="background-color: #238636; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; position: absolute; top: 20px; right: 20px;">
      </form>';

if (isset($_POST['logout'])) {
    session_unset(); 
    session_destroy(); 
    header("Location: http://localhost:8000/app/Auth/Login.php"); 
    exit;
}

include __DIR__ . '/../config/config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; 
$userQuery = "SELECT name FROM users WHERE id=?";
$stmt = mysqli_prepare($conn, $userQuery);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$userResult = mysqli_stmt_get_result($stmt);
$userRow = mysqli_fetch_assoc($userResult);
$user_name = $userRow['name']; 



$id = $_GET['id']; 
$Select = "SELECT * FROM createticket WHERE id=? ";
$stmt = mysqli_prepare($conn, $Select);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);

if (!$data) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_array($data);

if (!$row) {
    die("No data found for the given ID or you do not have permission to edit this ticket.");
}


$userListQuery = "SELECT name FROM users"; 
$userListResult = mysqli_query($conn, $userListQuery);

if (!$userListResult) {
    die("User query failed: " . mysqli_error($conn));
}

$users = [];
while ($userRow = mysqli_fetch_assoc($userListResult)) {
    $users[] = $userRow['name'];
}


if (isset($_POST['submit'])) {
    $title = $_POST['title']; 
    $description = $_POST['description'];
    $assignees = $_POST['assignees'];
    $status = $_POST['status']; 
    $createdby = $_POST['createdby']; 

    $fileUpload = $row['fileUpload'];


    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == UPLOAD_ERR_OK) {
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

    $update = "UPDATE createticket SET title=?, description=?, fileUpload=?, assignees=?, status=?, createdby=? WHERE id=? AND user_id=?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $update)) {
        mysqli_stmt_bind_param($stmt, 'ssssssii', $title, $description, $fileUpload, $assignees, $status, $createdby, $id, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Data updated successfully');
            window.location.href='http://localhost:8000/app/Ticket/Index.php';
            </script>";
        } else {
            echo "<script>alert('Error updating data: " . mysqli_stmt_error($stmt) . "');</script>";
        }
    } else {
        echo "<script>alert('Error preparing statement: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="wrapper">
<form class="main-content" action="" method="POST" enctype="multipart/form-data">
    <div class="container">
        <div class="input-group">
            <label for="title">Add a title</label>
            <input type="text" id="title" name="title" placeholder="Title" value="<?php echo htmlspecialchars($row['title']); ?>" required/><br>
        </div>

        <div class="input-group">
            <label for="description">Add a description</label>
            <textarea 
                name="description"
                placeholder="Add your description here..."
                rows="14"
                required
            ><?php echo htmlspecialchars($row['description']); ?></textarea>
            <div style="font-size: 12px; color: #8b949e; margin-top: 10px;">
                <input type="file" id="fileUpload" name="fileUpload">
                <?php if (!empty($row['fileUpload'])): ?>
                 <p>Current file: <?php echo htmlspecialchars($row['fileUpload']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer">
            <button type="submit" name="submit" value="Update Ticket">Update Ticket</button>
        </div>
    </div>

    <div class="right-section">
        <div>
            <span>Assignees</span>
            <select name="assignees">
                <option value="" disabled>Select Assignee</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo htmlspecialchars($user); ?>" <?php echo ($row['assignees'] == $user) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class='status'>
            <span>Status</span>
            <select name="status">
                <option value="" disabled>Select Status</option>
                <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="In Progress" <?php echo ($row['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                <option value="Completed" <?php echo ($row['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                <option value="On Hold" <?php echo ($row['status'] == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
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
</div>


</body>
</html>
