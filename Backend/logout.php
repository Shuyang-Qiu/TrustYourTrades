<!-- maybe add to functions?? unsure yet -->

<?php
    session_start();
    session_destroy();
    header("Location: ../home.php");
    exit();
?>
