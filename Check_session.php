<?php
session_start();

if (!isset($_SESSION['Usuario']) || $_SESSION['Usuario'] !== TRUE) {
    header("Location: Index.php");
    exit();
}

$inactive = 1800;
if (isset($_SESSION['last_activity'])) {
    $session_life = time() - $_SESSION['last_activity'];
    if ($session_life > $inactive) {
        session_unset();
        session_destroy();
        header("Location: Index.php?error=session_expired");
        exit();
    }
}
$_SESSION['last_activity'] = time();
?>