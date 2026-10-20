<?php
session_start();
include "functions.php";

if(!isset($_SESSION["reset_user"])){
    header("Location: ../forgot_password.php");
    exit();
}

if(empty($_POST["password"]) || empty($_POST["confirm_password"])){
    header("Location: ../reset_password.php");
    exit();
}

if(!match_password($_POST["password"], $_POST["confirm_password"])){
    header("Location: ../reset_password.php?error=password_mismatch");
    exit();
}

$username = $_SESSION["reset_user"];
$hashed = password_hash($_POST["password"], PASSWORD_DEFAULT);

update_password($username, $hashed);

unset($_SESSION["reset_user"]);
header("Location: ../login.php");
exit();
?>
