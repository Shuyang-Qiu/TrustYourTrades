<?php
session_start();
include "functions.php";

//redirect to login if not already logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ./login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

//handle the form if it was submitted
$response = handle_invoice_requests($user_id);

//get the list of services for the dropdown
$services = get_services($user_id);

//keep form values filled in after submission
if(isset($_POST["receiver_username"])){
    $receiver_username = $_POST["receiver_username"];
}
else{
    $receiver_username = "";
}

if(isset($_POST["service_id"])){
    $service_id = $_POST["service_id"];
}
else{
    $service_id = "";
}

if(isset($_POST["date"])){
    $date = $_POST["date"];
}
else{
    $date = "";
}

if(isset($_POST["price"])){
    $price = $_POST["price"];
}else
{
    $price = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Add Invoice</title>
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
                <p id="title">Create Invoice</p>

                <?php if($response["create_success"] != ""){ ?>
                    <p class="msg-success"><?= $response["create_success"] ?></p>
                <?php } ?>

                <?php if($response["create_error"] != ""){ ?>
                    <p class="msg-error"><?= $response["create_error"] ?></p>
                <?php } ?>

                <form method="POST" action="add_invoice.php">
                    <label>Recipient Username</label>
                    <input type="text" name="receiver_username" value="<?= htmlspecialchars($receiver_username) ?>">

                    <label>Service</label>
                    <select name="service_id">
                        <option value="">-- Select a service --</option>
                        <?php foreach($services as $svc){ ?>
                            <option value="<?= $svc["service_id"] ?>"
                                <?php if($service_id == $svc["service_id"]) {echo "selected";} ?>>
                                <?= htmlspecialchars($svc["service_name"]) ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label>Date</label>
                    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">

                    <label>Price ($)</label>
                    <input type="number" name="price" step="0.01" min="0.01" value="<?= htmlspecialchars($price) ?>">

                    <button type="submit" name="create_invoice">Submit</button>
                </form>

                <a href="../contractor_invoices.php" class="back">Back</a>
            </div>
        </div>
    </div>
</body>
</html>
