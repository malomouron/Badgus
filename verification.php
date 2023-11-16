<?php

    session_start();
    include('functions.php');
    include("config.inc.badger.php");
    include('db.php');
    $db = new db($dbhost, $dbuser, $dbpass, $dbname);
    if (isset($_POST['username']) && isset($_POST['password'])) {

        $username = securisation($_POST['username']);
        $password = securisation($_POST['password']);

        if ($username !== "" && $password !== "") {
            $count = $db->query("SELECT * FROM badgeuse_profil where profil_login = '" . $username . "' and profil_mdp = '" . md5($password) . "' ")->numRows();
            $select_user = $db->query("SELECT * FROM badgeuse_profil where profil_login = '" . $username . "' and profil_mdp = '" . md5($password) . "' ")->fetchArray();

            if ($count != 0) // nom d'utilisateur et mot de passe correctes
            {
                $_SESSION['id'] = $select_user['id_profil'];
                header('Location: index.php');
            } else {
                header('Location: login.php?erreur=1'); // utilisateur ou mot de passe incorrect
            }
        } else {
            header('Location: login.php?erreur=2'); // utilisateur ou mot de passe vide
        }
    } else {
        header('Location: login.php');
    }

