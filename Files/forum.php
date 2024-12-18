<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forum_name'], $_POST['forum_description'])) {
    $forum_name = trim($_POST['forum_name']);
    $forum_description = trim($_POST['forum_description']);

    if (strlen($forum_name) < 3) {
        $error = "Forum name must be at least 3 characters long.";
    } elseif (strlen($forum_description) < 5) {
        $error = "Forum description must be at least 5 characters long.";
    } else {
        // Insert the new forum into the database
        $stmt = $pdo->prepare("INSERT INTO forums (forum_name, forum_description) VALUES (:forum_name, :forum_description)");
        $stmt->execute(['forum_name' => $forum_name, 'forum_description' => $forum_description]);

        // Redirect to prevent form resubmission
        header('Location: forum.php');
        exit;
    }
}

$forums = $pdo->query("SELECT * FROM forums ORDER BY created_at ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
    body {
        margin: 1;
        padding: 2;
        font-family: 'Arial', sans-serif;
        background-color: black;
        color: white;
        line-height: 1.6;
    }

    .title {
        display: inline-flex;
        background-color: #333;
        padding-left: 5px;
        padding-right: 5px;
        color: white;
        text-decoration: solid;
        border: 2px solid white;
    }

    .logout {
        display: inline-flex;
        text-align: center;
        padding: 2px;
        font-size: medium;
    }

    .logout a {
        color: white;
    }

    .options {
        display: inline-flex;
        flex-direction: column;
        text-align: left;
        padding: 20px;
        font-size: larger;
        background-color: #333;
        border: 2px solid white;
    }

    .websiteName {
        flex-direction: column;
        background-color: navy;
        color: white;
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

    .button-link:hover {
        background-color: grey;
    }

    h2 {
        color: white;
    }

    h1 {
        color: white;
    }

    .back-link {
        color: cyan;
        text-decoration: underline;
        font-weight: bold;
    }

    .back-link:hover {
        color: limegreen;
    }

    .forum-link {
        color: cyan;
        font-size: 1.2em;
        text-decoration: none;
    }

    .forum-link:hover {
        color: limegreen;
        text-decoration: underline;
    }
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forums</title>
    <div class="websiteName">
        <center><h1>Sekuri Talks - Forums</h1></center>
    </div>
</head>
<body>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    <hr>

    <h2>Create a New Forum</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="forum.php" method="POST">
        <label for="forum_name">Forum Name:</label><br>
        <input type="text" name="forum_name" id="forum_name" required><br><br>

        <label for="forum_description">Forum Description:</label><br>
        <textarea name="forum_description" id="forum_description" rows="4" cols="50" required></textarea><br><br>

        <button type="submit">Create Forum</button>
    </form>
    <hr>

    <!-- Display Existing Forums -->
    <h2>Available Forums</h2>
    <ul>
        <?php foreach ($forums as $forum): ?>
            <li>
                <a href="forum_view.php?forum_id=<?php echo $forum['forum_id']; ?>" class="forum-link">
                    <strong><?php echo htmlspecialchars($forum['forum_name']); ?></strong>
                </a>
                <p><?php echo htmlspecialchars($forum['forum_description']); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
</body>
</html>
