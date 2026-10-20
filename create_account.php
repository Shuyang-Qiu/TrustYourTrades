<!DOCTYPE html>
<html lang="en">
    <?php include "./Backend/functions.php"; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Create Account</title>
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
                <p id="title">Create account</p>
                <p id="subtitle">Create your TrustYourTrades account.</p>
                <form action="./Backend/create_account_handler.php" method="POST">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter a username" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter a password" required>
                    <label for="conf_password">Re-Enter Password</label>
                    <input type="password" name="confirm_password" id="conf_password" placeholder="Re-enter password" required>
                    <input type="submit" value="Create Account">
                    <p>Already have an account? <a href="login.php">Log in</a></p><br>
                </form>

                <?php if (isset($_GET["error"])): ?>
                    <?php if ($_GET["error"] === "password_mismatch"): ?>
                        <p class="error">Passwords do not match.</p>
                    <?php elseif ($_GET["error"] === "creation_failed"): ?>
                        <p class="error">Username already taken or an error occurred.</p>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</body>
</html>
