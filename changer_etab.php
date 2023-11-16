<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
}
setcookie("Etablissement_id", "", time() + (1));
header("Location: badgus.php");
?>