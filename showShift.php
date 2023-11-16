<?php
session_start();
include('functions.php');
include("config.inc.badger.php");
include('db.php');

$css = array("css.css");
afficher_head("Badg'us", $css, "UTF-8");
$db = new db($dbhost, $dbuser, $dbpass, $dbname);

if (isset($_POST['code_emploi']) and isset($_COOKIE['Etablissement_id'])){
    $verif = $db->query("SELECT * FROM badgeuse_employer, badgeuse_contrat where badgeuse_employer.id_employer = badgeuse_contrat.id_employer AND badgeuse_contrat.id_etablissement = ".$_COOKIE['Etablissement_id']." AND employer_pin = '".securisation($_POST['code_emploi'])."'")->numRows();
    if ($verif == 1){
        $employer = $db->query("SELECT * FROM badgeuse_employer, badgeuse_contrat where badgeuse_employer.id_employer = badgeuse_contrat.id_employer AND badgeuse_contrat.id_etablissement = ".$_COOKIE['Etablissement_id']." AND employer_pin = '".securisation($_POST['code_emploi'])."'")->fetchArray();
        $badge = $db->query("SELECT badge_date_entree, badge_date_sortie, cron FROM `badgeuse_badge` WHERE id_employer = ".$employer['id_employer']." ORDER BY badge_date_entree DESC;")->fetchAll();
        $badge = regrouperParMois($badge);
        echo '  <h1 id="h1_show_shift">Horaire de '.$employer['employer_nom'].' '.$employer['employer_prenom'].' : </h1>
                <span id="span_code_couleur" class="span_code_couleur_shpw_shift">
                    Code couleur :
                    <span aria-label="Badge Correcte" class="cron_badge_0 picto-item">.</span>
                    <span aria-label="Erreur de badge" class="cron_badge_1 picto-item">.</span>
                    <span aria-label="Badge corrigé après erreur" class="cron_badge_2 picto-item">.</span>
                    <span aria-label="Badge Modifié" class="cron_badge_3 picto-item">.</span>
                    <span aria-label="Badge Ajouté" class="cron_badge_4 picto-item">.</span>
                </span>
                <div id="tableContainer"></div>  
                <script>
                    var tableau = '.json_encode($badge).'
                    // Créer une div pour chaque mois dans le tableau
                    var tableContainer = document.getElementById("tableContainer");
                    for (var mois in tableau) {
                        if (tableau.hasOwnProperty(mois)) {
                            var moisDiv = document.createElement("div");
                            
                            
                            const date = new Date(mois + "-01");
                            const moisEnFrancais = [
                              "janvier", "février", "mars", "avril", "mai", "juin",
                              "juillet", "août", "septembre", "octobre", "novembre", "décembre"
                            ];
                            const mois_a = moisEnFrancais[date.getMonth()];
                            const annee_a = date.getFullYear();
                            const dateFormatee = `${mois_a} ${annee_a}`;
                            
                            moisDiv.innerHTML = "<h2 class=\'mois_h2\'>" + dateFormatee + "</h2>";
            
                            // Créer une table pour afficher les données
                            var table = document.createElement("table");
                            var thead = table.createTHead();
                            var row = thead.insertRow();
                            var headers = ["Date d\'Entrée", "Date de Sortie"];
                            
                            // Créer des en-têtes de colonne
                            for (var i = 0; i < headers.length; i++) {
                                var cell = document.createElement("th");
                                cell.innerHTML = headers[i];
                                row.appendChild(cell);
                            }
            
                            // Ajouter les données pour ce mois
                            var tbody = table.createTBody();
                            for (var i = 0; i < tableau[mois].length; i++) {
                                var data = tableau[mois][i];
                                var row = tbody.insertRow();
                                if (data.cron == 0){
                                    row.style.background = "green";
                                }
                                if (data.cron == 1){
                                    row.style.background = "red";
                                }
                                if (data.cron == 2){
                                    row.style.background = "dodgerblue";
                                }
                                if (data.cron == 3){
                                    row.style.background = "#ef6c2b";
                                }
                                if (data.cron == 4){
                                    row.style.background = "rebeccapurple";
                                }
                                row.style.color= "white"
                                row.insertCell(0).innerHTML = "le "+ data.badge_date_entree.slice(8,-3).replace(/ /g, " à ");
                                row.insertCell(1).innerHTML = "le "+ data.badge_date_sortie.slice(8,-3).replace(/ /g, " à ");
                            }
            
                            moisDiv.appendChild(table);
                            tableContainer.appendChild(moisDiv);
                        }
                    }
                  </script><br><br><br><br><br>';
    }
    header('Location: showShift.php');
}else {


    echo '<style>
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
            quotes : "201C" "201D" "2018" "2019";
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
    </style>'; //dcecdc

    echo "<div id=\"pin\">
    <div class=\"dots\">
        <div class=\"dot\"></div>
        <div class=\"dot\"></div>
        <div class=\"dot\"></div>
        <div class=\"dot\"></div>
    </div>
    <p>Entrer votre code pin d'employé</p>
    <div class=\"numbers\">
        <div class=\"number\">1</div>
        <div class=\"number\">2</div>
        <div class=\"number\">3</div>
        <div class=\"number\">4</div>
        <div class=\"number\">5</div>
        <div class=\"number\">6</div>
        <div class=\"number\">7</div>
        <div class=\"number\">8</div>
        <div class=\"number\">9</div>
        <div onclick=\"retour_code_employer()\" class=\"ok_btn_pin reinitialise\">BACK</div>
        <div class=\"number\">0</div>
        <form action=\"showShift.php\" method=\"post\">
            <input id=\"input_hidden_pin\" type=\"hidden\" name=\"code_emploi\" required>
            <input onclick=\"vérifierLongueur()\" type=\"submit\" value=\"OK\"  class=\"ok_btn_pin reinitialise\">
        </form>
    </div>
</div>
<script>(function () {
        var input = '';
        var dots = document.querySelectorAll('.dot');
        var numbers = document.querySelectorAll('.number');
        dots = Array.prototype.slice.call(dots);
        var inputCache = document.getElementById(\"input_hidden_pin\");
        numbers = Array.prototype.slice.call(numbers);
        numbers.forEach(function (number, index) {
            number.addEventListener('click', function () {
                if  (input.length < 4) {
                    number.className += ' grow';
                    if (index == 9) {
                        index = -1;
                    }
                    input += index + 1;
                    dots[input.length - 1].className += ' active';
                    if (input.length >= 4) {
                        inputCache.value = input;
                    }
                    setTimeout(function () {
                        number.className = 'number';
                    }, 1000);
                }
            });
        });
    }());
    function vérifierLongueur() {
        var inputCache = document.getElementById(\"input_hidden_pin\");
        var valeur = inputCache.value;

        if (valeur.length != 4) {
            alert(\"La longueur requise est de 4 chiffres.\");
            return;
        }

    }
    function retour_code_employer(){
        window.location.href = 'badgus.php';
    }

</script>";
}
echo'</body>';


include ('footer_badgus.php');
?>