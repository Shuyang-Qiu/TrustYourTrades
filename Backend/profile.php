<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ../login.php");
    exit();
}

include "functions.php";

$user = get_user($_SESSION["username"]);

if($user["is_contractor"]){
    header("Location: ../contractor_profile.php");
}
else{
    header("Location: ../client_profile.php");
}

exit();
?>
