<!DOCTYPE html>
<html lang="en">
    <?php include "./Backend/functions.php"; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Login</title>
    <link rel="stylesheet" href="./Stylesheets/login.css">
    <link rel="stylesheet" href="./Stylesheets/general_style.css">
</head>
<body>
    <div id="container">
        <div id="nav">
            <div id="logo-name">
                <img id="website-logo" src="./Images/tyt-logo.png" alt="TrustYourTrades Logo">
                <p id="website-name">TrustYourTrades</p>
            </div>
        </div>

        <div id="content">
            <div id="login">
                <p id="title">Welcome back</p>
                <p id="subtitle">Sign in to your TrustYourTrades account.</p>
                <form action="./Backend/login_handler.php" method="POST">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    <input type="submit" value="Login">
                    <p class="acc-link">Don't have an account? <a href="create_account.php">Sign up</a></p>
                    <p class="acc-link"><a href="forgot_password.php">Forgot your password?</a></p>
                </form>

                <?php if (isset($_GET["error"])): ?>
                    <p class="error">Unable to match provided username and password.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</body>
</html>
