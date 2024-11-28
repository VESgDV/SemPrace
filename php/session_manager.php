<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$inactive = 20 * 60;
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > $inactive) {
       
        session_unset();
        session_destroy();
        header("Location: login.php?message=Session expired");
        exit();
    }
}


$_SESSION['last_activity'] = time();


if (!isset($_SESSION['uzivatel_id'])) {
   
    header("Location: login.php?message=Please log in");
    exit();
}
?>
