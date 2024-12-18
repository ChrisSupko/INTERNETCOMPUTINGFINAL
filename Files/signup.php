<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    $errors = [];
    if (strlen($username) < 3) $errors[] = 'Username must be at least 3 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        // Check for duplicate username or email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = 'Username or email already exists.';
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
            $stmt->execute(['username' => $username, 'email' => $email, 'password_hash' => $hashed_password]);

            // Redirect to login page
            header('Location: index.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<style>
    body{
        margin: 1;
        padding: 2;
        font-family: 'Arial', sans-serif;
        background-color: #f7f9fc;
        color: #333;
        line-height: 1.6;
        background-color: black;
    }
    .login{
        align-items: left;
        justify-content: left;
        margin-bottom: 10px;
        font-size: small;
    }
    .form-group{
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .form-group input{
        width: 300px;
        padding: 5px;
        margin: right;
    }
    .form-group label {
        min-width: 250px;
        text-align: center;
        font-size: medium;
    }
    .button-group{
        align-items: center;
    }
    button{
        padding: 5px 5px;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 14px;
        width: 30%;
        background-color:blue;
        border: 2px solid white;
    }
    button:hover{
        background-color:grey;
    }
    .websiteName{
        flex-direction: column;
        background-color:navy;
        color:white;
    }
</style>    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <div class="websiteName">
        <center><h1>Sekuri Talks</h1></center>
    </div>
    <center><h1 style="color:white;">Sign Up</h1></center>
</head>
<body>
    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <center><li><?php echo htmlspecialchars($error); ?></li></center>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form class="login" action="signup.php" method="POST">
        <div class = "form-group">
            <input type="text" name="username" id="username" placeholder="Username" required>
            <br>
        </div>
        <div class = "form-group">
            <input type="email" name="email" id="email" placeholder="eMail" required>
            <br>
        </div>
        <div class = "form-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <br>
        </div>
        <div class = "form-group">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
            <br>
        </div>
        <div class = "button-group">
            <center><button type="submit">Sign Up</button></center>
        </div>
    </form>
    <center><p style="display: inline-flex; text-align:center; padding:10px; background-color:navy; color:white;">Already have an account? <a style="padding-left: 5px; color: white;" href="index.php">Log in</a></p></center>
</body>
</html>
