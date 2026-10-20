<?php
session_start();
include "./Backend/functions.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: login.php");
    exit();
}

$user = get_user($_SESSION["username"]);
$services = get_services($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Services</title>
    <link rel="stylesheet" href="./Stylesheets/general_style.css">
    <link rel="stylesheet" href="./Stylesheets/general_profile_style.css">
    <link rel="stylesheet" href="./Stylesheets/services.css">
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
                    <li><a class="link" href="home.php">Home</a></li>
                    <li><a class="link" href="about.php">About</a></li>
                    <li><a class="link" href="search.php">Services</a></li>
                    <li><a class="link" href="contact.php">Contact</a></li>
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
                        <li><img class="p-icon" src="./Images/profile-logo.svg" alt="Profile icon"><a class="p-link" href="contractor_profile.php">Profile</a></li>
                        <li><img class="p-icon" src="./Images/services-logo.svg" alt="Services icon"><a class="p-current"><u>Services</u></a></li>
                        <li><img class="p-icon" src="./Images/reviews-icon.svg" alt="Reviews icon"><a class="p-link" href="contractor_reviews.php">Reviews</a></li>
                        <li><img class="p-icon" src="./Images/cert-logo.svg" alt="Certificates icon"><a class="p-link" href="contractor_certificates.php">Certificates</a></li>
                        <li><img class="p-icon" src="./Images/invoices-logo.svg" alt="Invoices icon"><a class="p-link" href="contractor_invoices.php">Invoices</a></li>
                    </ul>
                </div>
            </div>

            <div id="p-right">
                <div id="page-header">
                    <div id="page-title"><p>Your Services</p></div>
                    <div id="add"><a href="./Backend/add_service.php">✚ Add</a></div>
                </div>
                <div id="content">
                    <?php if(empty($services)): ?>
                        <p id="no-results">No services found.</p>
                    <?php else: ?>
                        <?php foreach($services as $service): ?>
                            <div class="service-card">
                                <p class="svc-name"><?= htmlspecialchars($service["service_name"]) ?></p>
                                <p class="svc-address"><?= htmlspecialchars($service["service_address"]) ?></p>
                                <p class="svc-city"><?= htmlspecialchars($service["service_city"].", ".$service["service_state"]." ".$service["service_zip"]) ?></p>
                                <p class="svc-datetime"><?= date("M d, Y", strtotime($service["service_time"])) ?> at <?= date("H:i", strtotime($service["service_time"])) ?></p>
                                <p class="svc-desc"><?= htmlspecialchars($service["service_desc"]) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <footer>Group 6 • CS 268 • Spring 2026</footer>
    </div>
</body>
</html>
