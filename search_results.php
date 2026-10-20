<?php
session_start();
include "./Backend/functions.php";

//"null coalescing operator" (??) to prevent 8 different if/else statements, isset(), or ternary shorthand (:?)
//ive used all of the above in various pages, testing NCO for service search
//added in PHP 7.0.x (https://www.php.net/manual/en/migration70.new-features.php)
$keyword = $_GET["keyword"] ?? "";
$contractor = $_GET["contractor"] ?? "";
$address = $_GET["address"] ?? "";
$city = $_GET["city"] ?? "";
$state = $_GET["state"] ?? "";
$zip = $_GET["zip"] ?? "";
$date = $_GET["date"] ?? "";
$time = $_GET["time"] ?? "";

$results = search_services($keyword, $contractor, $address, $city, $state, $zip, $date, $time);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Results</title>
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
                <!-- Works kinda like google, where after searching something, the searched name pops up in the search bar of the results page -->
                <form method="GET" action="search_results.php">
                    <input name="keyword" type="text" value="<?= htmlspecialchars($keyword) ?>">
                    <input type="submit" value="Search">
                </form>
                <a id="adv_link" href="./search.php">Advanced</a>
            </div>

            <!-- service module -->
            <?php if (empty($results)): ?>
                <p id="no-results">No services found. Please try adjusting your search.</p>
            <?php else: ?>
                <!-- module loop, displaying any services with matching params -->
                <?php foreach ($results as $service): ?>
                    <!-- Service module, input whatever values are needed -->
                    <!-- removed service type, not tracked in database -->
                    <div class="module" onclick="window.location='service_info.php?id=<?= $service['service_id'] ?>'">
                        <div class="service_header">
                            <span class="name"><?= htmlspecialchars($service["service_name"]) ?></span>
                            <p> - </p>
                            <span class="contractor"><?= htmlspecialchars($service["first_name"]." ".$service["last_name"]) ?></span>
                        </div>
                        <div class="service_address">
                            <p class="address"><?= htmlspecialchars($service["service_address"]).","?></p>
                            <p class="city"><?= htmlspecialchars($service["service_city"])."," ?>, </p>
                            <p class="state"><?= htmlspecialchars($service["service_state"]) ?></p>
                            <p class="zip"><?= htmlspecialchars($service["service_zip"])?></p>
                        </div>
                        <div class="service_publish">
                            <!-- capital Y = full year (2026), lowercase y = 2 digit (26) -->
                            <p class="date"><?= date("m/d/Y", strtotime($service["service_time"])) ?></p>
                            <!-- H:i gives 24hr time (14:30), h:i gives 12hr time (02:30) -->
                            <p class="time"><?= date("H:i", strtotime($service["service_time"])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <footer>Group 6 • CS 268 • Spring 2026</footer>
    </div>
</body>
</html>
