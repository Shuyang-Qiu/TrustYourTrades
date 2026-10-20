<?php
session_start();
include "./Backend/functions.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: login.php");
    exit();
}

$user = get_user($_SESSION["username"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trust Your Trades</title>
    <script src="Profile.js"></script>
    <link rel="stylesheet" href="./Stylesheets/general_style.css">
    <link rel="stylesheet" href="./Stylesheets/general_profile_style.css">
</head>
<body>
    <div id="container">
        <div id="nav">
            <div id="logo-name">
                <img id="website-logo" src="./Images/tyt-logo.png" alt="TrustYourTrades Logo">
                <p id="website-name">TrustYourTrades</p>
            </div>
            <div id="nav-links">
                <ul>
                    <li><a class="link" href="./home.php">Home</a></li>
                    <li><a class="link" href="./about.php">About</a></li>
                    <li><a class="link" href="./search.php">Services</a></li>
                    <li><a class="link" href="./contact.php">Contact</a></li>
                    <li><a class="link" href="./Backend/profile.php">Profile</a></li>
                    <li>
                        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                            <a id="logout-link" href="./Backend/logout.php">Logout</a>
                        <?php else: ?>
                            <a id="login-link" href="login.php">Login</a>
                        <?php endif; ?>
                    </li>    
                </ul>
            </div>
        </div>
        <div id="profile">
            <div id="p-left">
                <div id="banner"></div>
                <div id="picture"></div>
                <div id="name-occ">
                    <p id="user-name"><?=$user["first_name"]." ".$user["last_name"] ?></p>
                    <p id="occ"><?= $user["is_contractor"] ? "Contractor" : "Client" ?></p>
                </div>
                <div id="profile-nav">
                    <ul>
                        <li><img class="p-icon" src="./Images/profile-logo.svg" alt="Profile icon"><a class="p-current"><u>Profile</u></a></li>
                        <li><img class="p-icon" src="./Images/reviews-icon.svg" alt="Reviews icon"><a class="p-link" href="./client_reviews.php">Reviews</a></li>
                        <li><img class="p-icon" src="./Images/invoices-logo.svg" alt="Invoices icon"><a class="p-link" href="./client_invoices.php">Invoices</a></li>
                    </ul>
                </div>
            </div>

            <div id="p-right">
                <div id="page-header">
                    <div id="page-title"><p>Profile</p></div>
                </div>
                <div id="content">
                    <!-- Temp profile info, feel free to change as needed -->
                    <p id="name"><?=$user["first_name"]." ".$user["last_name"] ?></p>
                    <br>
                    <h4>Biography</h4>
                    <div id="bio">
                        <p>First-time homeowner tackling projects one at a time. Happy to be part of the TrustYourTrades community!</p>
                    </div><br>
                    <h4>Information</h4>
                    <div id="account-info">
                        <p>Username: <?= $user["username"] ?></p>
                        <p>Account Type: <?= $user["is_contractor"] ? "Contractor" : "Client" ?></p>
                        <p>Member #<?= $user["user_id"] ?></p>
                        <p>Member Since: January 2025</p>
                    </div><br>
                    <h4>Recent Activity</h4>
                    <div id="activity">
                        <p class="activity-item">Booked a service on 04/25/2026</p>
                        <p class="activity-item">Left a review for Emily Williams</p>
                        <p class="activity-item">Invoice from Bob Smith paid</p>
                    </div>
                </div>
            </div>
        </div>
        <footer>Group 6 • CS 268 • Spring 2026</footer>
    </div>
</body>
</html>
