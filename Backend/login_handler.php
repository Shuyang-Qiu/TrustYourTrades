<?php
session_start();
include "functions.php";

if(empty($_POST["username"]) || empty($_POST["password"])){
    header("Location: ../login.php");
    exit();
}

$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);

$result = check_user($username, $password);

if($result === true){
    $user = get_user($username); //fetch user to get user_id
    $_SESSION["loggedin"] = true;
    $_SESSION["username"] = $username;
    $_SESSION["user_id"] = $user["user_id"];
    $_SESSION["id"] = $username . date("Y-m-d");

    if($user["is_contractor"] == 1){
        header("Location: ../contractor_profile.php");
        exit();
    }
    else{
        header("Location: ../client_profile.php");
        exit();
    }
}
else{
    header("Location: ../login.php?error=1");
    exit();
}
?>
