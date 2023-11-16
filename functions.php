<?php
    error_reporting(0);
    function afficher_head($title, $fichiers_css, $charset)
        {

            echo '<!DOCTYPE html>
                    <html>
                        <head>
                            <title>'.$title.'</title>
                            <meta charset="'.$charset.'">
                            <link rel="icon" type="image/jpg" href="media/favicon.png">';
            foreach ($fichiers_css as $nom_fichier_css)
            {
                echo '      <link rel="stylesheet" href="'.$nom_fichier_css.'">';
            }
            echo '          
                            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                            <link href=\'https://fonts.googleapis.com/css?family=Roboto:400,100,300,700\' rel=\'stylesheet\' type=\'text/css\'>
                            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
                            <link rel="stylesheet" href="nav/css/style.css">
                            
                            <link rel="stylesheet" type="text/css" href="slick/slick.css"/>
                            <link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
                        </head>
                        <body>';
        }
    function show_tableau($tab){
        foreach ($tab as $cle => $val) {
                if (is_array($val)) {
                    echo $cle . ' : !Array!  ';
                    show_tableau($val);
                } else {
                    echo $cle . " : " . $val . '<br />';
                }
        }
    }
    function securisation($val){
        return addslashes(trim(htmlspecialchars($val)));
    }
    function filterByPlanningEntree($array, $planningEntree) {
        // Tableau pour stocker les éléments filtrés
        $filteredArray = array();

        // Boucle à travers le tableau d'entrée
        foreach ($array as $item) {
            // Extraire la partie jour de la date de "planning_entree"
            $datePart = substr($item['planning_entree'], 0, 10);

            // Vérifier si la partie jour correspond à la valeur spécifiée
            if ($datePart === $planningEntree) {
                // Ajouter l'élément filtré au tableau résultant
                $filteredArray[] = $item;
            }
        }

        return $filteredArray;
    }



    function generateRandomCode() {
        // Utilise rand() pour obtenir des chiffres aléatoires entre 0 et 9
        $code = '';
        for ($i = 0; $i < 4; $i++) {
            $code .= rand(0, 9);
        }
        return $code;
    }




    function regrouperParEmploye($data) {
        $groupedData = array();

        foreach ($data as $entry) {
            $key = $entry["employer_prenom"] . "_" . $entry["employer_nom"];
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = array();
            }
            $groupedData[$key][] = $entry;
        }

        return $groupedData;
    }
    function regrouperParMois($tableau) {
        $tableauMensuel = array();

        foreach ($tableau as $element) {
            $dateEntree = new DateTime($element['badge_date_entree']);
            $mois = $dateEntree->format('Y-m');

            if (!isset($tableauMensuel[$mois])) {
                $tableauMensuel[$mois] = array();
            }

            $tableauMensuel[$mois][] = $element;
        }

        return $tableauMensuel;
    }
?>