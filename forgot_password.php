<!DOCTYPE html>
<html lang="en">
    <?php include "./Backend/functions.php"; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Forgot Password</title>
    <link rel="stylesheet" href="./Stylesheets/general_style.css">
    <link rel="stylesheet" href="./Stylesheets/login.css">
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
                <p id="title">Reset password</p>
                <p id="subtitle">Enter your username and we'll reset your password.</p>
                <form action="./Backend/forgot_password_handler.php" method="POST">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username" required>
                    <input type="submit" value="Continue">
                    <p>Remembered it? <a href="login.php">Back to log in</a></p><br>
                </form>

                <?php if (isset($_GET["error"])): ?>
                    <p class="error">Unable to find an account with the provided username.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</body>
</html>
