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


$db = new db($dbhost, $dbuser, $dbpass, $dbname);

if (isset($_POST['id_employer']) and isset($_POST['badge_entree_ajt']) and isset($_POST['badge_sortie_ajt'])){
    $verifHveUser = $db->query("SELECT * FROM badgeuse_employer where id_employer = ".securisation($_POST['id_employer']))->numRows();
    $date_debut_obj = new DateTime($_POST['badge_entree_ajt']);
    $date_fin_obj = new DateTime($_POST['badge_sortie_ajt']);
    if (!($date_debut_obj >= $date_fin_obj)) {
        if ($verifHveUser > 0){
            $insert = $db->query("INSERT INTO badgeuse_badge VALUES (NULL, ".securisation($_POST['id_employer']).", '".securisation($_POST['badge_entree_ajt'])."','".securisation($_POST['badge_sortie_ajt'])."', NULL, NULL, 4)");
        }
    }
    header('Location: planning.php');
}else{
    $css = array("css.css");
    afficher_head("Badg'us", $css, "UTF-8");
    include('bandeau.php');
    $select_emp = $db->query("SELECT * FROM badgeuse_employer, badgeuse_contrat WHERE badgeuse_contrat.id_employer = badgeuse_employer.id_employer and id_etablissement = ".$_SESSION['etablissement'])->fetchAll();
    echo'
    <form method="post" action="addBadge.php" id="from_addBadge">
        <h2>Ajouter un badge : </h2>
        
        <label for="select_nom_employer_addBadge">Nom de l\'employé : </label>
        <select required name="id_employer" id="select_nom_employer_addBadge">';
    foreach ($select_emp as $employer){
        echo '<option value="'.$employer['id_employer'].'">'.$employer['employer_nom']." ".$employer['employer_prenom'].'</option>';
    }

    echo '       </select><br>
        <label for="badge_entree_ajt">Heure d\'arrivée : </label>
        <input required type="datetime-local" name="badge_entree_ajt" id="badge_entree_ajt"><br>
        
        <label for="badge_sortie_ajt">Heure de départ : </label>
        <input required type="datetime-local" name="badge_sortie_ajt" id="badge_sortie_ajt"><br>
        <input type="submit" value="Ajouter le badge">
    </form>
</body>';


    include ('footer.php');
}


?>
