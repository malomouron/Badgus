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
include('bandeau.php');

if (!isset($_GET['date'])){
    $mois = date('m');
    $anne = date('Y');
}else{
    $_GET['date'] = securisation($_GET['date']);
    list($anne, $mois) = explode("-", $_GET['date']);
    $mois = ltrim($mois, "0");
}
$erreur_mois = $db->query('SELECT * FROM badgeuse_badge, badgeuse_employer, badgeuse_contrat WHERE badgeuse_badge.id_employer = badgeuse_contrat.id_employer and badgeuse_badge.id_employer = badgeuse_employer.id_employer AND id_etablissement = '.$_SESSION['etablissement'].' AND cron = 1 AND MONTH(badge_date_entree) = '.$mois.' AND YEAR(badge_date_entree) = '.$anne.';')->fetchAll();

echo '
    
    <form action="error.php" method="get" id="form_erreur">
        <label for="mois_error">Sélectionner un mois et une année : </label>
        <input required id="mois_error" type="month" value="';
if (isset($_GET['date'])){
    echo $_GET['date'];
}
echo '" name="date"><br>
        <input type="submit" value="Filtrer">
    </form>
    <h2 id="h2_erreur_badge">Erreur de badge pour le '.$mois.'/'.$anne.' : </h2>
    <div id="div_align_form_err">';
foreach ($erreur_mois as $erreur){
    echo'   <form class="formulaire_erreur_modif" action="error_action.php" method="post">
                <p><span>'.$erreur['employer_nom'].'</span> <span>'.$erreur['employer_prenom'].'</span></p>
                
                <label for="badge_entree">Badge d\'entrée</label>
                <input required name="badge_entree" id="badge_entree" type="datetime-local" value="'.mb_substr($erreur['badge_date_entree'], 0, -3).'">
                
                <label for="badge_sortie">Badge de sortie</label>
                <input required name="badge_sortie" id="badge_sortie" type="datetime-local" value="'.mb_substr($erreur['badge_date_sortie'], 0, -3).'">
                
                <input type="hidden" name="id_badge" value="'.$erreur['id_badge'].'">
                
                <input type="submit" value="Comfirmer l\'horaire">
            </form>';
}

echo'</div><br><br><br><br><br>
    </body>';


include ('footer.php');
?>