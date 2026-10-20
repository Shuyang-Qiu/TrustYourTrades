<?php
session_start();
include "functions.php";

$username = $_SESSION["username"];
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$is_contractor = (int)$_POST["is_contractor"];

//try to store firstname, lastname, and contractor tinyint
$result = store_user_info($username, $first_name, $last_name, $is_contractor);

if($result){
    if($is_contractor == 1){
        header("Location: ../contractor_certificates.php");
        exit();
    }
    elseif($is_contractor == 0){
        header("Location: ../client_profile.php");
        exit();
    }
    else{
        echo("is_contractor not set to 0 or 1!");
        exit();
    }
}
else{
    header("Location: ../create_account_follow.php?error=creation_failed");
    exit();
}
?>
