<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
}elseif (!isset($_SESSION['etablissement'])){
    header('Location: index.php');
}
include('functions.php');
include("config.inc.badger.php");
include('db.php');

$css = array("css.css");
afficher_head("Badg'us", $css, "UTF-8");
$db = new db($dbhost, $dbuser, $dbpass, $dbname);

if (isset($_POST['id_badge']) and isset($_POST['entree']) and isset($_POST['sortie']) and isset($_POST['date_badge'])){
    $VerifExist = $db->query("SELECT * FROM badgeuse_badge WHERE id_badge = ".securisation($_POST['id_badge']))->numRows();
    if ($VerifExist >0) {
        $date_debut_obj = new DateTime($_POST['entree']);
        $date_fin_obj = new DateTime($_POST['sortie']);
        if (!($date_debut_obj >= $date_fin_obj)) {
            $ancien_badge = $db->query("SELECT * FROM badgeuse_badge WHERE id_badge = " . securisation($_POST['id_badge']))->fetchArray();
            $updateBadge = $db->query("UPDATE badgeuse_badge SET badge_date_entree_dernier = \"" . $ancien_badge['badge_date_entree'] . "\" WHERE id_badge = " . securisation($_POST['id_badge']));
            $updateBadge = $db->query("UPDATE badgeuse_badge SET badge_date_sortie_dernier = \"" . $ancien_badge['badge_date_sortie'] . "\" WHERE id_badge = " . securisation($_POST['id_badge']));

            $updateBadge = $db->query("UPDATE badgeuse_badge SET badge_date_entree = \"" . securisation($_POST['date_badge']) . " " . securisation($_POST['entree']) . "\" WHERE id_badge = " . securisation($_POST['id_badge']));
            $updateBadge = $db->query("UPDATE badgeuse_badge SET badge_date_sortie = \"" . securisation($_POST['date_badge']) . " " . securisation($_POST['sortie']) . "\" WHERE id_badge = " . securisation($_POST['id_badge']));
            $updateBadge = $db->query("UPDATE badgeuse_badge SET cron = 3 WHERE id_badge = " . securisation($_POST['id_badge']));
        }
    }
}
header('Location: planning.php');
?>