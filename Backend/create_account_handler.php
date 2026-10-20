<?php
session_start();
include "functions.php";

//if username or password are empty, send them back
if(empty($_POST["username"]) || empty($_POST["password"])){
    header("Location: ../create_account.php");
    exit();
}

$username = $_POST["username"];
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];

//check that both passwords match
if(!match_password($password, $confirm_password)){
    header("Location: ../create_account.php?error=password_mismatch");
    exit();
}

//hash the password before storing it
$hashed = password_hash($password, PASSWORD_DEFAULT);

//try to create the account
$result = create_user($username, $hashed);

if($result){
    //fetch the newly created user to get their user_id
    $user = get_user($username);

    //log user in by setting session variables
    $_SESSION["loggedin"] = true;
    $_SESSION["username"] = $username;
    $_SESSION["user_id"] = $user["user_id"];

    header("Location: ../create_account_follow.php");
    exit();
}
else{
    header("Location: ../create_account.php?error=creation_failed");
    exit();
}
?>
