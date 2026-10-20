<?php
session_start();
include "./Backend/functions.php";

$service_id = intval($_GET["id"] ?? 0);

//back to search if no valid id
if(!$service_id){
    header("Location: search.php");
    exit();
}

$service = get_service_by_id($service_id);

//back to search if service not found
if(!$service){
    header("Location: search.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - <?= htmlspecialchars($service["service_name"]) ?></title>
    <link rel="stylesheet" href="./Stylesheets/general_style.css">
    <link rel="stylesheet" href="./Stylesheets/results.css">
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
                        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                            <a id="logout-link" href="./Backend/logout.php">Logout</a>
                        <?php else: ?>
                            <a id="login-link" href="login.php">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>

        <div id="content">
            <div id="header">
                <span class="service_name"><?= htmlspecialchars($service["service_name"]) ?></span>
            </div>

            <div id="service_profile">
                <!-- profile picture omitted, unsure of how to store with database -->
                <div id="picture"></div>
                <p id="contractor_name"><?= htmlspecialchars($service["first_name"]." ".$service["last_name"]) ?></p>
            </div>

            <div id="details">
                <div class="service_address">
                    <p class="address"><?= htmlspecialchars($service["service_address"])."," ?></p>
                    <p class="city"><?= htmlspecialchars($service["service_city"]).","?></p>
                    <p class="state"><?= htmlspecialchars($service["service_state"]) ?></p>
                    <p class="zip"><?= htmlspecialchars($service["service_zip"]) ?></p>
                </div>
                <div class="service_publish">
                    <p class="date"><?= date("m/d/Y", strtotime($service["service_time"])) ?></p>
                    <p class="time"><?= date("H:i", strtotime($service["service_time"])) ?></p>
                </div>
                <p id="description"><?= htmlspecialchars($service["service_desc"]) ?></p>
            </div>

            <a class="back" href="javascript:history.back()">Back</a>
        </div>
        <footer>Group 6 • CS 268 • Spring 2026</footer>
    </div>
</body>
</html>
