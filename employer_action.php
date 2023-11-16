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

if (isset($_POST['nom'])){
    $verifExist = $db->query("SELECT * FROM badgeuse_employer where id_employer = ".securisation($_POST['id_em']))->numRows();
    if ($verifExist > 0){
        $update1 = $db->query("UPDATE badgeuse_employer SET employer_prenom = '".securisation($_POST['prenom'])."', employer_nom = '".securisation($_POST['nom'])."' WHERE id_employer = ".securisation($_POST['id_em']));
        $update2 = $db->query("UPDATE badgeuse_contrat SET type_contrat = '".securisation($_POST['type_c'])."', vol_h = ".securisation($_POST['vol_h']).", id_etablissement = ".securisation($_POST['entrep'])." WHERE id_employer = ".securisation($_POST['id_em']));
    }
    header("Location: employes.php");
}
if (isset($_GET['edite']) and isset($_GET['id'])){
    $verifExist = $db->query("SELECT * FROM badgeuse_employer where id_employer = ".securisation($_GET['id']))->numRows();
    if ($verifExist > 0){
        $selectEmployer = $db->query("SELECT * FROM badgeuse_employer, badgeuse_contrat where badgeuse_employer.id_employer = badgeuse_contrat.id_employer AND badgeuse_employer.id_employer = ".securisation($_GET['id']))->fetchArray();
        if ($_GET['edite'] == 1){
            $css = array("css.css");
            afficher_head("Badg'us", $css, "UTF-8");
            include('bandeau.php');
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
                                <h3 class="text-center">Modifier un employé</h3>
                                <form class="mb-5" method="post" id="contactForm" action="employer_action.php" name="contactForm">
                                  <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                      <label for="nom" class="col-form-label">Nom *</label>
                                      <input required type="text" class="form-control" name="nom" id="nom" value="'.$selectEmployer['employer_nom'].'" placeholder="Votre nom">
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                      <label for="prenom" class="col-form-label">Prénom *</label>
                                      <input required type="text" class="form-control" name="prenom" id="prenom" value="'.$selectEmployer['employer_prenom'].'" placeholder="Votre pénom">
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                      <label for="pin1" class="col-form-label">Code Pin *</label>
                                      <input required disabled type="text" class="form-control" name="pin1" value="'.$selectEmployer['employer_pin'].'" id="pin" placeholder="Votre pin">
                                    </div>
                                    <br>
                                    <div class="col-md-6 form-group mb-3">
                                      <label for="entrep" class="col-form-label">Établissement *</label>
                                      <select required class="form-control" name="entrep" id="entrep">';
                                $select_all_entep = $db->query("SELECT * FROM badgeuse_etablissements")->fetchAll();
                                foreach ($select_all_entep as $etab){
                                    if($etab['id_etablissement'] ==  $selectEmployer['id_etablissement']){
                                        echo '<option selected value="'.$etab['id_etablissement'].'">'.$etab["etablissement_nom"].'</option>';
                                    }else{
                                        echo '<option value="'.$etab['id_etablissement'].'">'.$etab["etablissement_nom"].'</option>';
                                    }
                                }
                                echo'                 </select>
                                    </div>
                                    
                                      <div class="col-md-6 form-group mb-3">
                                        <label for="vol_h" class="col-form-label">Volume horaire *</label>
                                        <input required type="number" min="0" max="48" class="form-control" value="'.$selectEmployer['vol_h'].'" name="vol_h" id="vol_h" placeholder="Votre volume horaire">
                                      </div>      
                                      <div class="col-md-6 form-group mb-3">
                                        <label for="type_c" class="col-form-label">Type de contrat *</label>
                                        <input required type="text" class="form-control" name="type_c" value="'.$selectEmployer['type_contrat'].'" id="type_c" placeholder="Type de contrat (ex : CDI, CDD ...)">
                                      </div>
                                  </div>
                                  <input type="hidden" name="pin" value="'.$selectEmployer['employer_pin'].'">
                                  <input type="hidden" name="id_em" value="'.securisation($_GET['id']).'">
                                  <div class="row justify-content-center">
                                    <div class="col-md-5 form-group text-center">
                                  <input type="submit" value="Modifier" class="btn btn-block btn-primary rounded-0 py-2 px-4">
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
        }else{
            echo "DELETE FROM `badgeuse_employer` WHERE id_employer = ".securisation($_GET['id']);
            $delet_employer = $db->query("DELETE FROM `badgeuse_employer` WHERE id_employer = ".securisation($_GET['id']));
            header("Location: employes.php");
        }
    }
}