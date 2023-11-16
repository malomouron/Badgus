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

if (isset($_POST['planning_day'])){

    if (isset($_POST['new_planning_entree'])){
        //form d'ajout d'horaire

        $new_planning_entree = DateTime::createFromFormat('H:i', $_POST['new_planning_entree']);
        $new_planning_sortie = DateTime::createFromFormat('H:i', $_POST['new_planning_sortie']);
        $new_planning_entree = $new_planning_entree->format('H:i');
        $new_planning_sortie = $new_planning_sortie->format('H:i');
        if ($new_planning_entree < $new_planning_sortie ){
            $insert_new_date = $db->query("INSERT INTO badgeuse_planning (id_employer, planning_entree, planning_sortie, planning_semaine, planning_annee) VALUES (".securisation($_POST['planning_em']).", '".securisation($_POST['planning_day'])." ".securisation($_POST['new_planning_entree'])."', '".securisation($_POST['planning_day'])." ".securisation($_POST['new_planning_sortie'])."', ".date('W').", ".date('Y')." )");
            header("Location: ".$_SERVER['REQUEST_URI']);
        } else {
            $erreur = "La date d'entrée est supérieure à la date de sortie.";
        }
    }elseif (isset($_POST['horaire_abs'])){

        $update_abs = $db->query("UPDATE badgeuse_planning SET abs = 1 WHERE id_planning = ".securisation($_POST['horaire_abs']));
        $update_abs = $db->query("UPDATE badgeuse_planning SET abs_type = '".securisation($_POST['type_abs'])."' WHERE id_planning = ".securisation($_POST['horaire_abs']));
        header("Location: ".$_SERVER['REQUEST_URI']);
    }elseif (isset($_POST['planning_id'])){
        //form modif horaire
        $new_planning_entree = DateTime::createFromFormat('H:i', $_POST["planning_entree"]);
        $new_planning_sortie = DateTime::createFromFormat('H:i', $_POST["planning_sortie"]);

        $new_planning_entree = $new_planning_entree->format('H:i');

        $new_planning_sortie = $new_planning_sortie->format('H:i');

        if ($new_planning_entree < $new_planning_sortie ){
            $update_date = $db->query("UPDATE badgeuse_planning SET planning_entree = '".securisation($_POST['planning_day'])." ".securisation($_POST["planning_entree"])."'  WHERE id_planning = ".securisation($_POST['planning_id']));
            $update_date_2 = $db->query("UPDATE badgeuse_planning SET planning_sortie = '".securisation($_POST['planning_day'])." ".securisation($_POST["planning_sortie"])."' WHERE id_planning = ".securisation($_POST['planning_id']));

            header("Location: ".$_SERVER['REQUEST_URI']);
        } else {
            $erreur = "La date d'entrée est supérieure à la date de sortie.";
        }
    }
}
?>
    <style>
        :root {
            --blue: #e4584b;
            --light: #ffffff87;
            --light2: #ffffff52;
        }
        .btn-outline-secondary:hover{
            background-color: #6c757d9c;
        }
        table {
            font-family: sans-serif;
            width: 100%;
            border-spacing: 0;
            border-collapse: separate;
            table-layout: fixed;
            margin-bottom: 50px;
            text-align: center;
            margin-bottom: 200px;
        }

        table thead tr th {
            background: var(--blue);
            color: #ebebeb;
            padding: 8px;
            overflow: hidden;
        }
        table thead tr th:hover{
            border: 2px black solid;
            cursor: pointer;
            padding: 6px;
        }

        table thead tr th:first-child {
            border-radius: 3px 0 0 0;
        }

        table thead tr th:last-child {
            border-radius: 0 3px 0 0;
        }

        table thead tr th .day {
            display: block;
            font-size: 1.2em;
            border-radius: 50%;
            height: 30px;
            padding: 5px;
            line-height: 1.8;
        }

        table thead tr th .day.active {
            background: var(--light);
            color: var(--blue);
        }

        table thead tr th .short {
            display: none;
        }

        table thead tr th i {
            vertical-align: middle;
            font-size: 2em;
        }

        table tbody tr {
            background: var(--light);
        }

        table tbody tr:nth-child(odd) {
            background: var(--light2);
        }


        table tbody tr td {
            text-align: center;
            vertical-align: middle;
            border-left: 1px solid black;
            position: relative;
            height: 35px;
            cursor: pointer;
        }

        table tbody tr td:last-child {
            border-right: 1px solid var(--blue);
        }

        table tbody tr td.hour {
            font-size: 2em;
            padding: 0;
            color: #5d5d5d;
            background: #fff;
            border-bottom: 1px solid black;
            border-collapse: separate;
            min-width: 100px;
            cursor: default;
            height: 4em;
        }

        table tbody tr td.hour span {
            display: block;
        }

        @media (max-width: 60em) {
            table thead tr th .long {
                display: none;
            }

            table thead tr th .short {
                display: block;
            }

            table tbody tr td.hour span {
                transform: rotate(270deg);
                -webkit-transform: rotate(270deg);
                -moz-transform: rotate(270deg);
            }
        }

        @media (max-width: 27em) {
            table thead tr th {
                font-size: 65%;
            }

            table thead tr th .day {
                display: block;
                font-size: 1.2em;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                margin: 0 auto 5px;
                padding: 5px;
            }

            table thead tr th .day.active {
                background: var(--light);
                color: var(--blue);
            }

            table tbody tr td.hour {
                font-size: 1.7em;
            }

            table tbody tr td.hour span {
                transform: translateY(16px) rotate(270deg);
                -webkit-transform: translateY(16px) rotate(270deg);
                -moz-transform: translateY(16px) rotate(270deg);
            }
        }

        .active_j_actuelle{
            background: #4782d0;
        }
        input[type="submit"]:hover{
            --bs-font-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            --bs-body-font-family: var(--bs-font-sans-serif);
            --bs-body-font-size: 1rem;
            --bs-body-font-weight: 400;
            --bs-body-line-height: 1.5;
            --bs-link-decoration: underline;
            --bs-highlight-bg: #fff3cd;
            --bs-border-width: 1px;
            --bs-border-style: solid;
            --bs-border-radius: .375rem;
            --bs-border-radius-sm: .25rem;
            --bs-border-radius-lg: .5rem;
            --bs-border-radius-xl: 1rem;
            --bs-border-radius-xxl: 2rem;
            --bs-border-radius-2xl: var(--bs-border-radius-xxl);
            --bs-border-radius-pill: 50rem;
            --bs-box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            --bs-box-shadow-sm: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            --bs-box-shadow-lg: 0 1rem 3rem rgba(0,0,0,0.175);
            --bs-box-shadow-inset: inset 0 1px 2px rgba(0,0,0,0.075);
            --bs-focus-ring-width: .25rem;
            --bs-focus-ring-opacity: .25;
            --bs-focus-ring-color: rgba(13,110,253,0.25);
            --bs-body-color: #adb5bd;
            --bs-body-color-rgb: 173,181,189;
            --bs-body-bg: #212529;
            --bs-body-bg-rgb: 33,37,41;
            --bs-emphasis-color: #fff;
            --bs-emphasis-color-rgb: 255,255,255;
            --bs-secondary-color: rgba(173,181,189,0.75);
            --bs-secondary-color-rgb: 173,181,189;
            --bs-secondary-bg: #343a40;
            --bs-secondary-bg-rgb: 52,58,64;
            --bs-tertiary-color: rgba(173,181,189,0.5);
            --bs-tertiary-color-rgb: 173,181,189;
            --bs-tertiary-bg: #2b3035;
            --bs-tertiary-bg-rgb: 43,48,53;
            --bs-primary-text-emphasis: #6ea8fe;
            --bs-secondary-text-emphasis: #a7acb1;
            --bs-success-text-emphasis: #75b798;
            --bs-info-text-emphasis: #6edff6;
            --bs-warning-text-emphasis: #ffda6a;
            --bs-danger-text-emphasis: #ea868f;
            --bs-light-text-emphasis: #f8f9fa;
            --bs-dark-text-emphasis: #dee2e6;
            --bs-primary-bg-subtle: #031633;
            --bs-secondary-bg-subtle: #161719;
            --bs-success-bg-subtle: #051b11;
            --bs-info-bg-subtle: #032830;
            --bs-warning-bg-subtle: #332701;
            --bs-danger-bg-subtle: #2c0b0e;
            --bs-light-bg-subtle: #343a40;
            --bs-dark-bg-subtle: #1a1d20;
            --bs-primary-border-subtle: #084298;
            --bs-secondary-border-subtle: #41464b;
            --bs-success-border-subtle: #0f5132;
            --bs-info-border-subtle: #087990;
            --bs-warning-border-subtle: #997404;
            --bs-danger-border-subtle: #842029;
            --bs-light-border-subtle: #495057;
            --bs-dark-border-subtle: #343a40;
            --bs-link-color: #6ea8fe;
            --bs-link-hover-color: #8bb9fe;
            --bs-link-color-rgb: 110,168,254;
            --bs-link-hover-color-rgb: 139,185,254;
            --bs-code-color: #e685b5;
            --bs-border-color: #495057;
            --bs-border-color-translucent: rgba(255,255,255,0.15);
            --bs-form-valid-color: #75b798;
            --bs-form-valid-border-color: #75b798;
            --bs-form-invalid-color: #ea868f;
            --bs-form-invalid-border-color: #ea868f;
            --bs-breakpoint-xs: 0;
            --bs-breakpoint-sm: 576px;
            --bs-breakpoint-md: 768px;
            --bs-breakpoint-lg: 992px;
            --bs-breakpoint-xl: 1200px;
            --bs-breakpoint-xxl: 1400px;
            --bd-purple: #4c0bce;
            --bd-violet: #712cf9;
            --bd-accent: #ffe484;
            --bd-violet-rgb: 112.520718,44.062154,249.437846;
            --bd-accent-rgb: 255,228,132;
            --bd-pink-rgb: 214,51,132;
            --bd-teal-rgb: 32,201,151;
            --base00: #282c34;
            --base01: #353b45;
            --base02: #3e4451;
            --base03: #868e96;
            --base04: #565c64;
            --base05: #abb2bf;
            --base06: #b6bdca;
            --base07: #d19a66;
            --base08: #e06c75;
            --base09: #d19a66;
            --base0A: #e5c07b;
            --base0B: #98c379;
            --base0C: #56b6c2;
            --base0D: #61afef;
            --base0E: #c678dd;
            --base0F: #be5046;
            color-scheme: light dark;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            --bs-gutter-y: 0;
            --bs-gutter-x: 3rem;
            box-sizing: border-box;
            margin: 0;
            text-transform: none;
            -webkit-appearance: button;
            --bs-btn-padding-x: .75rem;
            --bs-btn-padding-y: .375rem;
            --bs-btn-font-family: ;
            --bs-btn-font-size: 1rem;
            --bs-btn-font-weight: 400;
            --bs-btn-line-height: 1.5;
            --bs-btn-bg: transparent;
            --bs-btn-border-width: var(--bs-border-width);
            --bs-btn-border-radius: var(--bs-border-radius);
            --bs-btn-box-shadow: inset 0 1px 0 rgba(255,255,255,0.15),0 1px 1px rgba(0,0,0,0.075);
            --bs-btn-disabled-opacity: .65;
            --bs-btn-focus-box-shadow: 0 0 0 .25rem rgba(var(--bs-btn-focus-shadow-rgb), .5);
            display: inline-block;
            padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
            font-family: var(--bs-btn-font-family);
            font-size: var(--bs-btn-font-size);
            font-weight: var(--bs-btn-font-weight);
            line-height: var(--bs-btn-line-height);
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            user-select: none;
            border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
            border-radius: var(--bs-btn-border-radius);
            transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out;
            --bs-btn-color: #198754;
            --bs-btn-border-color: #198754;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #198754;
            --bs-btn-hover-border-color: #198754;
            --bs-btn-focus-shadow-rgb: 220,53,69;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #198754;
            --bs-btn-active-border-color: #198754;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0,0,0,0.125);
            --bs-btn-disabled-color: #198754;
            --bs-btn-disabled-bg: transparent;
            --bs-btn-disabled-border-color: #198754;
            --bs-gradient: none;
            cursor: pointer;
            color: var(--bs-btn-hover-color);
            background-color: var(--bs-btn-hover-bg);
            border-color: var(--bs-btn-hover-border-color);

            min-width: 100px;
            height: 40px;
            border-radius: 10px;
            margin-inline-start: 10px;
        }
        input[type="submit"]{
            --bs-font-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            --bs-body-font-family: var(--bs-font-sans-serif);
            --bs-body-font-size: 1rem;
            --bs-body-font-weight: 400;
            --bs-body-line-height: 1.5;
            --bs-link-decoration: underline;
            --bs-highlight-bg: #fff3cd;
            --bs-border-width: 1px;
            --bs-border-style: solid;
            --bs-border-radius: .375rem;
            --bs-border-radius-sm: .25rem;
            --bs-border-radius-lg: .5rem;
            --bs-border-radius-xl: 1rem;
            --bs-border-radius-xxl: 2rem;
            --bs-border-radius-2xl: var(--bs-border-radius-xxl);
            --bs-border-radius-pill: 50rem;
            --bs-box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            --bs-box-shadow-sm: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            --bs-box-shadow-lg: 0 1rem 3rem rgba(0,0,0,0.175);
            --bs-box-shadow-inset: inset 0 1px 2px rgba(0,0,0,0.075);
            --bs-focus-ring-width: .25rem;
            --bs-focus-ring-opacity: .25;
            --bs-focus-ring-color: rgba(13,110,253,0.25);
            --bs-body-color: #adb5bd;
            --bs-body-color-rgb: 173,181,189;
            --bs-body-bg: #212529;
            --bs-body-bg-rgb: 33,37,41;
            --bs-emphasis-color: #fff;
            --bs-emphasis-color-rgb: 255,255,255;
            --bs-secondary-color: rgba(173,181,189,0.75);
            --bs-secondary-color-rgb: 173,181,189;
            --bs-secondary-bg: #343a40;
            --bs-secondary-bg-rgb: 52,58,64;
            --bs-tertiary-color: rgba(173,181,189,0.5);
            --bs-tertiary-color-rgb: 173,181,189;
            --bs-tertiary-bg: #2b3035;
            --bs-tertiary-bg-rgb: 43,48,53;
            --bs-primary-text-emphasis: #6ea8fe;
            --bs-secondary-text-emphasis: #a7acb1;
            --bs-success-text-emphasis: #75b798;
            --bs-info-text-emphasis: #6edff6;
            --bs-warning-text-emphasis: #ffda6a;
            --bs-danger-text-emphasis: #ea868f;
            --bs-light-text-emphasis: #f8f9fa;
            --bs-dark-text-emphasis: #dee2e6;
            --bs-primary-bg-subtle: #031633;
            --bs-secondary-bg-subtle: #161719;
            --bs-success-bg-subtle: #051b11;
            --bs-info-bg-subtle: #032830;
            --bs-warning-bg-subtle: #332701;
            --bs-danger-bg-subtle: #2c0b0e;
            --bs-light-bg-subtle: #343a40;
            --bs-dark-bg-subtle: #1a1d20;
            --bs-primary-border-subtle: #084298;
            --bs-secondary-border-subtle: #41464b;
            --bs-success-border-subtle: #0f5132;
            --bs-info-border-subtle: #087990;
            --bs-warning-border-subtle: #997404;
            --bs-danger-border-subtle: #842029;
            --bs-light-border-subtle: #495057;
            --bs-dark-border-subtle: #343a40;
            --bs-link-color: #6ea8fe;
            --bs-link-hover-color: #8bb9fe;
            --bs-link-color-rgb: 110,168,254;
            --bs-link-hover-color-rgb: 139,185,254;
            --bs-code-color: #e685b5;
            --bs-border-color: #495057;
            --bs-border-color-translucent: rgba(255,255,255,0.15);
            --bs-form-valid-color: #75b798;
            --bs-form-valid-border-color: #75b798;
            --bs-form-invalid-color: #ea868f;
            --bs-form-invalid-border-color: #ea868f;
            --bs-breakpoint-xs: 0;
            --bs-breakpoint-sm: 576px;
            --bs-breakpoint-md: 768px;
            --bs-breakpoint-lg: 992px;
            --bs-breakpoint-xl: 1200px;
            --bs-breakpoint-xxl: 1400px;
            --bd-purple: #4c0bce;
            --bd-violet: #712cf9;
            --bd-accent: #ffe484;
            --bd-violet-rgb: 112.520718,44.062154,249.437846;
            --bd-accent-rgb: 255,228,132;
            --bd-pink-rgb: 214,51,132;
            --bd-teal-rgb: 32,201,151;
            --base00: #282c34;
            --base01: #353b45;
            --base02: #3e4451;
            --base03: #868e96;
            --base04: #565c64;
            --base05: #abb2bf;
            --base06: #b6bdca;
            --base07: #d19a66;
            --base08: #e06c75;
            --base09: #d19a66;
            --base0A: #e5c07b;
            --base0B: #98c379;
            --base0C: #56b6c2;
            --base0D: #61afef;
            --base0E: #c678dd;
            --base0F: #be5046;
            color-scheme: light dark;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            --bs-gutter-y: 0;
            --bs-gutter-x: 3rem;
            box-sizing: border-box;
            margin: 0;
            text-transform: none;
            -webkit-appearance: button;
            --bs-btn-padding-x: .75rem;
            --bs-btn-padding-y: .375rem;
            --bs-btn-font-family: ;
            --bs-btn-font-size: 1rem;
            --bs-btn-font-weight: 400;
            --bs-btn-line-height: 1.5;
            --bs-btn-bg: transparent;
            --bs-btn-border-width: var(--bs-border-width);
            --bs-btn-border-radius: var(--bs-border-radius);
            --bs-btn-box-shadow: inset 0 1px 0 rgba(255,255,255,0.15),0 1px 1px rgba(0,0,0,0.075);
            --bs-btn-disabled-opacity: .65;
            --bs-btn-focus-box-shadow: 0 0 0 .25rem rgba(var(--bs-btn-focus-shadow-rgb), .5);
            display: inline-block;
            padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
            font-family: var(--bs-btn-font-family);
            font-size: var(--bs-btn-font-size);
            font-weight: var(--bs-btn-font-weight);
            line-height: var(--bs-btn-line-height);
            color: var(--bs-btn-color);
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            user-select: none;
            border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
            border-radius: var(--bs-btn-border-radius);
            background-color: var(--bs-btn-bg);
            transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out;
            --bs-btn-color: #198754;
            --bs-btn-border-color: #198754;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #198754;
            --bs-btn-hover-border-color: #198754;
            --bs-btn-focus-shadow-rgb: 220,53,69;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #198754;
            --bs-btn-active-border-color: #198754;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0,0,0,0.125);
            --bs-btn-disabled-color: #198754;
            --bs-btn-disabled-bg: transparent;
            --bs-btn-disabled-border-color: #198754;
            --bs-gradient: none;
            cursor: pointer;

            min-width: 100px;
            height: 40px;
            border-radius: 10px;
            margin-inline-start: 10px;


            width: 200px;
            height: 40px;
            font-size: 16px;
        }
        #btn_save{
            color: #198754;
            border-color: #198754;
        }
        #btn_save:hover{
            color: #fff;
            background-color: #198754;
            border-color: #198754;
        }
        #btn_save_abs{
            color: #5c636a;
            border-color: #5c636a;
        }
        #btn_save_abs:hover{
            color: #fff;
            background-color: #5c636a;
            border-color: #5c636a;
        }
        .btn-outline-danger{
            margin: 0 3px;
        }
        #btn_supr_horaire{
            color: #dc3545;
        }
        #btn_supr_horaire:hover{
            color: #fff;
        }
        #btn_pres_horaire{
            color: #898989;
            border-color: #898989;
        }
        #btn_pres_horaire:hover{
            color: #fff;
            background-color: #898989;
            border-color: #898989;
        }
        #btn_paste, #btn_copy{
            border-color: white;
            color: white;
        }
        .p_remove_margin{
            margin: 0 15px;
        }
        .p_margin{
            margin: 0 15px;
        }
        .employee {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .dropdown_i {
            position: relative;
            display: inline-block;
            border: 0;
            color: black;
            border-radius: 50px;
            background: #fff;
            margin: 0;
            padding: 4px 8px;
        }

        .dropdown_i:hover{
            border: 0;
        }
        .dropdown_i:focus{
            border: 0;
            box-shadow: initial;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 50;
        }

        .dropdown_i.active + .dropdown-content {
            display: block;
        }
        .hour span{
            font-size: 15px;
        }
    </style>
    <table>
        <thead>
        <tr>
            <th>
                <?php
                if (isset($_GET['semaine'])){
                    echo '<a href="planning.php?semaine='.($_GET['semaine']-1).'">
                            <button style="color: white;border-color: white" type="button" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
                                </svg>
                            </button>
                        </a>
                        <a href="planning.php?semaine='.($_GET['semaine']+1).'">
                            <button style="color: white;border-color: white" type="button" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"></path>
                                </svg>
                            </button>
                        </a>';

                }else{
                    echo '<a href="planning.php?semaine=-1">
                            <button style="color: white;border-color: white" type="button" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
                                </svg>
                            </button>
                        </a>
                        <a href="planning.php?semaine=1">
                            <button style="color: white;border-color: white" type="button" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"></path>
                                </svg>
                            </button>
                        </a>';

                }
                ?>
                <br>
                <a href="copy_past.php?a=0&url=<?php
                echo $_SERVER['REQUEST_URI'];
                if (isset($_GET['semaine'])){
                    $semaine_calc = date('W', strtotime($_GET['semaine'].' week'));
                    echo "&semaine=".$semaine_calc."&year=".date('Y');
                }else{
                    echo "&semaine=".date('W')."&year=".date('Y');
                }
                ?>">
                    <button title="copier" id="btn_copy" type="button" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"></path>
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"></path>
                        </svg>
                    </button>
                </a>
                <?php

                if (isset($_SESSION['copy'])){
                    if(isset($_GET['semaine'])){
                        echo'<a href="copy_past.php?a=1&url='.$_SERVER['REQUEST_URI'].'&new_semaine='.$_GET['semaine'].'">';
                    }else{
                        echo'<a href="copy_past.php?a=1&url='.$_SERVER['REQUEST_URI'].'&new_semaine=0">';
                    }
                    echo  '<button title="coller" id="btn_paste" type="button" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-plus-fill" viewBox="0 0 16 16">
                                <path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3Zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3Z"></path>
                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5v-1Zm4.5 6V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5a.5.5 0 0 1 1 0Z"></path>
                            </svg>
                        </button>
                    </a>';
                }else{
                    echo'<span>
                        <button disabled title="coller" id="btn_paste" type="button" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-plus-fill" viewBox="0 0 16 16">
                                <path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3Zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3Z"></path>
                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5v-1Zm4.5 6V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5a.5.5 0 0 1 1 0Z"></path>
                            </svg>
                        </button>
                    </span>';
                }
                ?>
            </th>
<?php
            // Récupérer la date de début et de fin de la semaine en cours
            if (isset($_GET['semaine'])){
                $debutSemaine = date("Y-m-d", strtotime('monday this week '.$_GET['semaine']." week"));
                $finSemaine = date("Y-m-d", strtotime('sunday this week '.$_GET['semaine']." week"));
            }else{
                $debutSemaine = date("Y-m-d", strtotime('monday this week'));
                $finSemaine = date("Y-m-d", strtotime('sunday this week'));
            }

            // Tableau des jours de la semaine
            $joursSemaine = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
            $dateCourante = $debutSemaine;
            $a=0;
            while ($dateCourante <= $finSemaine) {
                $select_badge = $db->query("SELECT badgeuse_badge.badge_date_entree, badgeuse_badge.badge_date_sortie, badgeuse_employer.employer_prenom, badgeuse_employer.employer_nom, badgeuse_badge.id_badge, badgeuse_badge.cron FROM badgeuse_badge, badgeuse_employer, badgeuse_contrat WHERE badgeuse_contrat.id_employer = badgeuse_badge.id_employer AND badgeuse_employer.id_employer = badgeuse_badge.id_employer AND DATE(badgeuse_badge.badge_date_entree) = '".$dateCourante."' AND badgeuse_contrat.id_etablissement = ".$_SESSION['etablissement'])->fetchAll();
                // Afficher la date
                if (date("d/m", strtotime($dateCourante)) == date("d/m")){
                    echo "<th onclick='showShift(".json_encode(regrouperParEmploye($select_badge)).", event, $a)' id='thShowShift$a'  class=\"active_j_actuelle\">";
                }
                else{
                    echo "<th onclick='showShift(".json_encode(regrouperParEmploye($select_badge)).", event, $a)'  id='thShowShift$a'>";
                }
                echo '<div id="div_ext_taget">
                        <button type="button" onclick="toggleDropdown(\'dropbtn'.$a.'\', event)" class="dropbtn'.$a.' dropdown_i btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                            </svg>
                        </button>
                        <div class="dropdown-content">
                            <a href="copy_past.php?b=3&url='.$_SERVER['REQUEST_URI'].'&date='.$dateCourante.'">Vider</a><br>
                            <a href="copy_past.php?b=0&url='.$_SERVER['REQUEST_URI'].'&date='.$dateCourante.'">Copier</a><br>
                            <a href="copy_past.php?b=1&url='.$_SERVER['REQUEST_URI'].'&date='.$dateCourante.'">Coller</a>';


                echo'   </div>
                    </div>
                        <span id="day" class="day">' . date("d/m", strtotime($dateCourante)) . '</span>
                        <span id="long" class="long">'.$joursSemaine[$a].'</span>';
                echo '</th>';
                // Passer au jour suivant
                $dateCourante = date("Y-m-d", strtotime($dateCourante . ' +1 day'));
                $a++;
            }


?>
        </tr>
        </thead>
        <tbody>

<?php
    $select_employer = $db->query("SELECT * FROM badgeuse_contrat, badgeuse_employer, badgeuse_etablissements WHERE badgeuse_etablissements.id_etablissement = badgeuse_contrat.id_etablissement AND badgeuse_contrat.id_employer = badgeuse_employer.id_employer AND badgeuse_contrat.id_etablissement = ".$_SESSION['etablissement'] )->fetchAll();
    foreach ($select_employer as $employer){
        $select_employer_planning = $db->query("SELECT * FROM badgeuse_planning where id_employer = ".$employer['id_employer'])->fetchAll();
        $select_em = $db->query("SELECT badgeuse_employer.id_employer, id_contrat, badgeuse_etablissements.id_etablissement, employer_nom, employer_prenom, etablissement_nom FROM badgeuse_contrat, badgeuse_employer, badgeuse_etablissements WHERE badgeuse_etablissements.id_etablissement = badgeuse_contrat.id_etablissement AND badgeuse_contrat.id_employer = badgeuse_employer.id_employer AND badgeuse_employer.id_employer = ".$employer['id_employer']." AND badgeuse_contrat.id_etablissement = ".$_SESSION['etablissement'] )->fetchArray();
        echo '<tr>
                <td class="hour" rowspan="1">
                    <span>'.$employer['employer_nom'].' '.$employer['employer_prenom'].'</span>
                    <span>'.$employer["type_contrat"].' ('.$employer["vol_h"].'h) ';


        if (isset($_GET['semaine'])){
            $semaine = date("W", strtotime($_GET['semaine']." week"));
            $year = date("Y", strtotime($_GET['semaine']." week"));
        }
        else{
            $semaine = date("W");
            $year = date("Y");
        }
        $select_calc_totalH = $db->query("SELECT * FROM badgeuse_planning where id_employer = ".$employer['id_employer'].' AND planning_annee = '.$year.' AND WEEK(planning_entree, 3) = '.$semaine)->fetchAll();
        $calcul_nombre_heure_semaine = 0;
        foreach ($select_calc_totalH as $entree){
            $date1 = new DateTime($entree['planning_entree']);
            $date2 = new DateTime($entree['planning_sortie']);

            $diff = $date2->diff($date1);

            $hours = $diff->h; // Nombre d'heures
            $daysInHours = $diff->days * 24; // Heures des jours

            $totalHours = $hours + $daysInHours + $diff->i / 60 + $diff->s / 3600; // Ajoute les minutes et secondes en fractions d'heure

            $calcul_nombre_heure_semaine += $totalHours;
        }

        echo'    <span style="text-decoration: underline dotted;display: initial;" title="Total d\'heure prévue dans le planning cette semaine">'.round($calcul_nombre_heure_semaine, 2).'h</span></span>
               </td>';

        $dateCourante = $debutSemaine;
        while ($dateCourante <= $finSemaine) {
            echo "<td onclick='addShift(".json_encode(filterByPlanningEntree($select_employer_planning, $dateCourante)).", ".json_encode($select_em).", \"$dateCourante\")'>";
            foreach ($select_employer_planning as $planning){

                $calcul_date = date("Y-m-d", strtotime($planning["planning_entree"], '-1 day'));
                if ($calcul_date == $dateCourante){
                    if ($planning['abs'] == 0){
                        echo  "<span class='span_horaire_cell'>".date("H:i", strtotime($planning["planning_entree"], '-1 day'))." - ".date("H:i", strtotime($planning["planning_sortie"], '-1 day')).'</span>';
                    }else{
                        echo  "<span title='".$planning['abs_type']."' class='span_horaire_cell_abs span_horaire_cell'>".date("H:i", strtotime($planning["planning_entree"], '-1 day'))." - ".date("H:i", strtotime($planning["planning_sortie"], '-1 day')).'<span class="exclude" > '.$planning['abs_type'].'</span></span>';
                    }
                }
            }
            echo '</td>';
            $dateCourante = date("Y-m-d", strtotime($dateCourante . ' +1 day'));
        }

         echo '</tr>';
    }
?>
        </tbody>
    </table>
    <div style="height: 1px"></div>
    <script>
        function addShift(planning, employer, day) {

            // Création de la div pop-up
            const popupDiv = document.createElement('div');
            popupDiv.classList.add('popup');

            // Création des éléments pour afficher les informations sur l'employé
            const employerInfoDiv = document.createElement('div');
            employerInfoDiv.innerHTML = `<h2>Informations sur l'employé :</h2>
      <p class="no_spacing_p">Nom: ${employer.employer_nom}</p>
      <p class="no_spacing_p">Prénom: ${employer.employer_prenom}</p>
      <p class="no_spacing_p">Etablissement: ${employer.etablissement_nom}</p>`;
            popupDiv.appendChild(employerInfoDiv);

            // Création des formulaires pour modifier les horaires existants
            if (planning.length > 0) {
                const existingShiftsDiv = document.createElement('div');
                existingShiftsDiv.innerHTML = '<h2>Modifier les horaires existants :</h2>';

                for (const shift of planning) {
                    const shiftForm = document.createElement('form');
                    shiftForm.method = "post";
                    shiftForm.action = "";
                    shiftForm.classList.add('form-container');
                    shiftForm.innerHTML = `<label for="planning_entree_${shift.id_planning}">Entrée :</label>
                               <input required type="time" id="planning_entree_${shift.id_planning}" name="planning_entree" value="${shift.planning_entree.slice(-8, -3)}">
                               <label for="planning_sortie_${shift.id_planning}">Sortie :</label>
                               <input required type="time" id="planning_sortie_${shift.id_planning}" name="planning_sortie" value="${shift.planning_sortie.slice(-8, -3)}">
                               <input type="hidden" name="planning_day" id="planning_day" required value="${day}">
                               <input type="hidden" name="planning_id" id="planning_id" required value="${shift.id_planning}">
                               <input type="hidden" name="planning_em" id="planning_em" required value="${employer.id_employer}">
                               <button type="submit" id="btn_save" class="btn btn-outline-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
                                  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"></path>
                                </svg>
                                Enregistrer
                              </button>
                               <a href="del_h.php?id=${shift.id_planning}&link=<?php echo $_SERVER['REQUEST_URI'];?>" id="btn_supr_horaire" class="btn btn-outline-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"></path>
                                    </svg>
                                     Supprimer
                               </a>`
                    if (shift.abs == 1){
                        shiftForm.innerHTML = shiftForm.innerHTML +
                            `<a href="pres_h.php?id=${shift.id_planning}&link=<?php echo $_SERVER['REQUEST_URI'];?>" id="btn_pres_horaire" class="btn btn-outline-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-check" viewBox="0 0 16 16">
                                      <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514ZM11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                      <path d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"/>
                                    </svg>
                                     Présent
                               </a>`;
                    }

                    existingShiftsDiv.appendChild(shiftForm);
                }

                popupDiv.appendChild(existingShiftsDiv);
            }

            // Création du formulaire pour ajouter une nouvelle horaire
            const newShiftForm = document.createElement('form');
            newShiftForm.method = "post";
            newShiftForm.action = "";
            newShiftForm.classList.add('form-container');
            newShiftForm.innerHTML = `<h2>Ajouter un nouvel horaire :</h2>
                              <label for="new_planning_entree">Entrée :</label>
                              <input required type="time" id="new_planning_entree" name="new_planning_entree">
                              <label for="new_planning_sortie">Sortie :</label>
                              <input required type="time" id="new_planning_sortie" name="new_planning_sortie">
                               <input type="hidden" name="planning_day" id="planning_day" required value="${day}">
                               <input type="hidden" name="planning_em" id="planning_em" required value="${employer.id_employer}">
                              <button type="submit" id="btn_save" class="btn btn-outline-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
                                  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"></path>
                                </svg>
                                Ajouter
                              </button>`;

            popupDiv.appendChild(newShiftForm);


            if (planning.length > 0){
                // Création du formulaire pour ajouter une nouvelle abscence
                const newAbsForm = document.createElement('form');
                newAbsForm.classList.add('form-container');
                newAbsForm.method = "post";
                newAbsForm.action = "";
                var select_horaire = "";
                for (const shift of planning) {
                    select_horaire = select_horaire + '<option value="'+shift.id_planning+'">' +
                                        shift.planning_entree.slice(-8)+" - "+shift.planning_sortie.slice(-8)+
                                        "</option>";
                }
                newAbsForm.innerHTML = `<h2>Ajouter une nouvelle abscence :</h2>
                                  <label for="horaire_abs">Selectionner l'horaire :</label>
                                    <select required name='horaire_abs'>`+select_horaire+`
                                    </select>
                                  <button type="submit" id="btn_save_abs" class="btn btn-outline-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-slash" viewBox="0 0 16 16">
                                      <path d="M13.879 10.414a2.501 2.501 0 0 0-3.465 3.465l3.465-3.465Zm.707.707-3.465 3.465a2.501 2.501 0 0 0 3.465-3.465Zm-4.56-1.096a3.5 3.5 0 1 1 4.949 4.95 3.5 3.5 0 0 1-4.95-4.95ZM11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"/>
                                    </svg>
                                    Ajouter
                                  </button>
                                    <br>
                                    <label for="type_abs">Sélectionner la raison</label>
                                    <select required name="type_abs">
                                        <option value="CP">Congés payés</option>
                                        <option value="AT">Arrêt de travail</option>
                                        <option value="AM">Arrêt maladie</option>
                                        <option value="AI">Absence injustifiée</option>
                                    </select>
                                    <input type="hidden" name="planning_day" id="planning_day" required value="${day}">
                                  `;

                popupDiv.appendChild(newAbsForm);

            }
            // Création de l'arrière-plan obscurci
            const overlayDiv = document.createElement('div');
            overlayDiv.classList.add('overlay');

            // Ajout de la div pop-up et de l'arrière-plan obscurci au body
            document.body.appendChild(overlayDiv);
            document.body.appendChild(popupDiv);

            // Fonction pour fermer la pop-up lorsque l'arrière-plan est cliqué
            overlayDiv.addEventListener('click', function() {
                document.body.removeChild(overlayDiv);
                document.body.removeChild(popupDiv);
            });
        }
        function showShift(shift, event, id_target) {
            id_target = "thShowShift"+id_target;
            if (event.target.id == id_target || event.target.id == "day" || event.target.id == "long" || event.target.id == "div_ext_taget") {
                // Création de la div pop-up
                const popupDiv = document.createElement('div');
                popupDiv.classList.add('popup');
                if (shift.length != 0) {
                    console.log(shift);
                    // Création des éléments pour afficher les informations sur l'employé
                    const titre = document.createElement('div');
                    titre.classList.add("employee");
                    titre.innerHTML = `<span id="span_code_couleur">
                                            Code couleur :
                                            <span aria-label="Badge Correcte" class="cron_badge_0 picto-item">.</span>
                                            <span aria-label="Erreur de badge" class="cron_badge_1 picto-item">.</span>
                                            <span aria-label="Badge corrigé après erreur" class="cron_badge_2 picto-item">.</span>
                                            <span aria-label="Badge Modifié" class="cron_badge_3 picto-item">.</span>
                                            <span aria-label="Badge Ajouté" class="cron_badge_4 picto-item">.</span>
                                        </span>
                                        <h2 style="text-decoration: underline">Badge du jour :</h2>`;
                    popupDiv.appendChild(titre);

                    for (const key in shift) {
                        if (shift.hasOwnProperty(key)) {
                            const entries = shift[key];
                            const employeeName = key.replace("_", " ");
                            const employeeDiv = document.createElement("div");
                            employeeDiv.innerHTML = "<h2>" + employeeName + "</h2>";
                            const div_horaire = document.createElement("div");
                            let div_flex; // Déclaration en dehors de la boucle forEach

                            entries.forEach(entry => {
                                const entryDiv = document.createElement("p");
                                entryDiv.classList.add("p_remove_margin");
                                if (entry.cron == 0){
                                    class_suplem = "from_cron_badge_0";
                                }else if (entry.cron == 1){
                                    class_suplem = "from_cron_badge_1";
                                }else if (entry.cron == 2){
                                    class_suplem = "from_cron_badge_2";
                                }else if (entry.cron == 3){
                                    class_suplem = "from_cron_badge_3";
                                }else if (entry.cron == 4){
                                    class_suplem = "from_cron_badge_4";
                                }
                                if (entry.badge_date_sortie == null) {
                                    entryDiv.innerHTML = "<form class='form_planning_mof_badge "+ class_suplem +"' method='post' action='modif_badge.php'>" +
                                                             "<label for='formulaire_modif_badge_entree'>Arrivée : </label>" +
                                                             "<input required type='time' name='entree' id='formulaire_modif_badge_entree' value='" + entry.badge_date_entree.slice(-8, -3) + "'>"+

                                                             "<span class='p_margin'></span>" +

                                                             "<label for='formulaire_modif_badge_sortie'>Départ : </label>" +
                                                             "<input required type='time' name='sortie' id='formulaire_modif_badge_sortie' >"+
                                                             "<input type='hidden' name='id_badge' value='"+ entry.id_badge +"'>"+
                                                             "<input type='hidden' name='date_badge' value='"+ entry.badge_date_entree.slice(0, 10) +"'>"+
                                                             "<input class='input_modifier_arrive' type='submit' value='Modifier'>"+
                                                         "</form>";

                                } else {
                                    entryDiv.innerHTML = "<form class='form_planning_mof_badge "+ class_suplem +"' method='post' action='modif_badge.php'>" +
                                                             "<label for='formulaire_modif_badge_entree'>Arrivée : </label>" +
                                                             "<input required type='time' name='entree' id='formulaire_modif_badge_entree' value='" + entry.badge_date_entree.slice(-8, -3) + "'>"+

                                                             "<span class='p_margin'></span>" +

                                                             "<label for='formulaire_modif_badge_sortie'>Départ : </label>" +
                                                             "<input required type='time' name='sortie' id='formulaire_modif_badge_sortie' value='" + entry.badge_date_sortie.slice(-8, -3) + "'>"+
                                                             "<input type='hidden' name='id_badge' value='"+ entry.id_badge +"'>"+
                                                             "<input type='hidden' name='date_badge' value='"+ entry.badge_date_entree.slice(0, 10) +"'>"+
                                                             "<input type='submit' value='Modifier'>"+
                                                         "</form>";
                                }

                                div_horaire.appendChild(entryDiv);
                                employeeDiv.appendChild(div_horaire);
                            });

                            popupDiv.appendChild(employeeDiv);
                        }
                    }


                } else {
                    const titre = document.createElement('div');
                    titre.classList.add("employee");
                    titre.innerHTML = `<h3>Aucun badge ce jour</h3>`;
                    popupDiv.appendChild(titre);
                }

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
        }
        function filterByPlanningEntree(array, planningEntree) {
            // Utilisez la méthode filter() pour filtrer les dictionnaires avec la partie jour de "planning_entree" correspondante
            const filteredArray = array.filter(item => item.planning_entree.startsWith(planningEntree));
            return filteredArray;
        }


        function toggleDropdown(className, event) {
            event.stopPropagation();

            const activeButtons = document.querySelectorAll('.active');

            activeButtons.forEach(button => {
                if (!button.classList.contains(className)) {
                    button.classList.remove('active');
                }
            });

            const dropdown = document.querySelector('.' + className);
            dropdown.classList.toggle('active');
        }

    </script>
<?php
include ('footer.php')
?>
</body>

</html>
