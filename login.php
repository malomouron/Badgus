<?php
    session_start();
    include('functions.php');
    include("config.inc.badger.php");
    include('db.php');

    $css = array("css.css");
    afficher_head("Badg'us", $css, "UTF-8");
    $db = new db($dbhost, $dbuser, $dbpass, $dbname);
?>
     <body id="login_body">
         <div id="login_container">
             <form id="login_form" action="verification.php" method="POST">
             <h1>Connexion</h1>

             <label><b>Nom d'utilisateur</b></label>
             <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" required>

             <label><b>Mot de passe</b></label>
             <input id="passwordInput" type="password" placeholder="Entrer le mot de passe" name="password" required>

             <label for="afficher_mdp">Afficher le Mot de Passe</label>
             <input type="checkbox" name="afficher_mdp" id="afficher_mdp">
             <input type="submit" id='login_submit' value='LOGIN' >
             <?php
             if(isset($_GET['erreur'])){
             $err = $_GET['erreur'];
             if($err==1 || $err==2)
             echo "<p style='color:red'>Utilisateur ou mot de passe incorrect</p>";
             }
             ?>
             </form>
         </div>
         <?php
         include ("footer.php");
         ?>
         <script>
             // Sélectionnez les éléments HTML
             const passwordInput = document.getElementById("passwordInput");
             const afficherMdpCheckbox = document.getElementById("afficher_mdp");

             // Ajoutez un gestionnaire d'événements à la case à cocher
             afficherMdpCheckbox.addEventListener("change", function () {
                 if (afficherMdpCheckbox.checked) {
                     // Si la case est cochée, changez le type de l'input pour "text"
                     passwordInput.setAttribute("type", "text");
                 } else {
                     // Sinon, changez le type de l'input pour "password"
                     passwordInput.setAttribute("type", "password");
                 }
             });
         </script>
     </body>
</html>