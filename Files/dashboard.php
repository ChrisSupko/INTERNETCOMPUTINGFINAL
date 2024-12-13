<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Select an option below:</p>
    <ul>
        <li><a href="forum.php">Forums</a></li>
        <li><a href="messages.php">Messages</a></li>
    </ul>
    <a href="logout.php">Logout</a>
    <p>Looking for the admin pannel? <a href="admin_news_links.php">Admin Pannel</a></p>

    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
</body>
</html>
