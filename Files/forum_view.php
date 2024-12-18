<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$forum_id = $_GET['forum_id'] ?? null;

if (!$forum_id) {
    die("Invalid forum selected.");
}

$stmt = $pdo->prepare("SELECT * FROM forums WHERE forum_id = :forum_id");
$stmt->execute(['forum_id' => $forum_id]);
$forum = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$forum) {
    die("Forum not found.");
}

$stmt = $pdo->prepare("
    SELECT fm.message_text, fm.posted_at, u.username 
    FROM forum_messages fm
    JOIN users u ON fm.user_id = u.user_id
    WHERE fm.forum_id = :forum_id
    ORDER BY fm.posted_at ASC
");
$stmt->execute(['forum_id' => $forum_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_text = $_POST['message_text'] ?? '';
    $user_id = $_SESSION['user_id'];

    if (strlen(trim($message_text)) > 0) {
        $stmt = $pdo->prepare("INSERT INTO forum_messages (forum_id, user_id, message_text) VALUES (:forum_id, :user_id, :message_text)");
        $stmt->execute(['forum_id' => $forum_id, 'user_id' => $user_id, 'message_text' => trim($message_text)]);
        header("Location: forum_view.php?forum_id=" . $forum_id);
        exit;
    }
}
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

        h1, h2 {
            color: cyan;
        }

        .back-link {
            color: cyan;
            text-decoration: underline;
            font-weight: bold;
        }

        .back-link:hover {
            color: limegreen;
        }

        .forum-title {
            text-align: center;
            font-size: 2em;
            color: white;
            background-color: navy;
            
            margin-bottom: 20px;
        }

        .message {
            background-color: #333;
            color: white;
            border: 1px solid white;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }

        .message .username {
            font-weight: bold;
            color: cyan;
        }

        .message .posted-at {
            font-size: 0.8em;
            color: grey;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #222;
            color: white;
            border: 1px solid white;
        }

        button {
            background-color: blue;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: grey;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($forum['forum_name']); ?></title>
</head>
<body>
    <a href="forum.php" class="back-link">Back to Forums</a>
    <hr>

    <!-- Forum Title -->
    <div class="forum-title">
        <?php echo htmlspecialchars($forum['forum_name']); ?>
    </div>

    <!-- Display Messages -->
    <h2>Messages( Oldest First )</h2>
    <?php foreach ($messages as $message): ?>
        <div class="message">
            <div class="username"><?php echo htmlspecialchars($message['username']); ?></div>
            <div class="posted-at"><?php echo htmlspecialchars($message['posted_at']); ?></div>
            <p><?php echo nl2br(htmlspecialchars($message['message_text'])); ?></p>
        </div>
    <?php endforeach; ?>

    <!-- Post a New Message -->
    <h2>Post a New Message</h2>
    <form action="forum_view.php?forum_id=<?php echo $forum_id; ?>" method="POST">
        <textarea name="message_text" rows="4" placeholder="Write your message here..." required></textarea><br>
        <button type="submit">Post Message</button>
    </form>
</body>
</html>
