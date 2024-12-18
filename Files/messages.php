<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$users = $pdo->query("SELECT user_id, username FROM users WHERE user_id != $user_id")->fetchAll(PDO::FETCH_ASSOC);

$recipient_id = $_GET['recipient_id'] ?? null;
$messages = [];

if ($recipient_id) {
    $stmt = $pdo->prepare("
        SELECT m.message_text, m.sent_at, u.username AS sender
        FROM messages m
        JOIN users u ON m.sender_id = u.user_id
        WHERE (m.sender_id = :user_id AND m.receiver_id = :recipient_id)
           OR (m.sender_id = :recipient_id AND m.receiver_id = :user_id)
        ORDER BY m.sent_at ASC
    ");
    $stmt->execute(['user_id' => $user_id, 'recipient_id' => $recipient_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $recipient_id) {
    $message_text = $_POST['message_text'];
    if (strlen(trim($message_text)) > 0) {
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (:user_id, :recipient_id, :message_text)");
        $stmt->execute(['user_id' => $user_id, 'recipient_id' => $recipient_id, 'message_text' => trim($message_text)]);
        header("Location: messages.php?recipient_id=$recipient_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: black;
            color: white;
            line-height: 1.6;
        }
        .websiteName {
        flex-direction: column;
        background-color: navy;
        color: white;}

        h1, h2 {
            color: white;
        }

        a {
            color: cyan;
            text-decoration: none;
        }

        a:hover {
            color: limegreen;
            text-decoration: underline;
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
            background-color: #222;
            color: white;
            border: 1px solid white;
            margin-bottom: 10px;
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

        .recipient-list {
            margin: 10px 0;
        }

        .recipient-list a {
            display: inline-block;
            margin: 5px 0;
        }
        .back-link {
        color: cyan;
        text-decoration: underline;
        font-weight: bold;}

        .back-link:hover {
        color: limegreen;
        }

        .message-link {
        display: inline-block;
        margin: 5px 0;
        color: cyan;
        font-size: 20pt;
        text-decoration: none;
        }

        .messgae-link:hover {
        color: limegreen;
        text-decoration: underline;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <div class="websiteName">
        <center><h1>Sekuri Talks - Messages</h1></center>
    </div>
</head>
<body>
    <a class="back-link" href="dashboard.php">Back to Dashboard</a>
    <hr>

    <h2>Select a Recipient:</h2>
    <div class="message-link">
        <?php foreach ($users as $user): ?>
            <a href="messages.php?recipient_id=<?php echo $user['user_id']; ?>">
                <?php echo htmlspecialchars($user['username']); ?>
            </a><br>
        <?php endforeach; ?>
    </div>

    <?php if ($recipient_id): ?>
        <h2>Conversation</h2>
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <div class="username"><?php echo htmlspecialchars($message['sender']); ?></div>
                <div class="posted-at"><?php echo htmlspecialchars($message['sent_at']); ?></div>
                <p><?php echo nl2br(htmlspecialchars($message['message_text'])); ?></p>
            </div>
        <?php endforeach; ?>

        <h2>Send a Message</h2>
        <form action="messages.php?recipient_id=<?php echo $recipient_id; ?>" method="POST">
            <textarea name="message_text" rows="4" placeholder="Type your message here..." required></textarea><br>
            <button type="submit">Send</button>
        </form>
    <?php endif; ?>
</body>
</html>
