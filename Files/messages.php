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
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (:sender_id, :receiver_id, :message_text)");
    $stmt->execute(['sender_id' => $user_id, 'receiver_id' => $recipient_id, 'message_text' => $message_text]);
    header("Location: messages.php?recipient_id=$recipient_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
</head>
<body>
    <h1>Messages</h1>
    <a href="dashboard.php">Back to Dashboard</a>
    <h2>Users</h2>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><a href="messages.php?recipient_id=<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['username']); ?></a></li>
        <?php endforeach; ?>
    </ul>

    <?php if ($recipient_id): ?>
        <h2>Conversation</h2>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
            <?php foreach ($messages as $message): ?>
                <p>
                    <strong><?php echo htmlspecialchars($message['sender']); ?></strong>: 
                    <?php echo nl2br(htmlspecialchars($message['message_text'])); ?>
                    <br><small><?php echo $message['sent_at']; ?></small>
                </p>
                <hr>
            <?php endforeach; ?>
        </div>
        <form action="messages.php?recipient_id=<?php echo $recipient_id; ?>" method="POST">
            <textarea name="message_text" rows="4" cols="50" required></textarea>
            <br>
            <button type="submit">Send Message</button>
        </form>
    <?php endif; ?>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
</body>
</html>
