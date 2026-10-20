<?php
session_start();
include "./Backend/functions.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

$user = get_user($_SESSION["username"]);
$services = get_services($_SESSION["user_id"]);
$reviews = get_reviews($_SESSION["user_id"]);
$certificates = get_certificates($_SESSION["user_id"]);
$invoices = get_invoices($_SESSION["user_id"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Certificates</title>
    <link rel="stylesheet" href="./Stylesheets/general_style.css">
    <link rel="stylesheet" href="./Stylesheets/general_profile_style.css">
    <link rel="stylesheet" href="./Stylesheets/certificates.css">
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
                        <li><img class="p-icon" src="./Images/services-logo.svg" alt="Services icon"><a class="p-link" href="contractor_services.php">Services</a></li>
                        <li><img class="p-icon" src="./Images/reviews-icon.svg" alt="Reviews icon"><a class="p-link" href="contractor_reviews.php">Reviews</a></li>
                        <li><img class="p-icon" src="./Images/cert-logo.svg" alt="Certificates icon"><a class="p-current"><u>Certificates</u></a></li>
                        <li><img class="p-icon" src="./Images/invoices-logo.svg" alt="Invoices icon"><a class="p-link" href="contractor_invoices.php">Invoices</a></li>
                    </ul>
                </div>
            </div>

            <div id="p-right">
                <div id="page-header">
                    <div id="page-title"><p>Your Certificates and Insurance</p></div>
                    <div id="add"><a href="./Backend/add_certificate.php">✚ Add</a></div>
                </div>
                <div id="content">
                    <?php foreach($certificates as $cert): ?> <!-- foreach loops for all certs -->
                            <div class="cert-card">
                                <a class="edit-icon" href="./Backend/edit_certificate.php?id=<?= $cert['id'] ?>">
                                    <img src="./Images/edit.svg" alt="Edit icon">
                                </a>
                                <img class="cert-icon" src="./Images/cert-logo.svg" alt="Certificates icon">
                                <p class="cert-name"><?= $cert["cert_name"] ?></p>
                                <p class="cert_id"><?= $cert["cert_id"] ?></p>
                                <p class="cert-issuer"><?= $cert["issuing_state"] ?></p>
                                <p class="cert-expiry">Expires: <?= date("M d, Y", strtotime($cert["expiry"])) ?></p>
                            </div>
                        <?php endforeach; ?>
                </div>
            </div>
        </div>
        <footer>Group 6 • CS 268 • Spring 2026</footer>
    </div>
</body>
</html>
