<?php
session_start();
include "functions.php";

//redirect to login if not already logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ./login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

//get the certificate id from the URL (e.g. edit_certificate.php?id=3)
if(isset($_GET["id"])){
    $id = intval($_GET["id"]);
}
else{
    $id = 0;
}

//if no valid id, send them back
if($id == 0){
    header("Location: ../contractor_certificates.php");
    exit();
}

//handle the form if it was submitted
$response = handle_certificate_requests($user_id);

//fetch the certificate from the database
$cert = get_certificate_by_id($id, $user_id);

//if the certificate doesnt exist or doesnt belong to this user, send them back
if(!$cert){
    header("Location: ../contractor_certificates.php");
    exit();
}

//use POST values if re-displaying after an error, otherwise use  databse values
if(isset($_POST["cert_name"])){
    $cert_name = $_POST["cert_name"];
}
else{
    $cert_name = $cert["cert_name"];
}

if(isset($_POST["cert_id"])){
    $cert_id = $_POST["cert_id"];
}
else{
    $cert_id = $cert["cert_id"];
}

if(isset($_POST["issuing_state"])){
    $issuing_state = $_POST["issuing_state"];
}
else{
    $issuing_state = $cert["issuing_state"];
}

if(isset($_POST["expiry"])){
    $expiry = $_POST["expiry"];
}
else{
    $expiry = $cert["expiry"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Edit Certificate</title>
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
                <p id="title">Edit Certificate</p>

                <?php if($response["edit_success"] != ""){ ?>
                    <p class="msg-success"><?= $response["edit_success"] ?></p>
                <?php } ?>

                <?php if($response["edit_error"] != ""){ ?>
                    <p class="msg-error"><?= $response["edit_error"] ?></p>
                <?php } ?>

                <form method="POST" action="edit_certificate.php?id=<?= $id ?>">
                    <!-- hidden field so the form knows which certificate to update (added in cert table) -->
                    <input type="hidden" name="id" value="<?= $id ?>">

                    <label>Certificate Name</label>
                    <input type="text" name="cert_name" value="<?= htmlspecialchars($cert_name) ?>">

                    <label>Certificate ID</label>
                    <input type="number" name="cert_id" min="1" value="<?= htmlspecialchars($cert_id) ?>">

                    <label>Issuing State</label>
                    <input type="text" name="issuing_state" value="<?= htmlspecialchars($issuing_state) ?>">

                    <label>Expiration Date</label>
                    <input type="date" name="expiry" value="<?= htmlspecialchars($expiry) ?>">

                    <button type="submit" name="edit_certificate">Save Changes</button>

                    <button type="submit" name="delete_certificate" onclick="return confirm('Are you sure you want to delete this certificate?')"
                            style="background:#ff6b6b; color:white; margin-top:8px;">Delete Certificate</button>
                </form>

                <a href="../contractor_certificates.php" class="back">Back</a>
            </div>
        </div>
    </div>
</body>
</html>
