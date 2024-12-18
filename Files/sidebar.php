<?php
require 'db.php';

//newest links
$news_links = $pdo->query("SELECT * FROM news_links ORDER BY created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="sidebar">
    <h3>Latest News</h3>
    <ul>
        <?php foreach ($news_links as $link): ?>
            <li>
                <a href="<?php echo htmlspecialchars($link['link_url']); ?>" target="_blank">
                    <?php echo htmlspecialchars($link['link_title']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<style>
    .sidebar {
        background-color: #333;
        color: #fff;
        padding: 15px;
        margin: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 250px;
        position: fixed;
        top: 50px;
        right: 10px;
    }
    .sidebar h3 {
        text-align: center;
        font-size: 18px;
        margin-bottom: 15px;
    }
    .sidebar ul {
        list-style: none;
        padding: 0;
    }
    .sidebar ul li {
        margin-bottom: 10px;
    }
    .sidebar ul li a {
        color: #007BFF;
        text-decoration: none;
        font-size: 16px;
    }
    .sidebar ul li a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            position: static;
            margin: 10px 0;
            box-shadow: none;
        }
    }
</style>
