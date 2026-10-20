<?php
session_start();

// If they didn't come through step 1, kick them out
if (!isset($_SESSION["reset_user"])) {
    header("Location: forgot_password.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Login</title>
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
                <p id="title">Create password</p>
                <p id="subtitle">Enter and confirm a new password.</p>
                <form action="./Backend/reset_password_handler.php" method="POST">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter a password" required>
                    <label for="re-enter">Re-Enter Password</label>
                    <input type="password" name="confirm_password" id="re-enter" placeholder="Re-enter password" required>
                    <input type="submit" value="Reset Password">
                    <p>Remembered it? <a href="login.php">Back to log in</a></p><br>
                </form>

                <?php if (isset($_GET["error"])): ?>
                    <?php if ($_GET["error"] === "password_mismatch"): ?>
                        <p class="error">Passwords do not match.</p>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</body>
</html>
