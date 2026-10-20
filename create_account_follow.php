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
                <p id="title">One last thing</p>
                <p id="subtitle">Finish setting up your account.</p>
                <form action="./Backend/create_account_follow_handler.php" method="POST">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" placeholder="Enter your first name" required>
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" placeholder="Enter your last name" required>
                    <label for="is_contractor">Are you a contractor or a client?</label>
                    <div class="cont_bool">
                        <label><input type="radio" name="is_contractor" id="is_contractor" value="1" required>Contractor</label>
                        <label><input type="radio" name="is_contractor" value="0">Client</label>
                    </div>
                    <input type="submit" value="To Account">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
