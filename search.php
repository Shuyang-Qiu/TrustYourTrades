<?php
session_start();
include "./Backend/functions.php";

//dont need to verify that client is signed in to search services?

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Trust Your Trades</title>
    <link rel="stylesheet" href="./Stylesheets/general_style.css">
    <link rel="stylesheet" href="./Stylesheets/search.css">
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
                    <li><a class="link" href="#">Services</a></li>
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
            <div id="search">
                <p id="title">Service Search<p>
                <p id="subtitle">Search for a specific service.</p>
                <!-- Form element names: name, type, contractor, address, city, state, zip, date, time, button (for submission) -->
                <form method="GET" action="search_results.php">
                    <label for="name"><b>Service Name or Keyword</b></label>
                    <div id="services">
                        <input name="keyword" type="text" id="name">
                        <!-- dropped service type selection -->
                    </div>
                    
                    <label for="contractor"><b>Contractor</b></label>
                    <input name="contractor" type="text" id="contractor">
                    
                    <label for="address"><b>Location</b></label>
                    <input name="address" type="text" id="address" placeholder="Address">
                    <div id="location">
                        <input name="city" type="text" placeholder="City">
                        <input name="state" type="text" placeholder="State">
                        <input name="zip" type="number" placeholder="ZIP Code">
                    </div>
                    
                    <label for="date"><b>Date of Service</b></label>
                    <div id="datetime">
                        <input name="date" type="date" id="date">
                        <input name="time" type="time">
                    </div>
                    <input type="submit" value="Search">
                </form>
            </div>
        </div>
        <footer>Group 6 • CS 268 • Spring 2026</footer>
    </div>
</body>
</html>
