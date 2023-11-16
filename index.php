<?php
    session_start();
    if (!isset($_SESSION['id'])) {
        header('Location: login.php');
    }
    include('functions.php');
    include("config.inc.badger.php");
    include('db.php');

    $css = array("css.css");
    afficher_head("Badg'us", $css, "UTF-8");
    $db = new db($dbhost, $dbuser, $dbpass, $dbname);
    include('bandeau.php');
    $select_all_etablissement = $db->query("SELECT * FROM badgeuse_etablissements")->fetchAll();
    if (isset($_POST['radio'])){
        $_SESSION['etablissement'] = $_POST['radio'];
    }
?>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Averia+Serif+Libre:wght@300;400;700&display=swap");
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: "Averia Serif Libre", cursive;
        }

        .radio-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6%;
            padding-bottom: 50px;
            padding-top: 50px;
        }
        .radio-item [type="radio"] {
            display: none;
        }
        .radio-item + .radio-item {
            margin-top: 15px;
        }
        .radio-item label {
            display: block;
            padding: 20px 60px;
            background: #000;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 400;
            min-width: 250px;
            white-space: nowrap;
            position: relative;
            transition: 0.4s ease-in-out 0s;
            color: white;
        }
        .radio-item label:after,
        .radio-item label:before {
            content: "";
            position: absolute;
            border-radius: 50%;
        }
        .radio-item label:after {
            height: 19px;
            width: 19px;
            border: 2px solid #f00;
            left: 19px;
            top: calc(50% - 12px);
        }
        .radio-item label:before {
            background: #f00;
            height: 20px;
            width: 20px;
            top: 23px;
            left: 18px;
            top: calc(50%-5px);
            transform: scale(5);
            opacity: 0;
            visibility: hidden;
            transition: 0.4s ease-in-out 0s;

        }
        .radio-item [type="radio"]:checked ~ label {
            border-color: #e4584b;
        }
        .radio-item [type="radio"]:checked ~ label::before {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
        }
        #btn_input{
            position: absolute;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 10px 45px;
            background-color: #e4584b;
            border: 2px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 8px;
        }
    </style>
    <form action="index.php" method="post">
        <section class="radio-section" >
            <div class="radio-list">
        <?php
        $a = 0;
        foreach ( $select_all_etablissement as $etablissement){
            $a++;
            if (isset($_SESSION['etablissement'])){
                if ($a == $_SESSION['etablissement']){
                    echo "<div class=\"radio-item\"><input checked required id='radio".$a."' type='radio' name='radio' value='".$etablissement['id_etablissement']."'><label for='radio".$a."'>".$etablissement['etablissement_nom']."</label></div>";
                }
                else{
                    echo "<div class=\"radio-item\"><input required id='radio".$a."' type='radio' name='radio' value='".$etablissement['id_etablissement']."'><label for='radio".$a."'>".$etablissement['etablissement_nom']."</label></div>";
                }
            }else{
                echo "<div class=\"radio-item\"><input required id='radio".$a."' type='radio' name='radio' value='".$etablissement['id_etablissement']."'><label for='radio".$a."'>".$etablissement['etablissement_nom']."</label></div>";
            }

        }
        ?>
            </div>
        </section>
        <input id="btn_input" type="submit" value="Enregistrer">
    </form>
<?php
include ("footer.php");
?>
    </body>
</html>
