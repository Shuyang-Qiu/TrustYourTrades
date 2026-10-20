<?php
session_start();
include "functions.php";

//redirect to login if not logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ./login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

//handle the form if it was submitted
$response = handle_certificate_requests($user_id);

//keep form values filled in after submission
if(isset($_POST["cert_name"])){
    $cert_name = $_POST["cert_name"];
}
else{
    $cert_name = "";
}

if(isset($_POST["cert_id"])){
    $cert_id = $_POST["cert_id"];
}
else{
    $cert_id = "";
}

if(isset($_POST["issuing_state"])){
    $issuing_state = $_POST["issuing_state"];
}
else{
    $issuing_state = "";
}

if(isset($_POST["expiry"])){
    $expiry = $_POST["expiry"];
}
else{
    $expiry = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Add Certificate</title>
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
                <p id="title">Add Certificate</p>

                <?php if($response["create_success"] != ""){ ?>
                    <p class="msg-success"><?= $response["create_success"] ?></p>
                <?php } ?>

                <?php if($response["create_error"] != ""){ ?>
                    <p class="msg-error"><?= $response["create_error"] ?></p>
                <?php } ?>

                <form method="POST" action="add_certificate.php">
                    <label>Certificate Name</label>
                    <input type="text" name="cert_name" value="<?= htmlspecialchars($cert_name) ?>">

                    <label>Certificate ID</label>
                    <input type="number" name="cert_id" min="1" value="<?= htmlspecialchars($cert_id) ?>">

                    <label>Issuing State</label>
                    <input type="text" name="issuing_state" value="<?= htmlspecialchars($issuing_state) ?>">

                    <label>Expiration Date</label>
                    <input type="date" name="expiry" value="<?= htmlspecialchars($expiry) ?>">

                    <button type="submit" name="create_certificate">Submit</button>
                </form>

                <a href="../contractor_certificates.php" class="back">Back</a>
            </div>
        </div>
    </div>
</body>
</html>
