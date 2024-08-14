<?php
session_start(); 
include __DIR__ . '/../config/config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_email = $_SESSION['email'];
$userquery = "SELECT * FROM users WHERE email=?";
$stmt = mysqli_prepare($conn, $userquery);
mysqli_stmt_bind_param($stmt, 's', $user_email);
mysqli_stmt_execute($stmt);
$userresult = mysqli_stmt_get_result($stmt);
$row_r = mysqli_fetch_array($userresult);
$user_id = $row_r['id']; 
$user_name = $row_r['name']; 



if (isset($_POST['logout'])) {
    session_unset(); 
    session_destroy(); 
    header("Location: http://localhost:8000/app/Auth/Login.php"); 
    exit;
}


$query = "SELECT * FROM createticket WHERE user_id=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);

if (!$data) {
    die("Query failed: " . mysqli_error($conn));
}

$result = mysqli_num_rows($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Assignment Tool</title>
    <style>
   body {
    background-color: #181818;
    color: #fff;
    font-family: sans-serif;
    margin: 0;
    padding: 0;
}

.top-bar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #282c34;
    padding: 10px;
    border-bottom: 1px solid #ccc;
    display: flex;
    justify-content: space-around;
    align-items: center;
    box-sizing: border-box;
    flex-wrap: wrap; 
}

.top-bar span {
    font-size: 18px;
    margin-right: 20px;
}

.top-bar button {
    background-color: #28a745;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    margin: 4px;
}

.top-bar button:hover {
    background-color: #218838;
}

.top-bar form input {
    background-color: #2f8b2f; 
    color: #ffffff;
    border-radius: 5px;
    padding-top: 3px;
    cursor: pointer;
}

.main-container {
    display: flex;
    padding: 20px;
    justify-content: center;
    margin-top: 70px; /* Adjusted for fixed top-bar */
}

.issue-container {
    width: 70%;
    background-color: #282c34;
    border-radius: 8px;
    padding: 20px;
    margin-right: 20px;
}

.issue-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border: solid 1px #ccc;
    padding: 8px;
    border-radius: 8px;
}

.title-status {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    margin: 10px;
    width: 40%;
}

.title-status span {
    margin: 0;
    font-size: 18px;
}

.title-status .status {
    font-size: 18px;
    margin-left: 10px;
}

.actions {
    display: flex;
    width: 60%;
    justify-content: flex-end;
}

.actions button {
    background-color: #28a745;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    margin-left: 10px;
    cursor: pointer;
}

.actions button:hover {
    background-color: #218838;
}

.issue-description {
    border: solid 1px #ccc;
    border-radius: 8px;
    padding: 30px;
    margin-top: 20px;
}

.issue-description .description-text {
    font-size: 16px;
    line-height: 1.5;
}

.name {
    padding: 10px;
}

@media (max-width: 1024px) {
    /* Tablet screens */
    .main-container {
        flex-direction: column;
        align-items: center;
        padding: 15px;
        margin-top: 80px;
    }

    .issue-container {
        width: 90%;
        margin-right: 0;
        padding: 15px;
    }

    .issue-header {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px;
    }

    .title-status, .actions {
        width: 100%;
        margin: 10px 0;
    }

    .title-status span, .actions button {
        font-size: 16px;
    }

    .issue-description {
        padding: 25px;
    }

    .issue-description .description-text {
        font-size: 15px;
    }
}

@media (max-width: 768px) {
    
    .top-bar {
        flex-direction: row;
        align-items: center;
        padding: 15px;
    }

    .top-bar span, .top-bar button, .top-bar form input {
        font-size: 14px;
        margin: 5px 0;
        width: 100%; 
        text-align: center;
    }

    .top-bar button {
        padding: 10px 20px;
        margin: 0;
    }

    .main-container {
        flex-direction: column;
        align-items: center;
        padding: 10px;
       
    }

    .issue-container {
        width: 95%;
        margin-right: 0;
        padding: 15px;
    }

    .issue-header {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px;
    }

    .title-status, .actions {
        width: 100%;
        margin: 10px 0;
    }

    .title-status span, .actions button {
        font-size: 14px;
    }

    .issue-description {
        padding: 20px;
    }

    .issue-description .description-text {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .top-bar {
        padding: 10px;
    }

    .top-bar span, .top-bar button {
        font-size: 14px;
    }

    .top-bar button {
        width: 100%;
        margin-top: 5px;
    }

    .main-container {
        flex-direction: column;
        align-items: center;
        padding: 10px;
        margin-top: 100px;
    }

    .issue-container {
        width: 100%;
        padding: 15px;
    }

    .issue-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .title-status, .actions {
        width: 100%;
        margin: 5px 0;
    }

    .issue-description {
        padding: 20px;
    }

    .issue-description .description-text {
        font-size: 14px;
    }

    .issue-description img {
        width: 100%;
        height: auto;
    }
}


    </style>
</head>
<body>

<div class="top-bar">
    <?php
        echo "<span>Tickets: " . $result . "</span>";
    ?>
    <a href="Create.php">
        <button>Create new Ticket</button>
    </a>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="submit" name="logout" value="Logout">
    </form>
</div>

<?php
if ($result) {
    while ($row = mysqli_fetch_array($data)) {
?>
        <div class="main-container">
            <div class="issue-container">
                <div class="issue-header">
                    <div class="title-status">
                        <span><?php echo $row['title']; ?></span>
                        <span class="status"><?php echo $row['status']; ?></span>
                    </div>
                    <div class="actions">
                         <span class="name">Assignees: <?php echo $row['assignees']; ?></span>
                         <span class="name">Created By: <?php echo $row['createdby']; ?></span>
                        <a href="Edit.php?id=<?php echo $row['id']; ?>"><button>Edit</button></a>
                        <a href="Delete.php?id=<?php echo $row['id']; ?>"><button>Delete</button></a>
                    </div>
                </div>
                <div class="issue-description">
                    <div class="description-text"><?php echo htmlspecialchars($row['description']); ?></div>
                    <?php 
                        if (!empty($row['fileUpload'])) {
                    ?>
                        <img src="http://localhost:8000/app/Images/<?php echo htmlspecialchars($row['fileUpload']); ?>" alt="Ticket Image" style="width: 70%; height: auto;">
                    <?php
                    } else {
                    ?>
                        <p>No file uploaded.</p>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
<?php
    }
}
?>

</body>
</html>
