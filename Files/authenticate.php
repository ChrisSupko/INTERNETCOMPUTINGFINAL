<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        // Check if the user is an admin
        if ($user['is_admin']) { 
            $_SESSION['is_admin'] = true;
        } else {
            $_SESSION['is_admin'] = false;
        }

        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = 'Invalid username or password';
        header('Location: index.php');
        exit;
    }
}
?>
