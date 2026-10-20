<?php
session_start();
include "functions.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ./login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $service_name = $_POST["service_name"];
    $service_time = $_POST["service_time"];
    $service_address = $_POST["service_address"];
    $service_city = $_POST["service_city"];
    $service_state = $_POST["service_state"];
    $service_zip = intval($_POST["service_zip"]);
    $service_desc = $_POST["service_desc"];

    if(!$service_name || !$service_time || !$service_address || !$service_city || !$service_state || !$service_zip || !$service_desc){
        $error = "All fields are required.";
    } else {
        $conn = db_connect();
        $stmt = $conn->prepare("
            INSERT INTO services (user_id, service_name, service_time, service_address, service_city, service_state, service_zip, service_desc)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("isssssis", $user_id, $service_name, $service_time, $service_address, $service_city, $service_state, $service_zip, $service_desc);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        $success = $result ? "Service added successfully!" : "Failed to add service.";
    }
}

$service_name = $_POST["service_name"] ?? "";
$service_time = $_POST["service_time"] ?? "";
$service_address = $_POST["service_address"] ?? "";
$service_city = $_POST["service_city"] ?? "";
$service_state = $_POST["service_state"] ?? "";
$service_zip = $_POST["service_zip"] ?? "";
$service_desc = $_POST["service_desc"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Add Service</title>
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
                <p id="title">Add Service</p>
                <?php if($success != ""): ?>
                    <p class="msg-success"><?= $success ?></p>
                <?php endif; ?>
                <?php if($error != ""): ?>
                    <p class="msg-error"><?= $error ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <label>Service Name</label>
                    <input type="text" name="service_name" value="<?= htmlspecialchars($service_name) ?>">
                    <label>Date & Time</label>
                    <input type="datetime-local" name="service_time" value="<?= htmlspecialchars($service_time) ?>">
                    <label>Address</label>
                    <input type="text" name="service_address" value="<?= htmlspecialchars($service_address) ?>">
                    <label>City / State / Zip</label>
                    <div id="city-row">
                        <input type="text" name="service_city" placeholder="City" value="<?= htmlspecialchars($service_city) ?>">
                        <input type="text" name="service_state" placeholder="State" value="<?= htmlspecialchars($service_state) ?>">
                        <input type="number" name="service_zip" placeholder="Zip" value="<?= htmlspecialchars($service_zip) ?>">
                    </div>
                    <label>Description</label>
                    <input type="text" name="service_desc" value="<?= htmlspecialchars($service_desc) ?>">
                    <button type="submit" name="create_service">Submit</button>
                </form>
                <a href="../contractor_services.php" class="back">Back</a>
            </div>
        </div>
    </div>
</body>
</html>
