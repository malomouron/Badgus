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

$css = array("css.css");
afficher_head("Badg'us", $css, "UTF-8");
$db = new db($dbhost, $dbuser, $dbpass, $dbname);
include('bandeau.php');
if (isset($_POST['nom'])){
    $insert_employer = $db->query("INSERT INTO `badgeuse_employer`(`employer_nom`, `employer_prenom`, `employer_pin`) VALUES ('".securisation($_POST['nom'])."','".securisation($_POST['prenom'])."','".securisation($_POST['pin'])."')");
    $select_employer = $db->query("SELECT * FROM badgeuse_employer WHERE employer_nom = '".securisation($_POST['nom'])."' AND employer_prenom = '".securisation($_POST['prenom'])."' AND employer_pin = '".securisation($_POST['pin'])."'")->fetchArray();
    $insert_contrat = $db->query("INSERT INTO `badgeuse_contrat`(`id_etablissement`, `id_employer`, `vol_h`, `type_contrat`) VALUES (".securisation($_POST['entrep']).",".$select_employer['id_employer'].",".securisation($_POST['vol_h']).",'".securisation($_POST['type_c'])."')");
    header("Location: ".$_SERVER['REQUEST_URI']);
}
$code = generateRandomCode();
echo '  <head>
    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="contact-form/fonts/icomoon/style.css">
    <link rel="stylesheet" href="contact-form/css/bootstrap.min.css">
    <link rel="stylesheet" href="contact-form/css/style.css">

  </head>
  <body>
  <style>
        body{
            background-color: #ffdd2b;
        }
  </style>

  <div class="content">
    <div class="container">
      <div class="row align-items-stretch justify-content-center no-gutters">
        <div class="col-md-7">
          <div id="new_em_div_form_over" class="form h-100 contact-wrap p-5">
            <h3 class="text-center">Ajouter un employé</h3>
            <form class="mb-5" method="post" id="contactForm" action="new_employer.php" name="contactForm">
              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="nom" class="col-form-label">Nom *</label>
                  <input required type="text" class="form-control" name="nom" id="nom" placeholder="Votre nom">
                </div>
                <div class="col-md-6 form-group mb-3">
                  <label for="prenom" class="col-form-label">Prénom *</label>
                  <input required type="text" class="form-control" name="prenom" id="prenom" placeholder="Votre pénom">
                </div>
                <div class="col-md-6 form-group mb-3">
                  <label for="pin1" class="col-form-label">Code Pin *</label>
                  <input required disabled type="text" class="form-control" name="pin1" value="'.$code.'" id="pin" placeholder="Votre pin">
                </div>
                <br>
                <div class="col-md-6 form-group mb-3">
                  <label for="entrep" class="col-form-label">Établissement *</label>
                  <select required class="form-control" name="entrep" id="entrep">';
$select_all_entep = $db->query("SELECT * FROM badgeuse_etablissements")->fetchAll();
foreach ($select_all_entep as $etab){
    echo '<option value="'.$etab['id_etablissement'].'">'.$etab["etablissement_nom"].'</option>';
}
echo'                 </select>
                </div>
                
                  <div class="col-md-6 form-group mb-3">
                    <label for="vol_h" class="col-form-label">Volume horaire *</label>
                    <input required type="number" value="35" min="0" max="48" class="form-control" name="vol_h" id="vol_h" placeholder="Votre volume horaire">
                  </div>      
                  <div class="col-md-6 form-group mb-3">
                    <label for="type_c" class="col-form-label">Type de contrat *</label>
                    <input required type="text" class="form-control" name="type_c" id="type_c" placeholder="Type de contrat (ex : CDI, CDD ...)">
                  </div>
              </div>
              <input type="hidden" name="pin" value="'.$code.'">
              <div class="row justify-content-center">
                <div class="col-md-5 form-group text-center">
                  <input type="submit" value="Ajouter" class="btn btn-block btn-primary rounded-0 py-2 px-4">
                  <span class="submitting"></span>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  </body>';


include('footer.php');
?>