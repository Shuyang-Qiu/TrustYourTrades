<?php
session_start();
include "functions.php";

//redirect to login if not already logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ./login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

//get the invoice id from the URL (e.g. edit_invoice.php?id=5)
if(isset($_GET["id"])){
    $invoice_id = intval($_GET["id"]);
}
else{
    $invoice_id = 0;
}

//if no valid id, send them back
if($invoice_id == 0){
    header("Location: ../contractor_invoices.php");
    exit();
}

//handle the form if it was submitted
$response = handle_invoice_requests($user_id);

//fetch the invoice from the database
$invoice = get_invoice_by_id($invoice_id, $user_id);

//if the invoice doesnt exist or doesnt belong to this user, send them back
if(!$invoice){
    header("Location: ../contractor_invoices.php");
    exit();
}

//use POST values if re-displaying after an error, otherwise use the  databse values
if(isset($_POST["price"])){
    $price = $_POST["price"];
}
else{
    $price = $invoice["price"];
}

if(isset($_POST["status"])){
    $status = $_POST["status"];
}
else{
    $status = $invoice["status"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustYourTrades - Edit Invoice</title>
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
                <p id="title">Edit Invoice</p>

                <!-- show invoice info above the form -->
                <p style="color: rgba(255,255,255,0.7); font-size: 15px; margin: 0 0 10px 0;">
                    Invoice #<?= $invoice["invoice_id"] ?> ·
                    <?= htmlspecialchars($invoice["service_name"]) ?> ·
                    <?= htmlspecialchars($invoice["receiver_username"]) ?>
                </p>

                <?php if($response["edit_success"] != ""){ ?>
                    <p class="msg-success"><?= $response["edit_success"] ?></p>
                <?php } ?>

                <?php if($response["edit_error"] != ""){ ?>
                    <p class="msg-error"><?= $response["edit_error"] ?></p>
                <?php } ?>

                <form method="POST" action="edit_invoice.php?id=<?= $invoice_id ?>">
                    <!-- hidden field for form to track which invoice to update -->
                    <input type="hidden" name="invoice_id" value="<?= $invoice_id ?>">

                    <label>Price ($)</label>
                    <input type="number" name="price" step="0.01" min="0.01" value="<?= htmlspecialchars($price) ?>">

                    <label>Status</label>
                    <select name="status">
                        <?php
                        $statuses = ["Pending", "Overdue", "Paid", "Cancelled"];
                        foreach($statuses as $s){
                        ?>
                            <option value="<?= $s ?>" <?php if($status === $s){echo "selected";} ?>>
                                <?= $s ?>
                            </option>
                        <?php } ?>
                    </select>

                    <button type="submit" name="edit_invoice">Save Changes</button>

                    <button type="submit" name="delete_invoice"onclick="return confirm('Are you sure you want to delete this invoice?')"
                            style="background:#ff6b6b; color:white; margin-top:8px;">Delete Invoice</button>
                </form>

                <a href="../contractor_invoices.php" class="back">Back</a>
            </div>
        </div>
    </div>
</body>
</html>
