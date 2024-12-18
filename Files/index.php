<?php session_start(); ?>
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
    <title>Login</title>
    <div class="websiteName">
        <center><h1 style>Sekuri Talks</h1></center>
    </div>
    <center><h1 style="color:white;">Login</h1></center>
</head>
<body>
    <?php if (isset($_SESSION['error'])): ?>
        <center><p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p></center>
    <?php endif; ?>
    <form class="login" action="authenticate.php" method="POST">
        <div class="form-group">
            <input type="text" name="username" id="username" placeholder="Username" required>
            <br>
        </div>
        <div class="form-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <br>
        </div>
        <div class="button-group">
            <center><button type="submit">Login</button></center>
        </div>
    </form>
    <center><p style="display: inline-flex; text-align:center; padding:10px; background-color:navy; color:white;">Don't have an account? <a style="padding-left: 5px; color: white;" href="signup.php">Sign up</a></p></center>

</body>
</html>
