<?php
session_start();
include "./Backend/functions.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: login.php");
    exit();
}

$user = get_user($_SESSION["username"]);
$services = get_services($_SESSION["user_id"]);
$reviews = get_reviews($_SESSION["user_id"]);
$certificates = get_certificates($_SESSION["user_id"]);
$avg_rating = get_average_rating($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trust Your Trades</title>
    <link rel="stylesheet" href="./Stylesheets/general_style.css">
    <link rel="stylesheet" href="./Stylesheets/general_profile_style.css">
    <link rel="stylesheet" href="./Stylesheets/reviews.css">
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
                        <li><img class="p-icon" src="./Images/profile-logo.svg" alt="Profile icon"><a class="p-link" href="./client_profile.php">Profile</a></li>
                        <li><img class="p-icon" src="./Images/reviews-icon.svg" alt="Reviews icon"><a class="p-current"><u>Reviews</u></a></li>
                        <li><img class="p-icon" src="./Images/invoices-logo.svg" alt="Invoices icon"><a class="p-link" href="./client_invoices.php">Invoices</a></li>
                    </ul>
                </div>
            </div>

            <div id="p-right">
                <div id="page-header">
                    <div id="page-title"><p>Your Reviews</p></div>
                    <p id="avg-rating">Average Rating: <?= $avg_rating ? round($avg_rating, 1) . "/5" : "Not yet rated" ?></p>
                    <div id="add"><a href="./Backend/add_review.php">✚ Add</a></div>
                </div>
                <div id="content">
                    
                    <?php foreach($reviews as $review): ?>
                        <div class="review">
                            <img class="pfp" src="./Images/default-user.png" alt="User profile picture">

                            <div class="review-content">
                                <div class="review-header">
                                    <span class="name"><?= get_reviewer_name($review["reviewer_id"]) ?></span>
                                    <span class="rating">
                                        <?php
                                            $rating = (int)$review["rating"];

                                            for($i = 1; $i <= 5; $i++){
                                                if($i <= $rating){
                                                    echo "<span class='star filled'>★</span>";
                                                }
                                                else{
                                                    echo "<span class='star empty'>★</span>";
                                                }
                                            }
                                        ?>
                                    </span>
                                </div>
                                <p class="comment">
                                    <?= $review["comment"] ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                </div>
            </div>
        </div>
        <footer>Group 6 • CS 268 • Spring 2026</footer>
    </div>
</body>
</html>
