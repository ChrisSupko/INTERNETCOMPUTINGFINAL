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
        $stmt->execute(['forum_id' => $forum_id, 'user_id' => $user_id, 'message_text' => $message_text]);

        header("Location: forum_view.php?forum_id=$forum_id");
        exit;
    } else {
        $error = "Message cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($forum['forum_name']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($forum['forum_name']); ?></h1>
    <p><?php echo htmlspecialchars($forum['forum_description']); ?></p>
    <a href="forum.php">Back to Forums</a>
    <hr>

    <h2>Messages</h2>
    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <p>
                    <strong><?php echo htmlspecialchars($message['username']); ?></strong>: 
                    <?php echo nl2br(htmlspecialchars($message['message_text'])); ?>
                    <br><small><?php echo $message['posted_at']; ?></small>
                </p>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No messages yet. Be the first to post!</p>
        <?php endif; ?>
    </div>

    <h3>Post a Message</h3>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="forum_view.php?forum_id=<?php echo $forum_id; ?>" method="POST">
        <textarea name="message_text" rows="4" cols="50" required></textarea>
        <br>
        <button type="submit">Post Message</button>
    </form>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
</body>
</html>
