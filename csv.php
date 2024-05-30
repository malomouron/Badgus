<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
}elseif (!isset($_SESSION['etablissement']) or !isset($_GET['date'])){
    header('Location: index.php');
}
include("config.inc.badger.php");
include('functions.php');
include('db.php');
include ("style_csv.php");

$db = new db($dbhost, $dbuser, $dbpass, $dbname);

require 'vendor/autoload.php'; // Inclure l'autoloader de PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
// Création de la première feuille
$sheet1 = $spreadsheet->getActiveSheet();


// Diviser la date en année et mois
list($_GET['annee'], $_GET['mois']) = explode("-", $_GET['date']);

$_GET['mois'] = ltrim($_GET['mois'], "0");

$erreur_mois = $db->query('SELECT * FROM badgeuse_badge, badgeuse_employer, badgeuse_contrat WHERE badgeuse_badge.id_employer = badgeuse_contrat.id_employer and badgeuse_badge.id_employer = badgeuse_employer.id_employer AND id_etablissement = '.$_SESSION['etablissement'].' AND cron = 1 AND MONTH(badge_date_entree) = '.securisation($_GET['mois']).' AND YEAR(badge_date_entree) = '.securisation($_GET['annee']).';')->numRows();
if ($erreur_mois > 0){
    header('Location: error.php?date='.$_GET['date']);
}

$dateDebut = new DateTime($_GET['annee']."-".$_GET['mois']."-01");
$dateFin = new DateTime($_GET['annee']."-".$_GET['mois']."-01");
$dateFin->modify('last day of this month');

// Formater les dates dans le format souhaité (01-03 - 31-03)
$dateDebutFormatee = $dateDebut->format('d-m');
$dateFinFormatee = $dateFin->format('d-m');
$sheet1->setTitle("$dateDebutFormatee - $dateFinFormatee");

//compter le nombre de semaine

// calcul pour heure sup
$year = securisation($_GET['annee']);
$month = securisation($_GET['mois']);
$firstDayOfMonth = new DateTime("$year-$month-01");
$week = $firstDayOfMonth->format("W");
$nbSemaine = 0 ;
while ($firstDayOfMonth->format("m") == $month) {
    $sundayOfCurrentWeek = clone $firstDayOfMonth;
    $sundayOfCurrentWeek->modify('next Sunday');
    if ($sundayOfCurrentWeek->format("m") == $month) {
        $nbSemaine++;
    }
    $firstDayOfMonth->modify('next Monday');
    $week++;
}

$firstDayOfMonth2 = new DateTime("$year-$month-01");
$weekCalc = $firstDayOfMonth2->format("W");

$sheet1->setCellValue('A1', 'Nom');
$sheet1->setCellValue('B1', 'Prénom');
$sheet1->setCellValue('C1', 'Volume Horaire Hebdomadaire (h)*');
$sheet1->setCellValue('D1', 'Volume Horaire Mensuel (h)');
$sheet1->setCellValue('E1', 'Jours Travaillés (j)');
$sheet1->setCellValue('F1', "Heures Travaillées (h)");
$sheet1->setCellValue('G1', "S".($weekCalc+0)." (Heures +/-)");
$sheet1->setCellValue('H1', "S".($weekCalc+1)." (Heures +/-)");
$sheet1->setCellValue('I1', "S".($weekCalc+2)." (Heures +/-)");
$sheet1->setCellValue('J1', "S".($weekCalc+3)." (Heures +/-)");
if ($nbSemaine == 4){
    $sheet1->setCellValue('K1', "S".($weekCalc+0)." (Heures ±)  heure majoré 10 %");
    $sheet1->setCellValue('L1', "S".($weekCalc+1)." (Heures ±)  heure majoré 10 %");
    $sheet1->setCellValue('M1', "S".($weekCalc+2)." (Heures ±)  heure majoré 10 %");
    $sheet1->setCellValue('N1', "S".($weekCalc+3)." (Heures ±)  heure majoré 10 %");
    $sheet1->setCellValue('O1', "S".($weekCalc+0)." (Heures ±)  heure majoré 20 %");
    $sheet1->setCellValue('P1', "S".($weekCalc+1)." (Heures ±)  heure majoré 20 %");
    $sheet1->setCellValue('Q1', "S".($weekCalc+2)." (Heures ±)  heure majoré 20 %");
    $sheet1->setCellValue('R1', "S".($weekCalc+3)." (Heures ±)  heure majoré 20 %");
    $sheet1->setCellValue('S1', "Total heure majoré 10 %");
    $sheet1->setCellValue('T1', "Total heure majoré 20 %");
    $sheet1->setCellValue('U1', "Repas Dûs");
}elseif ($nbSemaine == 5){
    $sheet1->setCellValue('K1', "S".($weekCalc+4)." (Heures +/-)");
    $sheet1->setCellValue('L1', "S".($weekCalc+0)." (Heures ±)  heure majoré 10 %");
    $sheet1->setCellValue('M1', "S".($weekCalc+1)." (Heures ±)  heure majoré 10 %");
    $sheet1->setCellValue('N1', "S".($weekCalc+2)." (Heures ±)  heure majoré 10 %");
    $sheet1->setCellValue('O1', "S".($weekCalc+3)." (Heures ±)  heure majoré 10 %");
    $sheet1->setCellValue('P1', "S".($weekCalc+4)." (Heures ±)  heure majoré 10 %");
    $sheet1->setCellValue('Q1', "S".($weekCalc+0)." (Heures ±)  heure majoré 20 %");
    $sheet1->setCellValue('R1', "S".($weekCalc+1)." (Heures ±)  heure majoré 20 %");
    $sheet1->setCellValue('S1', "S".($weekCalc+2)." (Heures ±)  heure majoré 20 %");
    $sheet1->setCellValue('T1', "S".($weekCalc+3)." (Heures ±)  heure majoré 20 %");
    $sheet1->setCellValue('U1', "S".($weekCalc+4)." (Heures ±)  heure majoré 20 %");
    $sheet1->setCellValue('V1', "Total heure majoré 10 %");
    $sheet1->setCellValue('W1', "Total heure majoré 20 %");
    $sheet1->setCellValue('X1', "Repas Dûs");
}



$style1 = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'color' => ['argb' => 'aaaaaa'], // Remplacez 'FFFF00' par le code de couleur de fond souhaité
    ]
];
$style2 = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'color' => ['argb' => '05c045'], // Remplacez 'FFFF00' par le code de couleur de fond souhaité
    ]
];
$style3 = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'color' => ['argb' => '50e3c2'], // Remplacez 'FFFF00' par le code de couleur de fond souhaité
    ]
];
$style = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'color' => ['argb' => '1fbed5'], // Remplacez 'FFFF00' par le code de couleur de fond souhaité
    ]
];
$style5 = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'color' => ['argb' => 'ea9999'], // Remplacez 'FFFF00' par le code de couleur de fond souhaité
    ]
];
$style6 = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'color' => ['argb' => 'fcc101'], // Remplacez 'FFFF00' par le code de couleur de fond souhaité
    ]
];
$style7 = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'color' => ['argb' => '777777'], // Remplacez 'FFFF00' par le code de couleur de fond souhaité
    ]
];
$style8 = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'color' => ['argb' => '0d3f81'], // Remplacez 'FFFF00' par le code de couleur de fond souhaité
    ]
];


$sheet1->getStyle('A1')->applyFromArray($style1);
$sheet1->getStyle('B1')->applyFromArray($style1);
$sheet1->getStyle('C1')->applyFromArray($style2);
$sheet1->getStyle('D1')->applyFromArray($style2);
$sheet1->getStyle('E1')->applyFromArray($style2);
$sheet1->getStyle('F1')->applyFromArray($style2);

if ($nbSemaine == 4){
    $sheet1->getStyle('G1')->applyFromArray($style3);
    $sheet1->getStyle('H1')->applyFromArray($style3);
    $sheet1->getStyle('I1')->applyFromArray($style3);
    $sheet1->getStyle('J1')->applyFromArray($style3);
    $sheet1->getStyle('K1')->applyFromArray($style5);
    $sheet1->getStyle('L1')->applyFromArray($style5);
    $sheet1->getStyle('M1')->applyFromArray($style5);
    $sheet1->getStyle('N1')->applyFromArray($style5);
    $sheet1->getStyle('O1')->applyFromArray($style6);
    $sheet1->getStyle('P1')->applyFromArray($style6);
    $sheet1->getStyle('Q1')->applyFromArray($style6);
    $sheet1->getStyle('R1')->applyFromArray($style6);
    $sheet1->getStyle('S1')->applyFromArray($style5);
    $sheet1->getStyle('T1')->applyFromArray($style6);
    $sheet1->getStyle('U1')->applyFromArray($style8);
}elseif ($nbSemaine == 5){
    $sheet1->getStyle('G1')->applyFromArray($style3);
    $sheet1->getStyle('H1')->applyFromArray($style3);
    $sheet1->getStyle('I1')->applyFromArray($style3);
    $sheet1->getStyle('J1')->applyFromArray($style3);
    $sheet1->getStyle('K1')->applyFromArray($style3);
    $sheet1->getStyle('L1')->applyFromArray($style5);
    $sheet1->getStyle('M1')->applyFromArray($style5);
    $sheet1->getStyle('N1')->applyFromArray($style5);
    $sheet1->getStyle('O1')->applyFromArray($style5);
    $sheet1->getStyle('P1')->applyFromArray($style5);
    $sheet1->getStyle('Q1')->applyFromArray($style6);
    $sheet1->getStyle('R1')->applyFromArray($style6);
    $sheet1->getStyle('S1')->applyFromArray($style6);
    $sheet1->getStyle('T1')->applyFromArray($style6);
    $sheet1->getStyle('U1')->applyFromArray($style6);
    $sheet1->getStyle('V1')->applyFromArray($style5);
    $sheet1->getStyle('W1')->applyFromArray($style6);
    $sheet1->getStyle('X1')->applyFromArray($style8);
}


$columnIndex = 'A';
if ($nbSemaine == 4){
    $lastColumnIndex = 'U'; // Mettez à jour ceci avec la dernière colonne de votre feuille
}elseif ($nbSemaine == 5){
    $lastColumnIndex = 'X'; // Mettez à jour ceci avec la dernière colonne de votre feuille
}
while ($columnIndex <= $lastColumnIndex) {
    $sheet1->getColumnDimension($columnIndex)->setAutoSize(true);
    $columnIndex++;
}


if ($_SESSION['etablissement'] == 2){
    $select_bage = $db->query("SELECT bb.id_employer, e.employer_nom, e.employer_prenom, SUM( TIME_TO_SEC( TIMEDIFF( bb.badge_date_sortie, bb.badge_date_entree ) ) ) / 3600 AS heures_travaillees_mois, c.vol_h AS volume_horaire_hebdo FROM badgeuse_badge bb JOIN badgeuse_employer e ON bb.id_employer = e.id_employer JOIN badgeuse_contrat c ON bb.id_employer = c.id_employer JOIN badgeuse_etablissements eta ON c.id_etablissement = eta.id_etablissement WHERE c.vol_h < 35 AND MONTH(bb.badge_date_entree) = ".securisation($_GET['mois'])." AND YEAR(bb.badge_date_entree) = ".securisation($_GET['annee'])." AND eta.id_etablissement = ".$_SESSION['etablissement']." GROUP BY bb.id_employer, e.employer_nom, e.employer_prenom, c.vol_h;")->fetchAll();
}else{
    $select_bage = $db->query("SELECT bb.id_employer, e.employer_nom, e.employer_prenom, SUM( TIME_TO_SEC( TIMEDIFF( bb.badge_date_sortie, bb.badge_date_entree ) ) ) / 3600 AS heures_travaillees_mois, c.vol_h AS volume_horaire_hebdo FROM badgeuse_badge bb JOIN badgeuse_employer e ON bb.id_employer = e.id_employer JOIN badgeuse_contrat c ON bb.id_employer = c.id_employer JOIN badgeuse_etablissements eta ON c.id_etablissement = eta.id_etablissement WHERE MONTH(bb.badge_date_entree) = ".securisation($_GET['mois'])." AND YEAR(bb.badge_date_entree) = ".securisation($_GET['annee'])." AND eta.id_etablissement = ".$_SESSION['etablissement']." GROUP BY bb.id_employer, e.employer_nom, e.employer_prenom, c.vol_h;")->fetchAll();
}
$c = 2;
foreach ($select_bage as $employer){

    // calcul pour heure sup
    $year = securisation($_GET['annee']);
    $month = securisation($_GET['mois']);
    $firstDayOfMonth = new DateTime("$year-$month-01");
    $week = $firstDayOfMonth->format("W");
    $weekNumbers = [];
    while ($firstDayOfMonth->format("m") == $month) {
        $sundayOfCurrentWeek = clone $firstDayOfMonth;
        $sundayOfCurrentWeek->modify('next Sunday');
        if ($sundayOfCurrentWeek->format("m") == $month) {
            $select_sem = $db->query("SELECT bb.id_employer, e.employer_nom, e.employer_prenom, SUM(TIME_TO_SEC(TIMEDIFF(bb.badge_date_sortie, bb.badge_date_entree))) / 3600 AS heures_travaillees_semaine FROM badgeuse_badge bb JOIN badgeuse_employer e ON bb.id_employer = e.id_employer WHERE WEEK(bb.badge_date_entree, 3) = ".$week." AND YEAR(bb.badge_date_entree) = ".securisation($_GET['annee'])." AND bb.id_employer = ".$employer["id_employer"]." GROUP BY bb.id_employer, e.employer_nom, e.employer_prenom;")->fetchArray();
            $selectTotHeurAbs_plan = $db->query("SELECT SUM( TIME_TO_SEC( TIMEDIFF( planning_sortie, planning_entree ) ) ) / 3600 AS heures_travaillees_malade FROM badgeuse_planning WHERE WEEK(planning_entree, 3) = ".$week." AND YEAR(planning_entree) = ".securisation($_GET['annee'])." AND id_employer = ".$employer["id_employer"]." AND abs_type != 'NO' AND abs_type != 'AI' ")->fetchArray();
            if(isset($select_sem["heures_travaillees_semaine"]) or isset($selectTotHeurAbs_plan['heures_travaillees_malade'])){
                $weekNumbers[] = $select_sem["heures_travaillees_semaine"] + $selectTotHeurAbs_plan['heures_travaillees_malade'];
            }else{
                $weekNumbers[] = 0.0;
            }
        }
        $firstDayOfMonth->modify('next Monday');
        $week++;
    }

    $sheet1->setCellValue("A$c", $employer['employer_nom']);
    $sheet1->setCellValue("B$c", $employer['employer_prenom']);
    $sheet1->setCellValue("C$c", $employer['volume_horaire_hebdo']);
    $sheet1->setCellValue("D$c", $employer['volume_horaire_hebdo']*52/12);

    $select_sem = $db->query("SELECT COUNT(DISTINCT DATE(bb.badge_date_entree)) AS jours_travailles FROM badgeuse_badge bb WHERE MONTH(bb.badge_date_entree) = ".securisation($_GET['mois'])." AND YEAR(bb.badge_date_entree) = ".securisation($_GET['annee'])." AND bb.id_employer = ".$employer["id_employer"].";")->fetchArray();
    $sheet1->setCellValue("E$c", $select_sem['jours_travailles']);

    $selectTotHeurAbs = $db->query("SELECT SUM( TIME_TO_SEC( TIMEDIFF( planning_sortie, planning_entree ) ) ) / 3600 AS heures_travaillees_malade FROM badgeuse_planning WHERE MONTH(planning_entree) = ".securisation($_GET['mois'])." AND YEAR(planning_entree) = ".securisation($_GET['annee'])." AND id_employer = ".$employer["id_employer"]." AND abs_type != 'NO' AND abs_type != 'AI' ")->fetchArray();
    $sheet1->setCellValue("F$c", $employer['heures_travaillees_mois']+$selectTotHeurAbs['heures_travaillees_malade']);


    $select_repas = $db->query("SELECT * FROM badgeuse_badge WHERE id_employer = ".$employer['id_employer']." AND MONTH(badge_date_entree) = ".securisation($_GET['mois'])." and YEAR(badge_date_entree) = ".securisation($_GET['annee']))->numRows();
    if ($nbSemaine == 4){
        $sheet1->setCellValue("G$c", "=".$weekNumbers[0]."-C$c");
        $sheet1->setCellValue("H$c", "=".$weekNumbers[1]."-C$c");
        $sheet1->setCellValue("I$c", "=".$weekNumbers[2]."-C$c");
        $sheet1->setCellValue("J$c", "=".$weekNumbers[3]."-C$c");
        $sheet1->setCellValue("K$c", "=MAX(IF(G$c>(C$c/10), C$c/10, G$c), 0)");
        $sheet1->setCellValue("L$c", "=MAX(IF(H$c>(C$c/10), C$c/10, H$c), 0)");
        $sheet1->setCellValue("M$c", "=MAX(IF(I$c>(C$c/10), C$c/10, I$c), 0)");
        $sheet1->setCellValue("N$c", "=MAX(IF(J$c>(C$c/10), C$c/10, J$c), 0)");
        $sheet1->setCellValue("O$c", "=MAX(G$c-K$c, 0)");
        $sheet1->setCellValue("P$c", "=MAX(H$c-L$c, 0)");
        $sheet1->setCellValue("Q$c", "=MAX(I$c-M$c, 0)");
        $sheet1->setCellValue("R$c", "=MAX(J$c-N$c, 0)");
        $sheet1->setCellValue("S$c", "=SUM(K$c:N$c)");
        $sheet1->setCellValue("T$c", "=SUM(O$c:R$c)");
        $sheet1->setCellValue("U$c", $select_repas);

    }elseif ($nbSemaine == 5){
        $sheet1->setCellValue("G$c", "=".$weekNumbers[0]."-C$c");
        $sheet1->setCellValue("H$c", "=".$weekNumbers[1]."-C$c");
        $sheet1->setCellValue("I$c", "=".$weekNumbers[2]."-C$c");
        $sheet1->setCellValue("J$c", "=".$weekNumbers[3]."-C$c");
        $sheet1->setCellValue("K$c", "=".$weekNumbers[4]."-C$c");
        $sheet1->setCellValue("L$c", "=MAX(IF(G$c>(C$c/10), C$c/10, G$c), 0)");
        $sheet1->setCellValue("M$c", "=MAX(IF(H$c>(C$c/10), C$c/10, H$c), 0)");
        $sheet1->setCellValue("N$c", "=MAX(IF(I$c>(C$c/10), C$c/10, I$c), 0)");
        $sheet1->setCellValue("O$c", "=MAX(IF(J$c>(C$c/10), C$c/10, J$c), 0)");
        $sheet1->setCellValue("P$c", "=MAX(IF(K$c>(C$c/10), C$c/10, K$c), 0)");
        $sheet1->setCellValue("Q$c", "=MAX(G$c-L$c, 0)");
        $sheet1->setCellValue("R$c", "=MAX(H$c-M$c, 0)");
        $sheet1->setCellValue("S$c", "=MAX(I$c-N$c, 0)");
        $sheet1->setCellValue("T$c", "=MAX(J$c-O$c, 0)");
        $sheet1->setCellValue("U$c", "=MAX(K$c-P$c, 0)");
        $sheet1->setCellValue("V$c", "=SUM(L$c:P$c)");
        $sheet1->setCellValue("W$c", "=SUM(Q$c:U$c)");
        $sheet1->setCellValue("X$c", $select_repas);
    }
    $c++;
}

if ($_SESSION['etablissement'] == 2) {
    $c = $c + 2;
    $sheet1->setCellValue("A$c", 'Nom');
    $sheet1->setCellValue("B$c", 'Prénom');
    $sheet1->setCellValue("C$c", 'Volume Horaire Hebdomadaire (h)*');
    $sheet1->setCellValue("D$c", 'Volume Horaire Mensuel (h)');
    $sheet1->setCellValue("E$c", 'Jours Travaillés (j)');
    $sheet1->setCellValue("F$c", "Heures Travaillées (h)");
    $sheet1->setCellValue("G$c", "S".($weekCalc+0)." (Heures +/-)");
    $sheet1->setCellValue("H$c", "S".($weekCalc+1)." (Heures +/-)");
    $sheet1->setCellValue("I$c", "S".($weekCalc+2)." (Heures +/-)");
    $sheet1->setCellValue("J$c", "S".($weekCalc+3)." (Heures +/-)");
    if ($nbSemaine == 4){
        $sheet1->setCellValue("K$c", "S".($weekCalc+0)." (Heures ±)  heure majoré 10 %");
        $sheet1->setCellValue("L$c", "S".($weekCalc+1)." (Heures ±)  heure majoré 10 %");
        $sheet1->setCellValue("M$c", "S".($weekCalc+2)." (Heures ±)  heure majoré 10 %");
        $sheet1->setCellValue("N$c", "S".($weekCalc+3)." (Heures ±)  heure majoré 10 %");
        $sheet1->setCellValue("O$c", "S".($weekCalc+0)." (Heures ±)  heure majoré 20 %");
        $sheet1->setCellValue("P$c", "S".($weekCalc+1)." (Heures ±)  heure majoré 20 %");
        $sheet1->setCellValue("Q$c", "S".($weekCalc+2)." (Heures ±)  heure majoré 20 %");
        $sheet1->setCellValue("R$c", "S".($weekCalc+3)." (Heures ±)  heure majoré 20 %");
        $sheet1->setCellValue("S$c", "S".($weekCalc+0)." (Heures ±)  heure majoré 50 %");
        $sheet1->setCellValue("T$c", "S".($weekCalc+1)." (Heures ±)  heure majoré 50 %");
        $sheet1->setCellValue("U$c", "S".($weekCalc+2)." (Heures ±)  heure majoré 50 %");
        $sheet1->setCellValue("V$c", "S".($weekCalc+3)." (Heures ±)  heure majoré 50 %");
        $sheet1->setCellValue("W$c", "Total heure majoré 10 %");
        $sheet1->setCellValue("X$c", "Total heure majoré 20 %");
        $sheet1->setCellValue("Y$c", "Total heure majoré 50 %");
        $sheet1->setCellValue("Z$c", "Repas Dûs");
    }elseif ($nbSemaine == 5){
        $sheet1->setCellValue("K$c", "S".($weekCalc+4)." (Heures +/-)");
        $sheet1->setCellValue("L$c", "S".($weekCalc+0)." (Heures ±)  heure majoré 10 %");
        $sheet1->setCellValue("M$c", "S".($weekCalc+1)." (Heures ±)  heure majoré 10 %");
        $sheet1->setCellValue("N$c", "S".($weekCalc+2)." (Heures ±)  heure majoré 10 %");
        $sheet1->setCellValue("O$c", "S".($weekCalc+3)." (Heures ±)  heure majoré 10 %");
        $sheet1->setCellValue("P$c", "S".($weekCalc+4)." (Heures ±)  heure majoré 10 %");
        $sheet1->setCellValue("Q$c", "S".($weekCalc+0)." (Heures ±)  heure majoré 20 %");
        $sheet1->setCellValue("R$c", "S".($weekCalc+1)." (Heures ±)  heure majoré 20 %");
        $sheet1->setCellValue("S$c", "S".($weekCalc+2)." (Heures ±)  heure majoré 20 %");
        $sheet1->setCellValue("T$c", "S".($weekCalc+3)." (Heures ±)  heure majoré 20 %");
        $sheet1->setCellValue("U$c", "S".($weekCalc+4)." (Heures ±)  heure majoré 20 %");
        $sheet1->setCellValue("V$c", "S".($weekCalc+0)." (Heures ±)  heure majoré 50 %");
        $sheet1->setCellValue("W$c", "S".($weekCalc+1)." (Heures ±)  heure majoré 50 %");
        $sheet1->setCellValue("X$c", "S".($weekCalc+2)." (Heures ±)  heure majoré 50 %");
        $sheet1->setCellValue("Y$c", "S".($weekCalc+3)." (Heures ±)  heure majoré 50 %");
        $sheet1->setCellValue("Z$c", "S".($weekCalc+4)." (Heures ±)  heure majoré 50 %");
        $sheet1->setCellValue("AA$c", "Total heure majoré 10 %");
        $sheet1->setCellValue("AB$c", "Total heure majoré 20 %");
        $sheet1->setCellValue("AC$c", "Total heure majoré 50 %");
        $sheet1->setCellValue("AD$c", "Repas Dûs");
    }


    $sheet1->getStyle("A$c")->applyFromArray($style1);
    $sheet1->getStyle("B$c")->applyFromArray($style1);
    $sheet1->getStyle("C$c")->applyFromArray($style2);
    $sheet1->getStyle("D$c")->applyFromArray($style2);
    $sheet1->getStyle("E$c")->applyFromArray($style2);
    $sheet1->getStyle("F$c")->applyFromArray($style2);

    if ($nbSemaine == 4){
        $sheet1->getStyle("G$c")->applyFromArray($style3);
        $sheet1->getStyle("H$c")->applyFromArray($style3);
        $sheet1->getStyle("I$c")->applyFromArray($style3);
        $sheet1->getStyle("J$c")->applyFromArray($style3);
        $sheet1->getStyle("K$c")->applyFromArray($style5);
        $sheet1->getStyle("L$c")->applyFromArray($style5);
        $sheet1->getStyle("M$c")->applyFromArray($style5);
        $sheet1->getStyle("N$c")->applyFromArray($style5);
        $sheet1->getStyle("O$c")->applyFromArray($style6);
        $sheet1->getStyle("P$c")->applyFromArray($style6);
        $sheet1->getStyle("Q$c")->applyFromArray($style6);
        $sheet1->getStyle("R$c")->applyFromArray($style6);
        $sheet1->getStyle("S$c")->applyFromArray($style7);
        $sheet1->getStyle("T$c")->applyFromArray($style7);
        $sheet1->getStyle("U$c")->applyFromArray($style7);
        $sheet1->getStyle("V$c")->applyFromArray($style7);
        $sheet1->getStyle("W$c")->applyFromArray($style5);
        $sheet1->getStyle("X$c")->applyFromArray($style6);
        $sheet1->getStyle("Y$c")->applyFromArray($style7);
        $sheet1->getStyle("Z$c")->applyFromArray($style8);
    }elseif ($nbSemaine == 5){
        $sheet1->getStyle("G$c")->applyFromArray($style3);
        $sheet1->getStyle("H$c")->applyFromArray($style3);
        $sheet1->getStyle("I$c")->applyFromArray($style3);
        $sheet1->getStyle("J$c")->applyFromArray($style3);
        $sheet1->getStyle("K$c")->applyFromArray($style3);
        $sheet1->getStyle("L$c")->applyFromArray($style5);
        $sheet1->getStyle("M$c")->applyFromArray($style5);
        $sheet1->getStyle("N$c")->applyFromArray($style5);
        $sheet1->getStyle("O$c")->applyFromArray($style5);
        $sheet1->getStyle("P$c")->applyFromArray($style5);
        $sheet1->getStyle("Q$c")->applyFromArray($style6);
        $sheet1->getStyle("R$c")->applyFromArray($style6);
        $sheet1->getStyle("S$c")->applyFromArray($style6);
        $sheet1->getStyle("T$c")->applyFromArray($style6);
        $sheet1->getStyle("U$c")->applyFromArray($style6);
        $sheet1->getStyle("V$c")->applyFromArray($style7);
        $sheet1->getStyle("W$c")->applyFromArray($style7);
        $sheet1->getStyle("X$c")->applyFromArray($style7);
        $sheet1->getStyle("Y$c")->applyFromArray($style7);
        $sheet1->getStyle("Z$c")->applyFromArray($style7);
        $sheet1->getStyle("AA$c")->applyFromArray($style5);
        $sheet1->getStyle("AB$c")->applyFromArray($style6);
        $sheet1->getStyle("AC$c")->applyFromArray($style7);
        $sheet1->getStyle("AD$c")->applyFromArray($style8);
    }


    $columnIndex = 'A';
    $lastColumnIndex = 'Z'; // Mettez à jour ceci avec la dernière colonne de votre feuille
    while ($columnIndex <= $lastColumnIndex) {
        $sheet1->getColumnDimension($columnIndex)->setAutoSize(true);
        $columnIndex++;
    }
    if ($nbSemaine == 5){
        $sheet1->getColumnDimension("AA")->setAutoSize(true);
        $sheet1->getColumnDimension("AB")->setAutoSize(true);
        $sheet1->getColumnDimension("AC")->setAutoSize(true);
        $sheet1->getColumnDimension("AD")->setAutoSize(true);
    }

    $c++;
    $select_bage = $db->query("SELECT bb.id_employer, e.employer_nom, e.employer_prenom, SUM( TIME_TO_SEC( TIMEDIFF( bb.badge_date_sortie, bb.badge_date_entree ) ) ) / 3600 AS heures_travaillees_mois, c.vol_h AS volume_horaire_hebdo FROM badgeuse_badge bb JOIN badgeuse_employer e ON bb.id_employer = e.id_employer JOIN badgeuse_contrat c ON bb.id_employer = c.id_employer JOIN badgeuse_etablissements eta ON c.id_etablissement = eta.id_etablissement WHERE c.vol_h >= 35 AND MONTH(bb.badge_date_entree) = ".securisation($_GET['mois'])." AND YEAR(bb.badge_date_entree) = ".securisation($_GET['annee'])." AND eta.id_etablissement = ".$_SESSION['etablissement']." GROUP BY bb.id_employer, e.employer_nom, e.employer_prenom, c.vol_h;")->fetchAll();
    foreach ($select_bage as $employer){

        // calcul pour heure sup
        $year = securisation($_GET['annee']);
        $month = securisation($_GET['mois']);
        $firstDayOfMonth = new DateTime("$year-$month-01" );
        $week = $firstDayOfMonth->format("W");
        $weekNumbers = [];
        while ($firstDayOfMonth->format("m") == $month) {
            $sundayOfCurrentWeek = clone $firstDayOfMonth;
            $sundayOfCurrentWeek->modify('next Sunday');
            if ($sundayOfCurrentWeek->format("m") == $month) {
                $select_sem = $db->query("SELECT bb.id_employer, e.employer_nom, e.employer_prenom, SUM(TIME_TO_SEC(TIMEDIFF(bb.badge_date_sortie, bb.badge_date_entree))) / 3600 AS heures_travaillees_semaine FROM badgeuse_badge bb JOIN badgeuse_employer e ON bb.id_employer = e.id_employer WHERE WEEK(bb.badge_date_entree, 3) = ".$week." AND YEAR(bb.badge_date_entree) = ".securisation($_GET['annee'])." AND bb.id_employer = ".$employer["id_employer"]." GROUP BY bb.id_employer, e.employer_nom, e.employer_prenom;")->fetchArray();
                $selectTotHeurAbs_plan = $db->query("SELECT SUM( TIME_TO_SEC( TIMEDIFF( planning_sortie, planning_entree ) ) ) / 3600 AS heures_travaillees_malade FROM badgeuse_planning WHERE WEEK(planning_entree, 3) = ".$week." AND YEAR(planning_entree) = ".securisation($_GET['annee'])." AND id_employer = ".$employer["id_employer"]." AND abs_type != 'NO' AND abs_type != 'AI' ")->fetchArray();
                if(isset($select_sem["heures_travaillees_semaine"]) or isset($selectTotHeurAbs_plan['heures_travaillees_malade'])){
                    $weekNumbers[] = $select_sem["heures_travaillees_semaine"] + $selectTotHeurAbs_plan['heures_travaillees_malade'];
                }else{
                    $weekNumbers[] = 0.0;
                }
            }
            $firstDayOfMonth->modify('next Monday');
            $week++;
        }

        $sheet1->setCellValue("A$c", $employer['employer_nom']);
        $sheet1->setCellValue("B$c", $employer['employer_prenom']);
        $sheet1->setCellValue("C$c", $employer['volume_horaire_hebdo']);
        $sheet1->setCellValue("D$c", $employer['volume_horaire_hebdo']*52/12);

        $select_sem = $db->query("SELECT COUNT(DISTINCT DATE(bb.badge_date_entree)) AS jours_travailles FROM badgeuse_badge bb WHERE MONTH(bb.badge_date_entree) = ".securisation($_GET['mois'])." AND YEAR(bb.badge_date_entree) = ".securisation($_GET['annee'])." AND bb.id_employer = ".$employer["id_employer"].";")->fetchArray();
        $sheet1->setCellValue("E$c", $select_sem['jours_travailles']);

        $selectTotHeurAbs = $db->query("SELECT SUM( TIME_TO_SEC( TIMEDIFF( planning_sortie, planning_entree ) ) ) / 3600 AS heures_travaillees_malade FROM badgeuse_planning WHERE MONTH(planning_entree) = ".securisation($_GET['mois'])." AND YEAR(planning_entree) = ".securisation($_GET['annee'])." AND id_employer = ".$employer["id_employer"]." AND abs_type != 'NO' AND abs_type != 'AI' ")->fetchArray();
        $sheet1->setCellValue("F$c", $employer['heures_travaillees_mois']+$selectTotHeurAbs['heures_travaillees_malade']);


        $select_repas = $db->query("SELECT * FROM badgeuse_badge WHERE id_employer = ".$employer['id_employer']." AND MONTH(badge_date_entree) = ".securisation($_GET['mois'])." and YEAR(badge_date_entree) = ".securisation($_GET['annee']))->numRows();
        if ($nbSemaine == 4){
            $sheet1->setCellValue("G$c", "=".$weekNumbers[0]."-C$c");
            $sheet1->setCellValue("H$c", "=".$weekNumbers[1]."-C$c");
            $sheet1->setCellValue("I$c", "=".$weekNumbers[2]."-C$c");
            $sheet1->setCellValue("J$c", "=".$weekNumbers[3]."-C$c");

            $sheet1->setCellValue("K$c", "=MAX(IF(G$c>4, 4, G$c), 0)");
            $sheet1->setCellValue("L$c", "=MAX(IF(H$c>4, 4, H$c), 0)");
            $sheet1->setCellValue("M$c", "=MAX(IF(I$c>4, 4, I$c), 0)");
            $sheet1->setCellValue("N$c", "=MAX(IF(J$c>4, 4, J$c), 0)");

            $sheet1->setCellValue("O$c", "=MAX(IF(G$c>8, 4, IF(G$c>4, G$c-4, 0)), 0)");
            $sheet1->setCellValue("P$c", "=MAX(IF(H$c>8, 4, IF(H$c>4, H$c-4, 0)), 0)");
            $sheet1->setCellValue("Q$c", "=MAX(IF(I$c>8, 4, IF(I$c>4, I$c-4, 0)), 0)");
            $sheet1->setCellValue("R$c", "=MAX(IF(J$c>8, 4, IF(J$c>4, J$c-4, 0)), 0)");

            $sheet1->setCellValue("S$c", "=MAX(G$c-K$c-O$c, 0)");
            $sheet1->setCellValue("T$c", "=MAX(H$c-L$c-P$c, 0)");
            $sheet1->setCellValue("U$c", "=MAX(I$c-M$c-Q$c, 0)");
            $sheet1->setCellValue("V$c", "=MAX(J$c-N$c-R$c, 0)");

            $sheet1->setCellValue("W$c", "=SUM(K$c:N$c)");
            $sheet1->setCellValue("X$c", "=SUM(O$c:R$c)");
            $sheet1->setCellValue("Y$c", "=SUM(S$c:V$c)");
            $sheet1->setCellValue("Z$c", $select_repas);

        }elseif ($nbSemaine == 5){
            $sheet1->setCellValue("G$c", "=".$weekNumbers[0]."-C$c");
            $sheet1->setCellValue("H$c", "=".$weekNumbers[1]."-C$c");
            $sheet1->setCellValue("I$c", "=".$weekNumbers[2]."-C$c");
            $sheet1->setCellValue("J$c", "=".$weekNumbers[3]."-C$c");
            $sheet1->setCellValue("K$c", "=".$weekNumbers[4]."-C$c");

            $sheet1->setCellValue("L$c", "=MAX(IF(G$c>4, 4, G$c), 0)");
            $sheet1->setCellValue("M$c", "=MAX(IF(H$c>4, 4, H$c), 0)");
            $sheet1->setCellValue("N$c", "=MAX(IF(I$c>4, 4, I$c), 0)");
            $sheet1->setCellValue("O$c", "=MAX(IF(J$c>4, 4, J$c), 0)");
            $sheet1->setCellValue("P$c", "=MAX(IF(K$c>4, 4, K$c), 0)");

            $sheet1->setCellValue("Q$c", "=MAX(IF(G$c>8, 4, IF(G$c>4, G$c-4, 0)), 0)");
            $sheet1->setCellValue("R$c", "=MAX(IF(H$c>8, 4, IF(H$c>4, H$c-4, 0)), 0)");
            $sheet1->setCellValue("S$c", "=MAX(IF(I$c>8, 4, IF(I$c>4, I$c-4, 0)), 0)");
            $sheet1->setCellValue("T$c", "=MAX(IF(J$c>8, 4, IF(J$c>4, J$c-4, 0)), 0)");
            $sheet1->setCellValue("U$c", "=MAX(IF(K$c>8, 4, IF(K$c>4, K$c-4, 0)), 0)");

            $sheet1->setCellValue("V$c", "=MAX(G$c-L$c-Q$c, 0)");
            $sheet1->setCellValue("W$c", "=MAX(H$c-M$c-R$c, 0)");
            $sheet1->setCellValue("X$c", "=MAX(I$c-N$c-S$c, 0)");
            $sheet1->setCellValue("Y$c", "=MAX(J$c-O$c-T$c, 0)");
            $sheet1->setCellValue("Z$c", "=MAX(K$c-P$c-U$c, 0)");

            $sheet1->setCellValue("AA$c", "=SUM(L$c:P$c)");
            $sheet1->setCellValue("AB$c", "=SUM(Q$c:U$c)");
            $sheet1->setCellValue("AC$c", "=SUM(V$c:Z$c)");
            $sheet1->setCellValue("AD$c", $select_repas);
        }
        $c++;
    }
}
$sheet1->calculateColumnWidths();



// Création de la deuxième feuille
$spreadsheet->createSheet();
$sheet2 = $spreadsheet->getSheet(1); // Accès à la deuxième feuille
$sheet2->setTitle('Détails');
$sheet2->setCellValue('A1', 'Prénom');
$sheet2->setCellValue('B1', 'Nom');
$sheet2->setCellValue('C1', 'Type de contrat');
$sheet2->setCellValue('D1', 'Temps contractuel');
$sheet2->setCellValue('E1', 'Etablissement principal');
$sheet2->setCellValue('F1', 'N° semaine');
$sheet2->setCellValue('G1', 'Date');
$sheet2->setCellValue('H1', 'Début');
$sheet2->setCellValue('I1', 'Fin');
$sheet2->setCellValue('J1', 'Retard (h)');
$sheet2->setCellValue('K1', 'Heures Travaillées (h)');
$sheet2->setCellValue('L1', 'Absences Incluses dans le Compteur');
$sheet2->setCellValue('M1', 'Absences non incluses dans le Compteur');
$sheet2->setCellValue('N1', 'Repas Dûs');
$sheet2->setCellValue('O1', 'Type Badge');
$sheet2->setCellValue('P1', 'Type Absence');
$sheet2->setCellValue('Q1', 'Ancien badge d\'entrée');
$sheet2->setCellValue('R1', 'Ancien badge de sortie');


$sheet2->getStyle('A1')->applyFromArray($style);
$sheet2->getStyle('B1')->applyFromArray($style);
$sheet2->getStyle('C1')->applyFromArray($style);
$sheet2->getStyle('D1')->applyFromArray($style);
$sheet2->getStyle('E1')->applyFromArray($style);
$sheet2->getStyle('F1')->applyFromArray($style);
$sheet2->getStyle('G1')->applyFromArray($style);
$sheet2->getStyle('H1')->applyFromArray($style);
$sheet2->getStyle('I1')->applyFromArray($style);
$sheet2->getStyle('J1')->applyFromArray($style);
$sheet2->getStyle('K1')->applyFromArray($style);
$sheet2->getStyle('L1')->applyFromArray($style);
$sheet2->getStyle('M1')->applyFromArray($style);
$sheet2->getStyle('N1')->applyFromArray($style);
$sheet2->getStyle('O1')->applyFromArray($style);
$sheet2->getStyle('P1')->applyFromArray($style);
$sheet2->getStyle('Q1')->applyFromArray($style);
$sheet2->getStyle('R1')->applyFromArray($style);




$columnIndex = 'A';
$lastColumnIndex = 'R'; // Mettez à jour ceci avec la dernière colonne de votre feuille
while ($columnIndex <= $lastColumnIndex) {
    $sheet2->getColumnDimension($columnIndex)->setAutoSize(true);
    $columnIndex++;
}



$select_detail = $db->query("SELECT bb.id_employer, e.employer_nom, e.employer_prenom, c.type_contrat, c.vol_h, et.etablissement_nom, WEEK(bb.badge_date_entree, 3) AS num_semaine, DATE_FORMAT(bb.badge_date_entree, '%d/%m/%Y') AS date_entree, TIME_FORMAT(bb.badge_date_entree, '%H:%i') AS heure_entree, TIME_FORMAT(bb.badge_date_sortie, '%H:%i') AS heure_sortie, bb.badge_date_entree_dernier, bb.badge_date_sortie_dernier, TIME_TO_SEC( TIMEDIFF(bb.badge_date_sortie, bb.badge_date_entree) ) / 3600 AS heures_travaillees, bb.cron FROM badgeuse_badge bb JOIN badgeuse_employer e ON bb.id_employer = e.id_employer JOIN badgeuse_contrat c ON bb.id_employer = c.id_employer JOIN badgeuse_etablissements et ON c.id_etablissement = et.id_etablissement WHERE MONTH(bb.badge_date_entree) = ".securisation($_GET['mois'])." AND YEAR(bb.badge_date_entree) = ".securisation($_GET['annee'])." AND et.id_etablissement = ".$_SESSION['etablissement']." ORDER BY bb.id_employer, bb.badge_date_entree;")->fetchAll();
$c = 2;
foreach ($select_detail as $detail){
    $sheet2->setCellValue("A$c", $detail["employer_prenom"]);
    $sheet2->setCellValue("B$c", $detail["employer_nom"]);
    $sheet2->setCellValue("C$c", $detail["type_contrat"]);
    $sheet2->setCellValue("D$c", $detail["vol_h"]);
    $sheet2->setCellValue("E$c", $detail["etablissement_nom"]);
    $sheet2->setCellValue("F$c", $detail["num_semaine"]);
    $sheet2->setCellValue("G$c", $detail["date_entree"]);
    $sheet2->setCellValue("H$c", $detail["heure_entree"]);
    $sheet2->setCellValue("I$c", $detail["heure_sortie"]);
    $sheet2->setCellValue("J$c", 'Retard (h)');
    $sheet2->setCellValue("K$c", $detail["heures_travaillees"]);
    $sheet2->setCellValue("L$c", '');
    $sheet2->setCellValue("M$c", '');
    $sheet2->setCellValue("N$c", '');
    $sheet2->setCellValue("O$c", '');
    $sheet2->setCellValue("P$c", '');
    $sheet2->setCellValue("Q$c", '');
    $sheet2->setCellValue("R$c", '');

    if ($detail["heures_travaillees"] > 0.5){
        $sheet2->setCellValue("N$c", '1');
    }

    if ($detail["cron"] == 1){
        $sheet2->setCellValue("O$c", "Erreur de badge");
    }elseif ($detail["cron"] == 2){
        $sheet2->setCellValue("O$c", "Badge corrigé après erreur");
    }elseif ($detail["cron"] == 3){
        $sheet2->setCellValue("O$c", "Badge Modifié");
    }elseif ($detail["cron"] == 4){
        $sheet2->setCellValue("O$c", "Badge Ajouté");
    }

    if ($detail["badge_date_entree_dernier"] == NULL){
        $sheet2->setCellValue("Q$c", $detail["badge_date_entree_dernier"]);
    }
    if ($detail["badge_date_sortie_dernier"] == NULL){
        $sheet2->setCellValue("R$c", $detail["badge_date_sortie_dernier"]);
    }
    $c++;
}
$select_detail2_abs = $db->query("SELECT bp.id_employer, e.employer_nom, e.employer_prenom, c.type_contrat, c.vol_h, et.etablissement_nom, WEEK(bp.planning_entree, 3) AS num_semaine, DATE_FORMAT(bp.planning_entree, '%d/%m/%Y') AS date_entree, TIME_FORMAT(bp.planning_entree, '%H:%i') AS heure_entree, TIME_FORMAT(bp.planning_sortie, '%H:%i') AS heure_sortie, TIME_TO_SEC( TIMEDIFF( bp.planning_sortie, bp.planning_entree ) ) / 3600 AS heures_travaillees, bp.abs_type FROM badgeuse_planning bp JOIN badgeuse_employer e ON bp.id_employer = e.id_employer JOIN badgeuse_contrat c ON bp.id_employer = c.id_employer JOIN badgeuse_etablissements et ON c.id_etablissement = et.id_etablissement WHERE MONTH(bp.planning_entree) = ".securisation($_GET['mois'])." AND YEAR(bp.planning_entree) = ".securisation($_GET['annee'])." AND et.id_etablissement = ".$_SESSION['etablissement']." AND bp.abs_type != 'NO' ORDER BY bp.id_employer, bp.planning_entree;")->fetchAll();
foreach ($select_detail2_abs as $detail){
    $sheet2->setCellValue("A$c", $detail["employer_prenom"]);
    $sheet2->setCellValue("B$c", $detail["employer_nom"]);
    $sheet2->setCellValue("C$c", $detail["type_contrat"]);
    $sheet2->setCellValue("D$c", $detail["vol_h"]);
    $sheet2->setCellValue("E$c", $detail["etablissement_nom"]);
    $sheet2->setCellValue("F$c", $detail["num_semaine"]);
    $sheet2->setCellValue("G$c", $detail["date_entree"]);
    $sheet2->setCellValue("H$c", $detail["heure_entree"]);
    $sheet2->setCellValue("I$c", $detail["heure_sortie"]);
    $sheet2->setCellValue("J$c", '');
    $sheet2->setCellValue("K$c", $detail["heures_travaillees"]);

    if ($detail['abs_type'] != "AI"){
        $sheet2->setCellValue("L$c", $detail["heures_travaillees"]);
        $sheet2->setCellValue("M$c", '');
    }else{
        $sheet2->setCellValue("L$c", '');
        $sheet2->setCellValue("M$c", $detail["heures_travaillees"]);
    }
    $sheet2->setCellValue("N$c", '');

    $sheet2->setCellValue("O$c", "");
    $sheet2->setCellValue("P$c", $detail['abs_type']);

    $c++;
}

$sheet2->calculateColumnWidths();






// Création des feuilles pour chaque emloyer
$select_emlpoyer = $db->query("SELECT badgeuse_employer.id_employer, employer_prenom, employer_nom FROM badgeuse_employer JOIN badgeuse_contrat ON badgeuse_contrat.id_employer = badgeuse_employer.id_employer WHERE badgeuse_contrat.id_etablissement = ".$_SESSION['etablissement'])->fetchAll();

$boucle_compteur = 2;
foreach ($select_emlpoyer as $employer_selectionner){

    $select_detail = $db->query("SELECT bb.id_employer, e.employer_nom, e.employer_prenom, c.type_contrat, c.vol_h, et.etablissement_nom, WEEK(bb.badge_date_entree, 3) AS num_semaine, DATE_FORMAT(bb.badge_date_entree, '%d/%m/%Y') AS date_entree, TIME_FORMAT(bb.badge_date_entree, '%H:%i') AS heure_entree, TIME_FORMAT(bb.badge_date_sortie, '%H:%i') AS heure_sortie, bb.badge_date_entree_dernier, bb.badge_date_sortie_dernier, TIME_TO_SEC( TIMEDIFF(bb.badge_date_sortie, bb.badge_date_entree) ) / 3600 AS heures_travaillees, bb.cron FROM badgeuse_badge bb JOIN badgeuse_employer e ON bb.id_employer = e.id_employer JOIN badgeuse_contrat c ON bb.id_employer = c.id_employer JOIN badgeuse_etablissements et ON c.id_etablissement = et.id_etablissement WHERE bb.id_employer = ".$employer_selectionner["id_employer"]." AND MONTH(bb.badge_date_entree) = ".securisation($_GET['mois'])." AND YEAR(bb.badge_date_entree) = ".securisation($_GET['annee'])." AND et.id_etablissement = ".$_SESSION['etablissement']." ORDER BY bb.id_employer, bb.badge_date_entree;")->fetchAll();
    $select_detail2_abs = $db->query("SELECT bp.id_employer, e.employer_nom, e.employer_prenom, c.type_contrat, c.vol_h, et.etablissement_nom, WEEK(bp.planning_entree, 3) AS num_semaine, DATE_FORMAT(bp.planning_entree, '%d/%m/%Y') AS date_entree, TIME_FORMAT(bp.planning_entree, '%H:%i') AS heure_entree, TIME_FORMAT(bp.planning_sortie, '%H:%i') AS heure_sortie, TIME_TO_SEC( TIMEDIFF( bp.planning_sortie, bp.planning_entree ) ) / 3600 AS heures_travaillees, bp.abs_type FROM badgeuse_planning bp JOIN badgeuse_employer e ON bp.id_employer = e.id_employer JOIN badgeuse_contrat c ON bp.id_employer = c.id_employer JOIN badgeuse_etablissements et ON c.id_etablissement = et.id_etablissement WHERE bp.id_employer = ".$employer_selectionner["id_employer"]." AND MONTH(bp.planning_entree) = ".securisation($_GET['mois'])." AND YEAR(bp.planning_entree) = ".securisation($_GET['annee'])." AND et.id_etablissement = ".$_SESSION['etablissement']." AND bp.abs_type != 'NO' ORDER BY bp.id_employer, bp.planning_entree;")->fetchAll();

    if (count($select_detail) != 0 or count($select_detail2_abs) != 0){
        $spreadsheet->createSheet();
        $sheetTemps = $spreadsheet->getSheet($boucle_compteur); // Accès à la deuxième feuille
        $sheetTemps->setTitle('Détails '.$employer_selectionner["employer_prenom"]." ".$employer_selectionner["employer_nom"]);
        $sheetTemps->setCellValue('A1', 'Prénom');
        $sheetTemps->setCellValue('B1', 'Nom');
        $sheetTemps->setCellValue('C1', 'Type de contrat');
        $sheetTemps->setCellValue('D1', 'Temps contractuel');
        $sheetTemps->setCellValue('E1', 'Etablissement principal');
        $sheetTemps->setCellValue('F1', 'N° semaine');
        $sheetTemps->setCellValue('G1', 'Date');
        $sheetTemps->setCellValue('H1', 'Début');
        $sheetTemps->setCellValue('I1', 'Fin');
        $sheetTemps->setCellValue('J1', 'Retard (h)');
        $sheetTemps->setCellValue('K1', 'Heures Travaillées (h)');
        $sheetTemps->setCellValue('L1', 'Absences Incluses dans le Compteur');
        $sheetTemps->setCellValue('M1', 'Absences non incluses dans le Compteur');
        $sheetTemps->setCellValue('N1', 'Repas Dûs');
        $sheetTemps->setCellValue('O1', 'Type Badge');
        $sheetTemps->setCellValue('P1', 'Type Absence');
        $sheetTemps->setCellValue('Q1', 'Ancien badge d\'entrée');
        $sheetTemps->setCellValue('R1', 'Ancien badge de sortie');

        $sheetTemps->getStyle('A1')->applyFromArray($style);
        $sheetTemps->getStyle('B1')->applyFromArray($style);
        $sheetTemps->getStyle('C1')->applyFromArray($style);
        $sheetTemps->getStyle('D1')->applyFromArray($style);
        $sheetTemps->getStyle('E1')->applyFromArray($style);
        $sheetTemps->getStyle('F1')->applyFromArray($style);
        $sheetTemps->getStyle('G1')->applyFromArray($style);
        $sheetTemps->getStyle('H1')->applyFromArray($style);
        $sheetTemps->getStyle('I1')->applyFromArray($style);
        $sheetTemps->getStyle('J1')->applyFromArray($style);
        $sheetTemps->getStyle('K1')->applyFromArray($style);
        $sheetTemps->getStyle('L1')->applyFromArray($style);
        $sheetTemps->getStyle('M1')->applyFromArray($style);
        $sheetTemps->getStyle('N1')->applyFromArray($style);
        $sheetTemps->getStyle('O1')->applyFromArray($style);
        $sheetTemps->getStyle('P1')->applyFromArray($style);
        $sheetTemps->getStyle('Q1')->applyFromArray($style);
        $sheetTemps->getStyle('R1')->applyFromArray($style);


        $columnIndex = 'A';
        $lastColumnIndex = 'R'; // Mettez à jour ceci avec la dernière colonne de votre feuille
        while ($columnIndex <= $lastColumnIndex) {
            $sheetTemps->getColumnDimension($columnIndex)->setAutoSize(true);
            $columnIndex++;
        }

        $c = 2;
        foreach ($select_detail as $detail){
            $sheetTemps->setCellValue("A$c", $detail["employer_prenom"]);
            $sheetTemps->setCellValue("B$c", $detail["employer_nom"]);
            $sheetTemps->setCellValue("C$c", $detail["type_contrat"]);
            $sheetTemps->setCellValue("D$c", $detail["vol_h"]);
            $sheetTemps->setCellValue("E$c", $detail["etablissement_nom"]);
            $sheetTemps->setCellValue("F$c", $detail["num_semaine"]);
            $sheetTemps->setCellValue("G$c", $detail["date_entree"]);
            $sheetTemps->setCellValue("H$c", $detail["heure_entree"]);
            $sheetTemps->setCellValue("I$c", $detail["heure_sortie"]);
            $sheetTemps->setCellValue("J$c", 'Retard (h)');
            $sheetTemps->setCellValue("K$c", $detail["heures_travaillees"]);
            $sheetTemps->setCellValue("L$c", '');
            $sheetTemps->setCellValue("M$c", '');
            $sheetTemps->setCellValue("N$c", '');
            $sheetTemps->setCellValue("O$c", '');
            $sheetTemps->setCellValue("P$c", '');
            $sheetTemps->setCellValue("Q$c", '');
            $sheetTemps->setCellValue("R$c", '');

            if ($detail["heures_travaillees"] > 0.5){
                $sheetTemps->setCellValue("N$c", '1');
            }

            if ($detail["cron"] == 1){
                $sheetTemps->setCellValue("O$c", "Erreur de badge");
            }elseif ($detail["cron"] == 2){
                $sheetTemps->setCellValue("O$c", "Badge corrigé après erreur");
            }elseif ($detail["cron"] == 3){
                $sheetTemps->setCellValue("O$c", "Badge Modifié");
            }elseif ($detail["cron"] == 4){
                $sheetTemps->setCellValue("O$c", "Badge Ajouté");
            }

            if ($detail["badge_date_entree_dernier"] == NULL){
                $sheetTemps->setCellValue("Q$c", $detail["badge_date_entree_dernier"]);
            }
            if ($detail["badge_date_sortie_dernier"] == NULL){
                $sheetTemps->setCellValue("R$c", $detail["badge_date_sortie_dernier"]);
            }
            $c++;
        }
        foreach ($select_detail2_abs as $detail){
            $sheetTemps->setCellValue("A$c", $detail["employer_prenom"]);
            $sheetTemps->setCellValue("B$c", $detail["employer_nom"]);
            $sheetTemps->setCellValue("C$c", $detail["type_contrat"]);
            $sheetTemps->setCellValue("D$c", $detail["vol_h"]);
            $sheetTemps->setCellValue("E$c", $detail["etablissement_nom"]);
            $sheetTemps->setCellValue("F$c", $detail["num_semaine"]);
            $sheetTemps->setCellValue("G$c", $detail["date_entree"]);
            $sheetTemps->setCellValue("H$c", $detail["heure_entree"]);
            $sheetTemps->setCellValue("I$c", $detail["heure_sortie"]);
            $sheetTemps->setCellValue("J$c", '');
            $sheetTemps->setCellValue("K$c", $detail["heures_travaillees"]);

            if ($detail['abs_type'] != "AI"){
                $sheetTemps->setCellValue("L$c", $detail["heures_travaillees"]);
                $sheetTemps->setCellValue("M$c", '');
            }else{
                $sheetTemps->setCellValue("L$c", '');
                $sheetTemps->setCellValue("M$c", $detail["heures_travaillees"]);
            }
            $sheetTemps->setCellValue("N$c", '');

            $sheetTemps->setCellValue("O$c", "");
            $sheetTemps->setCellValue("P$c", $detail['abs_type']);

            $c++;
        }

        $sheetTemps->calculateColumnWidths();
        $boucle_compteur++;
    }
}

$m = array('','Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
$select_info_title = $db->query("SELECT * FROM badgeuse_etablissements where id_etablissement = ".$_SESSION['etablissement'])->fetchArray();
// Création du fichier XLSX
$writer = new Xlsx($spreadsheet);
$cheminFichierXLSX = str_replace(' ', '_', strtoupper($select_info_title['etablissement_nom']))."_".$m[$_GET['mois']]."_".$_GET['annee'].".xlsx";
$writer->save($cheminFichierXLSX);

// Lancement du téléchargement
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.$cheminFichierXLSX.'"');
readfile($cheminFichierXLSX);

// Suppression du fichier après le téléchargement
unlink($cheminFichierXLSX);

?>
