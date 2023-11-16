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
$select_contrat = $db->query("SELECT * FROM badgeuse_contrat, badgeuse_employer, badgeuse_etablissements WHERE badgeuse_etablissements.id_etablissement = badgeuse_contrat.id_etablissement AND badgeuse_contrat.id_employer = badgeuse_employer.id_employer AND badgeuse_contrat.id_etablissement = ".$_SESSION['etablissement'] )->fetchAll();
?>
<div>
    <div id="employes_slick-div" class="slick_class">
        <?php
        $b = 0;
        foreach ($select_contrat as $contrat) {
            $b++;
            echo "<div  class='slick_employer_info slick_employer_info_css' id='slick_employer_info_active$b' onclick='change_select($b)'><span>" . $contrat['employer_nom'] . " " . $contrat['employer_prenom'] . "</span></div>";
        }

        ?>
    </div>
    <a href="new_employer.php">
        <button id="employes_btn_ajt_em" type="button" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"></path>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"></path>
            </svg>
            Ajouter un employé
        </button>
    </a>
    <div id="info_employes">

        <?php
        $a = 0;
        foreach ($select_contrat as $contrat) {
            $a++;
            $select_etablissement = $db->query("SELECT * FROM badgeuse_etablissements where id_etablissement = " . $contrat['id_etablissement'])->fetchArray();
            echo "     <div class='div_employer_info div_employer_info_css' id='div_employer_info_active$a'>".'
                            <div style=\'position: absolute;right: 15%;\'>
                                <a href="employer_action.php?edite=1&id='.$contrat['id_employer'].'">
                                    <button type="button" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
                                            <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"></path>
                                        </svg>
                                        Modifier
                                    </button>'."
                                </a>
                                <a id=\"myLink$a\" onclick=\"doubleClickAction('".$contrat['id_employer']."', $a)\">
                                    <button type=\"button\" class=\"btn btn-danger\">
                                        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-person-x\" viewBox=\"0 0 16 16\">
                                            <path d=\"M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z\"></path>
                                            <path d=\"M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708Z\"></path>
                                        </svg>
                                        Supprimer
                                    </button>
                                </a>
                            </div>
                            <h2>" . $contrat['employer_nom'] . " " . $contrat['employer_prenom'] . "</h2>
                            <hr>
                            <p>Pin : " . $contrat['employer_pin'] . "</p><hr>
                            <p>
                                Établissement : " . $select_etablissement['etablissement_nom'] . " <br>
                                Type de contrat : " . $contrat['type_contrat'] . "<br>
                                Volume horaire : " . $contrat['vol_h'] . "h
                            </p>
                        </div>";
        }

        ?>
    </div>
</div>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="slick/slick.min.js"></script>
<script type="text/javascript">
    var select = 1
    var employer = document.getElementById("div_employer_info_active" + select);
    employer.classList.add("div_employer_info_select");
    employer.classList.remove("div_employer_info");

    var employer_slick = document.getElementById("slick_employer_info_active" + select)
    employer_slick.classList.add("slick_employer_info_active")
    employer_slick.classList.remove("slick_employer_info")

    function change_select(new_select) {
        var ancien_select = document.getElementsByClassName("div_employer_info_select");
        ancien_select[0].classList.add("div_employer_info");
        ancien_select[0].classList.remove("div_employer_info_select");

        var new_selected_el = document.getElementById("div_employer_info_active" + new_select);
        new_selected_el.classList.add("div_employer_info_select");
        new_selected_el.classList.remove("div_employer_info");

        var ancien_select_slide = document.getElementsByClassName("slick_employer_info_active");
        ancien_select_slide[0].classList.add("slick_employer_info");
        ancien_select_slide[0].classList.remove("slick_employer_info_active");

        var new_selected_el_slide = document.getElementById("slick_employer_info_active" + new_select);
        new_selected_el_slide.classList.add("slick_employer_info_active");
        new_selected_el_slide.classList.remove("slick_employer_info");

    }
    function doubleClickAction(id, c) {
        var link = document.getElementById('myLink'+c);
        if (link.getAttribute('onclick')) {
            alert("Cette action est irréversible double cliquez pour continuer");
            link.removeAttribute('onclick');
            link.setAttribute("ondblclick", "window.location.href='employer_action.php?edite=0&id="+id+"'")
        }
    }
</script>
<script type="text/javascript">
    $('.slick_class').slick({
        dots: false,
        infinite: false,
        speed: 200,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
</script>
<?php
    include('footer.php');
?>
</body>
</html>
