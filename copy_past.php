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
if (isset($_GET['a'])){
    if ($_GET['a'] == 0){
        if (isset($_GET['url']) and isset($_GET['semaine']) and isset($_GET['year'])){
            $_SESSION['copy'] = Array("semaine" => $_GET['semaine'],
                                        "year" => $_GET['year']);
            header("Location: ".$_GET['url']);
        }
    }else{
        if(isset($_GET['url']) and isset($_SESSION['copy']) and isset($_GET['new_semaine'])){
            $select_all_plannnig_sem = $db->query("SELECT * FROM badgeuse_planning, badgeuse_contrat WHERE  badgeuse_contrat.id_employer = badgeuse_planning.id_employer AND badgeuse_contrat.id_etablissement = ".$_SESSION['etablissement']." AND planning_semaine = ".$_SESSION['copy']['semaine']." AND planning_annee = ".$_SESSION['copy']['year'])->numRows();
            if ($select_all_plannnig_sem > 0){
                $select_plannnig = $db->query("SELECT * FROM badgeuse_planning, badgeuse_contrat WHERE  badgeuse_contrat.id_employer = badgeuse_planning.id_employer AND badgeuse_contrat.id_etablissement = ".$_SESSION['etablissement']." AND planning_semaine = ".$_SESSION['copy']['semaine']." AND planning_annee = ".$_SESSION['copy']['year'])->fetchAll();
                foreach ($select_plannnig as $plan){
                    $nombre_de_semaines = date('W') + $_GET['new_semaine'] - $_SESSION['copy']['semaine'];
                    $entree = new DateTime($plan["planning_entree"]);
                    $entree->modify("+$nombre_de_semaines weeks");
                    $entree = $entree->format('Y-m-d H:i:s');

                    $sortie = new DateTime($plan["planning_sortie"]);
                    $sortie->modify("+$nombre_de_semaines weeks");
                    $sortie = $sortie->format('Y-m-d H:i:s');

                    $date = new DateTime($entree);
                    $new_semaine = $date->format("W");
                    $new_year = $date->format("Y");

                    $insert_plan = $db->query("INSERT INTO `badgeuse_planning`(`id_employer`, `planning_entree`, `planning_sortie`, `planning_semaine`, `planning_annee`) VALUES (".$plan['id_employer'].",'".$entree."','".$sortie."',".$new_semaine.",".$new_year.")");
                }
            }
            header("Location: ".$_GET['url']);
        }
    }
}elseif (isset($_GET['b'])){
    if ($_GET['b'] == 0){
        if (isset($_GET['url']) and isset($_GET['date']) ){
            $_SESSION['copy_j'] = $_GET['date'];
            header("Location: ".$_GET['url']);
        }
    }elseif ($_GET['b'] == 1){
        if(isset($_GET['url']) and isset($_SESSION['copy_j']) and isset($_GET['date'])){

            $select_all_plannnig_day = $db->query("SELECT *  FROM badgeuse_planning, badgeuse_contrat WHERE badgeuse_contrat.id_employer = badgeuse_planning.id_employer AND badgeuse_contrat.id_etablissement = ".$_SESSION['etablissement']." AND DATE(planning_entree) = '".securisation($_SESSION['copy_j'])."'")->numRows();
            if ($select_all_plannnig_day > 0){
                $select_plannnig = $db->query("SELECT *  FROM badgeuse_planning, badgeuse_contrat WHERE badgeuse_contrat.id_employer = badgeuse_planning.id_employer AND badgeuse_contrat.id_etablissement = ".$_SESSION['etablissement']." AND DATE(planning_entree) = '".securisation($_SESSION['copy_j'])."'")->fetchAll();
                foreach ($select_plannnig as $plan){
                    $date1 = new DateTime($_GET['date']);

                    $date2 = new DateTime($plan['planning_entree']);
                    $date3 = new DateTime($plan['planning_sortie']);

                    $heure = $date2->format('H:i:s');
                    $heure2 = $date3->format('H:i:s');

                    $entree = $date1->format('Y-m-d') . ' ' . $heure;
                    $sortie = $date1->format('Y-m-d') . ' ' . $heure2;

                    $date = new DateTime($entree);
                    $new_semaine = $date->format("W");
                    $new_year = $date->format("Y");
                    $insert_plan = $db->query("INSERT INTO `badgeuse_planning`(`id_employer`, `planning_entree`, `planning_sortie`, `planning_semaine`, `planning_annee`) VALUES (".$plan['id_employer'].",'".$entree."','".$sortie."',".$new_semaine.",".$new_year.")");
                }
            }
            header("Location: ".$_GET['url']);
        }
    }else{
        if(isset($_GET['url']) and isset($_GET['date'])){
            $vider_table = $db->query("DELETE FROM badgeuse_planning WHERE DATE(planning_entree) = '".securisation($_GET['date'])."' AND id_employer IN ( SELECT id_employer FROM badgeuse_contrat WHERE id_etablissement = ".$_SESSION['etablissement']." );");
            header("Location: ".$_GET['url']);
        }
    }
}
?>