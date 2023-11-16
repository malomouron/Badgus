<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
} elseif (!isset($_SESSION['etablissement'])) {
    header('Location: index.php');
}
include('functions.php');
include("config.inc.badger.php");
include('db.php');
$db = new db($dbhost, $dbuser, $dbpass, $dbname);

if (isset($_GET['id']) and isset($_GET["link"])){

    $select_verif_exist = $db->query("SELECT * FROM badgeuse_planning WHERE id_planning = ".$_GET['id'])->numRows();
    if ($select_verif_exist == 1){
        $del = $db->query("DELETE FROM badgeuse_planning WHERE id_planning = ".$_GET['id']);
    }
    header("Location: ".$_GET['link']);
}
?>