<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die("Access denied.");
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['link_title'], $_POST['link_url'])) {
    $link_title = trim($_POST['link_title']);
    $link_url = trim($_POST['link_url']);

    if (strlen($link_title) < 3) {
        $error = "Link title must be at least 3 characters long.";
    } elseif (!filter_var($link_url, FILTER_VALIDATE_URL)) {
        $error = "Invalid URL.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO news_links (link_title, link_url) VALUES (:link_title, :link_url)");
        $stmt->execute(['link_title' => $link_title, 'link_url' => $link_url]);
        $success = "Link added successfully.";
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM news_links WHERE link_id = :link_id");
    $stmt->execute(['link_id' => $delete_id]);
    $success = "Link deleted successfully.";
}

$links = $pdo->query("SELECT * FROM news_links ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News Links</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .message {
            color: green;
        }
        .error {
            color: red;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
        }
        table th {
            background-color: #f8f8f8;
        }
        a.delete-link {
            color: red;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage News Links</h1>
        <a href="dashboard.php">Back to Dashboard</a>
        <hr>

        <h2>Add a New Link</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="message"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form action="admin_news_links.php" method="POST">
            <label for="link_title">Title:</label><br>
            <input type="text" name="link_title" id="link_title" required><br><br>

            <label for="link_url">URL:</label><br>
            <input type="url" name="link_url" id="link_url" required><br><br>

            <button type="submit">Add Link</button>
        </form>
        <hr>

        <h2>Existing Links</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($links as $link): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($link['link_id']); ?></td>
                        <td><?php echo htmlspecialchars($link['link_title']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($link['link_url']); ?>" target="_blank">
                                <?php echo htmlspecialchars($link['link_url']); ?>
                            </a>
                        </td>
                        <td>
                            <a class="delete-link" href="admin_news_links.php?delete_id=<?php echo $link['link_id']; ?>"
                               onclick="return confirm('Are you sure you want to delete this link?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
