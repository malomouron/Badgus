<?php
session_start();
$valid_emp_con = false;
include("config.inc.badger.php");
include('db.php');

$css = array("css.css");
$db = new db($dbhost, $dbuser, $dbpass, $dbname);

function secu($val){
    return addslashes(trim(htmlspecialchars($val)));
}
function show_tab($tab){
    foreach ($tab as $cle => $val) {
        if (is_array($val)) {
            echo $cle . ' : !Array!  ';
            show_tab($val);
        } else {
            echo $cle . " : " . $val . '<br />';
        }
    }
}
if (isset($_POST['code']) and $_POST['code'] != '') {
    $select_code = $db->query("SELECT * FROM badgeuse_etablissements WHERE etablissement_code = '" . md5(secu($_POST['code'])) . "'")->numRows();
    if ($select_code == 1) {
        $select_code = $db->query("SELECT * FROM badgeuse_etablissements WHERE etablissement_code = '" . md5(secu($_POST['code'])) . "'")->fetchArray();

        setcookie("Etablissement_id", $select_code['id_etablissement'], time() + (20 * 365 * 24 * 60 * 60));

        header("Location: ".$_SERVER['PHP_SELF']);
    }
}

if (isset($_POST['code_emploi']) and $_POST['code_emploi'] != ''){

    $select_code_em = $db->query("SELECT * FROM badgeuse_employer, badgeuse_contrat where badgeuse_employer.id_employer = badgeuse_contrat.id_employer AND badgeuse_contrat.id_etablissement = ".$_COOKIE['Etablissement_id']." AND employer_pin = '".secu($_POST['code_emploi'])."'")->numRows();
    if ($select_code_em == 1){
        $valid_emp_con = true;
        $de_em = $_GET['da'];
        $select_code_em = $db->query("SELECT * FROM badgeuse_employer, badgeuse_contrat where badgeuse_employer.id_employer = badgeuse_contrat.id_employer AND badgeuse_contrat.id_etablissement = ".$_COOKIE['Etablissement_id']." AND employer_pin = '".secu($_POST['code_emploi'])."'")->fetchArray();
        $verifExist = $db->query("SELECT * FROM badgeuse_badge WHERE id_employer = ".$select_code_em['id_employer']." ORDER BY id_badge DESC LIMIT 1;")->numRows();
        $select_dernier_enreg = $db->query("SELECT * FROM badgeuse_badge WHERE id_employer = ".$select_code_em['id_employer']." ORDER BY id_badge DESC LIMIT 1;")->fetchArray();
        if ($verifExist == 0){
            if($_GET['da'] == 0){
                $insert_dep_entr = $db->query("INSERT INTO badgeuse_badge (`id_badge`, `id_employer`, `badge_date_entree`) VALUES (NULL, ".$select_code_em['id_employer'].", CURRENT_TIMESTAMP)");
            }elseif ($_GET['da'] == 1){
                $erreur1_date = $select_dernier_enreg['badge_date_entree'];
                $erreur1 = "Déjà enregistré comme partie";
            }
        }else{
            if (($_GET['da'] == 0 and $select_dernier_enreg['badge_date_sortie'] != NULL)){
                $insert_dep_entr = $db->query("INSERT INTO badgeuse_badge (`id_badge`, `id_employer`, `badge_date_entree`) VALUES (NULL, ".$select_code_em['id_employer'].", CURRENT_TIMESTAMP)");
            }elseif (($_GET['da'] == 1 and $select_dernier_enreg['badge_date_sortie'] == NULL)){
                $insert_dep_entr = $db->query("UPDATE badgeuse_badge SET badge_date_sortie = CURRENT_TIMESTAMP  WHERE id_badge = ".$select_dernier_enreg['id_badge']);
            }else{
                if ($_GET['da'] == 0){
                    $erreur1_date = $select_dernier_enreg['badge_date_entree'];
                    $erreur1 = "Déjà enregistré comme arrivé";
                }else{
                    $erreur1_date = $select_dernier_enreg['badge_date_sortie'];
                    $erreur1 = "Déjà enregistré comme partie";
                }
            }
        }
        unset($_GET['da']);
    }else{
        $erreur = "Code pin incorrect";
    }
}

include('functions.php');
afficher_head("Badg'us", $css, "UTF-8");
?>
<head>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <style>
        *{
            box-sizing: unset;
        }
        .reinitialise {
            animation : none;
            animation-delay : 0;
            animation-direction : normal;
            animation-duration : 0;
            animation-fill-mode : none;
            animation-iteration-count : 1;
            animation-name : none;
            animation-play-state : running;
            animation-timing-function : ease;
            backface-visibility : visible;
            background : 0;
            background-attachment : scroll;
            background-clip : border-box;
            background-color : transparent;
            background-image : none;
            background-origin : padding-box;
            background-position : 0 0;
            background-position-x : 0;
            background-position-y : 0;
            background-repeat : repeat;
            background-size : auto auto;
            border : 0;
            border-style : none;
            border-width : medium;
            border-color : inherit;
            border-bottom : 0;
            border-bottom-color : inherit;
            border-bottom-left-radius : 0;
            border-bottom-right-radius : 0;
            border-bottom-style : none;
            border-bottom-width : medium;
            border-collapse : separate;
            border-image : none;
            border-left : 0;
            border-left-color : inherit;
            border-left-style : none;
            border-left-width : medium;
            border-radius : 0;
            border-right : 0;
            border-right-color : inherit;
            border-right-style : none;
            border-right-width : medium;
            border-spacing : 0;
            border-top : 0;
            border-top-color : inherit;
            border-top-left-radius : 0;
            border-top-right-radius : 0;
            border-top-style : none;
            border-top-width : medium;
            bottom : auto;
            box-shadow : none;
            box-sizing : content-box;
            caption-side : top;
            clear : none;
            clip : auto;
            color : inherit;
            columns : auto;
            column-count : auto;
            column-fill : balance;
            column-gap : normal;
            column-rule : medium none currentColor;
            column-rule-color : currentColor;
            column-rule-style : none;
            column-rule-width : none;
            column-span : 1;
            column-width : auto;
            content : normal;
            counter-increment : none;
            counter-reset : none;
            cursor : auto;
            direction : ltr;
            display : inline;
            empty-cells : show;
            float : none;
            font : normal;
            font-family : inherit;
            font-size : medium;
            font-style : normal;
            font-variant : normal;
            font-weight : normal;
            height : auto;
            hyphens : none;
            left : auto;
            letter-spacing : normal;
            line-height : normal;
            list-style : none;
            list-style-image : none;
            list-style-position : outside;
            list-style-type : disc;
            margin : 0;
            margin-bottom : 0;
            margin-left : 0;
            margin-right : 0;
            margin-top : 0;
            max-height : none;
            max-width : none;
            min-height : 0;
            min-width : 0;
            opacity : 1;
            orphans : 0;
            outline : 0;
            outline-color : invert;
            outline-style : none;
            outline-width : medium;
            overflow : visible;
            overflow-x : visible;
            overflow-y : visible;
            padding : 0;
            padding-bottom : 0;
            padding-left : 0;
            padding-right : 0;
            padding-top : 0;
            page-break-after : auto;
            page-break-before : auto;
            page-break-inside : auto;
            perspective : none;
            perspective-origin : 50% 50%;
            position : static;
            /* Vous devrez modifier les quotes selon le langage de la page (ici il s'agit du Langage Français) */
            quotes : '201C' '201D' '2018' '2019';
            right : auto;
            tab-size : 8;
            table-layout : auto;
            text-align : inherit;
            text-align-last : auto;
            text-decoration : none;
            text-decoration-color : inherit;
            text-decoration-line : none;
            text-decoration-style : solid;
            text-indent : 0;
            text-shadow : none;
            text-transform : none;
            top : auto;
            transform : none;
            transform-style : flat;
            transition : none;
            transition-delay : 0s;
            transition-duration : 0s;
            transition-property : none;
            transition-timing-function : ease;
            unicode-bidi : normal;
            vertical-align : baseline;
            visibility : visible;
            white-space : normal;
            widows : 0;
            width : auto;
            word-spacing : normal;
            z-index : auto;
        }
        body {
            height: 100vh;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-family: Open Sans;
        }

        #inspiration {
            position: fixed;
            right: 1em;
            bottom: 1em;
        }

        #inspiration a {
            display: inline-block;
            float: left;
            text-decoration: none;
            font-weight: bold;
            color: white;
            -webkit-transition: all 1s ease;
            transition: all 1s ease;
        }

        #inspiration a:hover {
            color: #212121;
        }

        #inspiration p {
            margin: 0;
            padding-left: .4em;
            display: inline-block;
            float: right;
            color: rgba(255, 255, 255, 0.6);
        }

        #pin {
            background: #212121;
            width: 20em;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            padding: 1em;
            border-radius: .3em;
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.3);
            margin: auto;
            color: rgba(255, 255, 255, 0.2);
            margin-top: 1%;
        }

        .dots {
            width: 50%;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-justify-content: space-around;
            -ms-flex-pack: distribute;
            justify-content: space-around;
            padding: 1em;
            padding-top: 1.5em;
        }

        .dot {
            position: relative;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 0.8em;
            width: 0.8em;
            height: 0.8em;
            -webkit-transform: scale3d(0.7, 0.7, 0.7);
            transform: scale3d(0.7, 0.7, 0.7);
        }

        .dot.active {
            -webkit-animation: growDot .5s ease;
            animation: growDot .5s ease;
            -webkit-animation-fill-mode: forwards;
            animation-fill-mode: forwards;
        }

        .dot.wrong {
            -webkit-animation: wrong .9s ease;
            animation: wrong .9s ease;
        }

        .dot.correct {
            -webkit-animation: correct .9s ease;
            animation: correct .9s ease;
        }

        #pin p {
            font-size: .8em;
        }

        .numbers {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-flex-flow: row wrap;
            -ms-flex-flow: row wrap;
            flex-flow: row wrap;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-justify-content: space-around;
            -ms-flex-pack: distribute;
            justify-content: space-around;
            -webkit-align-content: flex-end;
            -ms-flex-line-pack: end;
            align-content: flex-end;
            margin: 0.5em 0;
        }

        .number, .ok_btn_pin {
            position: relative;
            width: 2.5em;
            height: 2.5em;
            margin: 0.5em;
            border-radius: 2.5em;
            border: 2px solid rgba(255, 255, 255, 0);
            text-align: center;
            line-height: 2.5em;
            font-weight: 400;
            font-size: 1.8em;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-transition: all .5s ease;
            transition: all .5s ease;
        }

        .number:hover, .ok_btn_pin:hover {
            color: rgba(255, 255, 255, 0.5);
        }

        .number:hover:before, .ok_btn_pin:hover:before {
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .number:before ,.ok_btn_pin:before {
            content: "";
            position: absolute;
            left: -2px;
            width: 2.5em;
            height: 2.5em;
            border: 2px solid rgba(255, 255, 255, 1);
            border-radius: 2.5em;
            -webkit-transition: all .5s ease;
            transition: all .5s ease;
        }


        .number.grow:before, .ok_btn_pin.grow:before {
            -webkit-animation: grow .6s ease;
            animation: grow .6s ease;
        }

        @-webkit-keyframes growDot {
            100% {
                background: white;
                -webkit-transform: scale3d(0.9, 0.9, 0.9);
                transform: scale3d(0.9, 0.9, 0.9);
            }
        }

        @keyframes growDot {
            100% {
                background: white;
                -webkit-transform: scale3d(0.9, 0.9, 0.9);
                transform: scale3d(0.9, 0.9, 0.9);
            }
        }

        @-webkit-keyframes grow {
            50% {
                -webkit-transform: scale3d(1.5, 1.5, 1.5);
                transform: scale3d(1.5, 1.5, 1.5);
            }
            100% {
                -webkit-transform: scale3d(1, 1, 1);
                transform: scale3d(1, 1, 1);
            }
        }

        @keyframes grow {
            50% {
                -webkit-transform: scale3d(1.5, 1.5, 1.5);
                transform: scale3d(1.5, 1.5, 1.5);
            }
            100% {
                -webkit-transform: scale3d(1, 1, 1);
                transform: scale3d(1, 1, 1);
            }
        }

        @-webkit-keyframes wrong {
            20% {
                background: crimson;
            }
            40% {
                -webkit-transform: translate(-15px, 0);
                transform: translate(-15px, 0);
            }
            60% {
                -webkit-transform: translate(10px, 0);
                transform: translate(10px, 0);
            }
            80% {
                -webkit-transform: translate(-5px, 0);
                transform: translate(-5px, 0);
            }
        }

        @keyframes wrong {
            20% {
                background: crimson;
            }
            40% {
                -webkit-transform: translate(-15px, 0);
                transform: translate(-15px, 0);
            }
            60% {
                -webkit-transform: translate(10px, 0);
                transform: translate(10px, 0);
            }
            80% {
                -webkit-transform: translate(-5px, 0);
                transform: translate(-5px, 0);
            }
        }

        @-webkit-keyframes correct {
            20% {
                background: limegreen;
            }
            40% {
                -webkit-transform: translate(0, -15px);
                transform: translate(0, -15px);
            }
            60% {
                -webkit-transform: translate(0, 10px);
                transform: translate(0, 10px);
            }
            80% {
                -webkit-transform: translate(0, -5px);
                transform: translate(0, -5px);
            }
        }

        @keyframes correct {
            20% {
                background: limegreen;
            }
            40% {
                -webkit-transform: translate(0, -15px);
                transform: translate(0, -15px);
            }
            60% {
                -webkit-transform: translate(0, 10px);
                transform: translate(0, 10px);
            }
            80% {
                -webkit-transform: translate(0, -5px);
                transform: translate(0, -5px);
            }
        }

        @-webkit-keyframes bg-red {
            50% {
                background: crimson;
            }
        }

        @keyframes bg-red {
            50% {
                background: crimson;
            }
        }

        @-webkit-keyframes bg-green {
            50% {
                background: limegreen;
            }
        }

        @keyframes bg-green {
            50% {
                background: limegreen;
            }
        }
        .reinitialise:before{
            border: none;
        }
        .reinitialise:hover{
            border: none;
        }
        .reinitialise:hover:before{
            border: none;
        }
        h2{
            text-align: center;
        }
        /* CSS */
        .button-24 {
            background: #FF4742;
            border: 1px solid #FF4742;
            border-radius: 6px;
            box-shadow: rgba(0, 0, 0, 0.1) 1px 2px 4px;
            box-sizing: border-box;
            color: #FFFFFF;
            cursor: pointer;
            display: inline-block;
            font-family: nunito,roboto,proxima-nova,"proxima nova",sans-serif;
            font-size: 16px;
            font-weight: 800;
            line-height: 16px;
            min-height: 40px;
            outline: 0;
            padding: 12px 14px;
            text-align: center;
            text-rendering: geometricprecision;
            text-transform: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            vertical-align: middle;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .button-24:hover,
        .button-24:active {
            background-color: initial;
            background-position: 0 0;
            color: #FF4742;
        }

        .button-24:active {
            opacity: .5;
        }
        #centrer_div{
            position: absolute;
            top: 25%;
            left: 50%;
            transform: translate(-50%, 50%);
        }
    </style>
</head>
<?php

if (isset($_COOKIE['Etablissement_id'])){
    if (!isset($_GET['da'])){
        if ($valid_emp_con){
            if(isset($erreur1)){
                echo '<body style="display: initial">
                        <div id="centrer_div">
                            <h1>'.$erreur1.'<h1/>
                            <h2>'."le " . date("d/m/Y", strtotime($erreur1_date)) . " à " . date("H:i", strtotime($erreur1_date)).'</h2>';
                unset($erreur1);
            }else{
                echo '<body style="display: initial">
                        <div id="centrer_div">
                            <h1>';
                if ($de_em == 0){
                    echo 'Arrivée';
                }else{
                    echo 'Départ';
                }

                echo ' de '.$select_code_em['employer_nom'].' '.$select_code_em['employer_prenom'].' Validé</h1>';
            }
            echo'            <br>
                        <button onclick="window.location.href = window.location.pathname" class="button-24" role="button">Continuer</button>
                    </div>
                    ';
            $valid_emp_con = false;
        } else{

           // echo show_tab($_COOKIE);
            echo '<link rel="stylesheet" href="button-hover-effect/style.css">';
            echo '<body style="height: auto">
                    <div>
                        <h2 id="date-temps-reel"></h2>
                        <h1 id="heure-temps-reel"></h1>
                        <div class="container">
                          <a class="button" onclick="redirigerVersAutrePage(0)" style="--color:#e4584b;">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            Arrivée
                          </a>
                          <a class="button" onclick="redirigerVersAutrePage(1)" style="--color: #333;">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            Départ
                          </a>
                        </div>
                        <div id="btn_lien_voir_horaire">
                            <a target="_blank" href="showShift.php">Voir mes horaires
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                  <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                </svg>
                            </a>
                        </div>
                        <div id="btn_lien_voir_horaire2">
                            <a target="_blank" href="showPlanning.php">Voir mon planning
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                  <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
            <script>
            function afficherDateEtHeureEnTempsReel() {
              var date = new Date();
              var jour = date.getDate();
              var mois = date.getMonth() + 1; // Les mois commencent à partir de 0, donc on ajoute 1
              var annee = date.getFullYear();
              var heures = date.getHours();
              var minutes = date.getMinutes();
              var secondes = date.getSeconds();
              
              // Ajoute un zéro devant les chiffres inférieurs à 10 pour une meilleure lisibilité
              jour = jour < 10 ? "0" + jour : jour;
              mois = mois < 10 ? "0" + mois : mois;
              heures = heures < 10 ? "0" + heures : heures;
              minutes = minutes < 10 ? "0" + minutes : minutes;
              secondes = secondes < 10 ? "0" + secondes : secondes;
            
              var dateActuelle = jour + "/" + mois + "/" + annee;
              var heureActuelle = heures + ":" + minutes + ":" + secondes;
              
              // Affiche la date et l\'heure dans un élément HTML avec l\'ID "date-heure-temps-reel"
              document.getElementById("date-temps-reel").textContent = dateActuelle;
              document.getElementById("heure-temps-reel").textContent = heureActuelle;
            }
            afficherDateEtHeureEnTempsReel()
            // Met à jour l\'heure toutes les secondes
            setInterval(afficherDateEtHeureEnTempsReel, 1000);
            function redirigerVersAutrePage(page) { 
              setTimeout(function() {
                window.location.href = "'.$_SERVER['PHP_SELF'].'?da="+page;
              }, 1000);
            }
        </script>';
        }
    }else{
        echo '

<body>
<div id="pin">
    <div class="dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>
    <p>Entrer votre code pin d\'employé</p>
    <div class="numbers">
        <div class="number">1</div>
        <div class="number">2</div>
        <div class="number">3</div>
        <div class="number">4</div>
        <div class="number">5</div>
        <div class="number">6</div>
        <div class="number">7</div>
        <div class="number">8</div>
        <div class="number">9</div>
        <div onclick="retour_code_employer()" class="ok_btn_pin reinitialise">BACK</div>
        <div class="number">0</div>
        <form action="badgus.php?da='.$_GET['da'].'" method="post">
            <input id="input_hidden_pin" type="hidden" name="code_emploi" required>
            <input onclick="vérifierLongueur()" type="submit" value="OK"  class="ok_btn_pin reinitialise">
        </form>
    </div>
</div>
<script>(function () {
        var input = \'\';
        var dots = document.querySelectorAll(\'.dot\');
        var numbers = document.querySelectorAll(\'.number\');
        dots = Array.prototype.slice.call(dots);
        var inputCache = document.getElementById("input_hidden_pin");
        numbers = Array.prototype.slice.call(numbers);
        numbers.forEach(function (number, index) {
            number.addEventListener(\'click\', function () {
                if  (input.length < 4) {
                    number.className += \' grow\';
                    if (index == 9) {
                        index = -1;
                    }
                    input += index + 1;
                    dots[input.length - 1].className += \' active\';
                    if (input.length >= 4) {
                        inputCache.value = input;
                        /*
                    if (input !== correct) {
                        dots.forEach(function (dot, index) {
                            dot.className += \' wrong\';
                        });
                    } else {
                        dots.forEach(function (dot, index) {
                            dot.className += \' correct\';
                        });
                    }
                    setTimeout(function () {
                        dots.forEach(function (dot, index) {
                            dot.className = \'dot\';
                        });
                        input = \'\';
                    }, 900);
                    setTimeout(function () {
                    }, 1000);

                     */
                    }
                    setTimeout(function () {
                        number.className = \'number\';
                    }, 1000);
                }
            });
        });
    }());
    function vérifierLongueur() {
        var inputCache = document.getElementById("input_hidden_pin");
        var valeur = inputCache.value;

        if (valeur.length != 4) {
            alert("La longueur requise est de 4 chiffres.");
            return;
        }

        // Le code continue ici si la longueur est supérieure ou égale à 5
        // Faites ce que vous voulez avec la valeur du champ caché
    }
    function retour_code_employer(){
        window.location.href = window.location.pathname;
    }

</script>';

    }
}
else{
    echo '

<body>
<div id="pin">
    <div class="dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>
    <p>Code pin de l\'établissement</p>
    <div class="numbers">
        <div class="number">1</div>
        <div class="number">2</div>
        <div class="number">3</div>
        <div class="number">4</div>
        <div class="number">5</div>
        <div class="number">6</div>
        <div class="number">7</div>
        <div class="number">8</div>
        <div class="number">9</div>
        <div class="ok_btn_pin reinitialise"></div>
        <div class="number">0</div>
        <form action="badgus.php" method="post">
            <input id="input_hidden_pin" type="hidden" name="code" required >
            <input onclick="vérifierLongueur()" type="submit" value="OK"  class="ok_btn_pin reinitialise">
        </form>
    </div>
</div>
<script>(function () {
        var input = \'\';
        var dots = document.querySelectorAll(\'.dot\');
        var numbers = document.querySelectorAll(\'.number\');
        dots = Array.prototype.slice.call(dots);
        var inputCache = document.getElementById("input_hidden_pin");
        numbers = Array.prototype.slice.call(numbers);
        numbers.forEach(function (number, index) {
            number.addEventListener(\'click\', function () {
                if  (input.length < 6) {
                    number.className += \' grow\';
                    if (index == 9) {
                        index = -1;
                    }
                    input += index + 1;
                    dots[input.length - 1].className += \' active\';
                    if (input.length >= 6) {
                        inputCache.value = input;
                        /*
                    if (input !== correct) {
                        dots.forEach(function (dot, index) {
                            dot.className += \' wrong\';
                        });
                    } else {
                        dots.forEach(function (dot, index) {
                            dot.className += \' correct\';
                        });
                    }
                    setTimeout(function () {
                        dots.forEach(function (dot, index) {
                            dot.className = \'dot\';
                        });
                        input = \'\';
                    }, 900);
                    setTimeout(function () {
                    }, 1000);

                     */
                    }
                    setTimeout(function () {
                        number.className = \'number\';
                    }, 1000);
                }
            });
        });
    }());
    function vérifierLongueur() {
        var inputCache = document.getElementById("input_hidden_pin");
        var valeur = inputCache.value;

        if (valeur.length != 6) {
            alert("La longueur requise est de 6 chiffres.");
            return;
        }

        // Le code continue ici si la longueur est supérieure ou égale à 5
        // Faites ce que vous voulez avec la valeur du champ caché
    }

</script>';
}
include ('footer_badgus.php');
?>
</body>
</html>
