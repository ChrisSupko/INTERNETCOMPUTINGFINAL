<?php
require 'db.php';

$news_links = $pdo->query("SELECT * FROM news_links ORDER BY created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="border: 1px solid #ccc; padding: 10px; margin: 10px; width: 200px;">
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
