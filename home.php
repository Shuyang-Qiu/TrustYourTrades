<?php
session_start();
include "./Backend/functions.php";

//run setup script if database isn't already created
$conn = new mysqli("localhost", "root", "", "");

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

//see if tyt database already exists
$result = $conn->query("SHOW DATABASES LIKE 'tyt'");

if ($result->num_rows === 0) {
    //db doesn't exist, run setup and insertion scripts
    $conn->close();
    include "./Backend/DatabaseSetup.php";
    include "./Backend/InsertTests.php";
} else {
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Home</title>
    <link rel="stylesheet" href="./Stylesheets/general_style.css">
    <link rel="stylesheet" href="./Stylesheets/home.css">
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
            <h1>Find trusted contractors. <br>Skip the guesswork.</h1>
            <p>TrustYourTrades connects you with verified, dependable contractors for any job. Whether you need work done or offer services yourself, our platform helps you find the right match—without the uncertainty.</p>
        </div>
        <footer>Group 6 • CS 268 • Spring 2026</footer>
    </div>
</body>
</html>
