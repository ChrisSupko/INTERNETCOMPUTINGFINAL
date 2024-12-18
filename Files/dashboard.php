<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<style>
    body{
        margin: 1;
        padding: 2;
        font-family: 'Arial', sans-serif;
        background-color: #f7f9fc;
        color: #333;
        line-height: 1.6;
        background-color: black;
    }
    .title{
        display:inline-flex;
        background-color:#333;
        padding-left: 5px;
        padding-right: 5px;
        color:white;
        text-decoration: solid;
        border: 2px solid white;
    }
    .logout{
        display:inline-flex;
        text-align:center;
        padding: 2px;
        font-size:medium;
    }
    .logout a{
        color:white;
    }
    .options{
        display:inline-flex;
        flex-direction: column;
        text-align:left;
        padding: 20px;
        font-size: larger;
        background-color:#333;
        border: 2px solid white;
    }
    .websiteName{
        flex-direction: column;
        background-color:navy;
        color:white;
    }
    .button-link {
        display: inline-block;
        padding: 10px 20px;
        margin: 5px;
        color: white;
        background-color: blue;
        text-decoration: none;
        border-radius: 5px;
        border: none;
        text-align: center;
    }
    .button-link:hover{
        background-color:grey;
    }
    
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <div class="websiteName">
        <center><h1>Sekuri Talks</h1></center>
    </div>
</head>
<body>
    <div class="title">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    </div>
    <h2 style="color:white; text-decoration:underline;">Select an option below:</h2>
    <ul>
        <div class = "options">
            <a class="button-link" href="forum.php">Forums</a>
            <a class="button-link" href="messages.php">Messages</a>
        </div>
    </ul>
    <div class="logout">
        <a class="button-link" href="logout.php">Log out</a>
    </div>
    <div>    <p>Looking for the admin pannel? <a href="admin_news_links.php">Admin Pannel</a></p>
    </div>
</body>
</html>
