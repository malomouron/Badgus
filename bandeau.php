
<section class="ftco-section">
    <nav style="background: #fff!important;" class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href=""><img id="index_logo" src="media/favicon.png"></a>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav m-auto">
                    <li id="nav_active_1" class="nav-item"><a href="index.php" class="nav-link">Accueil</a></li>
                    <li id="nav_active_2" class="nav-item"><a href="planning.php" class="nav-link">Planning</a></li>
                    <li id="nav_active_3" class="nav-item"><a href="employes.php" class="nav-link">Employés</a></li>

                    <?php
                    if(isset($_SESSION['id'])){
                    echo '
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Autre</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="addBadge.php" ><img src="media/plus.png" alt="icon-plus-ajouter">Ajouter un badge</a>
                            <a class="dropdown-item" href="error.php"><img alt="icon-croix_barre" src="media/error.png">Corriger les erreurs de badge</a>
                            <span class="dropdown-item" style="cursor: pointer" onclick="choixMois();"><img src="media/export.png">Exporter les données en CSV</span>
                            <a class="dropdown-item" href="exit.php"><img src="media/deco.png">Déconnexion</a>
                        </div>
                    </li>';
                    }
                    ?>
                    <li id="nav_active_4" class="nav-item"><a href="badgus.php" class="nav-link">Badgeuse</a></li>
                </ul>
            </div>
        </div>
    </nav>
</section>
<script src="nav/js/jquery.min.js"></script>
<script src="nav/js/popper.js"></script>
<script src="nav/js/bootstrap.min.js"></script>
<script src="nav/js/main.js"></script>

<script type="text/javascript">
    var name = "<?php echo $_SERVER['PHP_SELF'] ; ?>";
    if (name == "/badgus/index.php"){
        nave_btn_active = document.getElementById("nav_active_1");
    }
    else if (name == "/badgus/badgus.php"){
        nave_btn_active = document.getElementById("nav_active_4");
    }
    else if (name == "/badgus/planning.php"){
        nave_btn_active = document.getElementById("nav_active_2");
    }
    else if (name == "/badgus/employes.php"){
        nave_btn_active = document.getElementById("nav_active_3");
    }
    nave_btn_active.classList.add("active")
</script>
<script>
    function choixMois() {
        // Création de la div pop-up
        const popupDiv = document.createElement('div');
        popupDiv.classList.add('popup');


        const titre = document.createElement('div');
        titre.classList.add("employee");
        titre.innerHTML = `<form action="csv.php" method="get" id="monFormulaire">
                                       <label for="date">Selectionner le mois : </label>
                                       <input required placeholder="AAAA-MM" type="month" name="date" id="date_export">
                            <input type="submit" value="Exporter" class="btn btn-success">
                           </form>`;
        popupDiv.appendChild(titre);


        // Création de l'arrière-plan obscurci
        const overlayDiv = document.createElement('div');
        overlayDiv.classList.add('overlay');

        // Ajout de la div pop-up et de l'arrière-plan obscurci au body
        document.body.appendChild(overlayDiv);
        document.body.appendChild(popupDiv);

        // Fonction pour fermer la pop-up lorsque l'arrière-plan est cliqué
        overlayDiv.addEventListener('click', function () {
            document.body.removeChild(overlayDiv);
            document.body.removeChild(popupDiv);
        });
    }

</script>