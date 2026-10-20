<?php
session_start();
include "functions.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ./login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$response = handle_review_requests($user_id);

$reviewee_username = isset($_POST["reviewee_username"]) ? $_POST["reviewee_username"] : "";
$rating = isset($_POST["rating"]) ? $_POST["rating"] : "";
$comment = isset($_POST["comment"]) ? $_POST["comment"] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Add Review</title>
    <link rel="stylesheet" href="../Stylesheets/add.css">
</head>
<body>
    <div id="container">
        <div id="nav">
            <div id="logo-name">
                <img id="website-logo" src="../Images/tyt-logo.png" alt="TrustYourTrades Logo">
                <p id="website-name">TrustYourTrades</p>
            </div>
        </div>

        <div id="content">
            <div id="entry">
                <p id="title">Create Review</p>

                <?php if($response["create_success"] != ""): ?>
                    <p class="msg-success"><?= $response["create_success"] ?></p>
                <?php endif; ?>

                <?php if($response["create_error"] != ""): ?>
                    <p class="msg-error"><?= $response["create_error"] ?></p>
                <?php endif; ?>

                <form method="POST" action="add_review.php">
                    <label>Recipient Username</label>
                    <input type="text" name="reviewee_username" value="<?= htmlspecialchars($reviewee_username) ?>">

                    <label>Rating</label>
                    <select name="rating">
                        <option value="">-- Select a rating --</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= $rating == $i ? "selected" : "" ?>>
                                <?= $i ?> star<?= $i > 1 ? "s" : "" ?>
                            </option>
                        <?php endfor; ?>
                    </select>

                    <label>Comment</label>
                    <input type="text" name="comment" value="<?= htmlspecialchars($comment) ?>">

                    <button type="submit" name="create_review">Submit</button>
                </form>

                <!-- redirect after submitting review based on session is_contractor tinyint (bool) -->
                <?php
                $back_user = get_user($_SESSION["username"]);
                $back = $back_user["is_contractor"] ? "../contractor_reviews.php" : "../client_reviews.php";
                ?>
                <a href="<?= $back ?>" class="back">Back</a>
            </div>
        </div>
    </div>
</body>
</html>
