<?php
session_start();
include "functions.php";

if(empty($_POST["username"])){
    header("Location: ../forgot_password.php");
    exit();
}

$username = htmlspecialchars($_POST["username"]);
$user = get_user($username);

if(!$user){
    header("Location: ../forgot_password.php?error=user_not_found");
    exit();
}

//store username in session so reset page knows who to update
$_SESSION["reset_user"] = $username;
header("Location: ../reset_password.php");
exit();
?>
