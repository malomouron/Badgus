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
        echo '<style>
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
    </style>';
        echo '<h1 id="h1ShowPlanning">Vos horaires</h1>
            <table>
                <thead>
                <tr>
                    <th>';
        if (isset($_GET['semaine'])){
            echo '<form method="post" action="showPlanning.php?semaine='.($_GET['semaine']-1).'">
                    <input type="hidden" value="'.$_POST['code_emploi'].'" name="code_emploi" id="code_emploi">
                    <button type="submit" style="color: white;border-color: white" type="button" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
                        </svg>
                    </button>
                </form>
                <form method="post" action="showPlanning.php?semaine='.($_GET['semaine']+1).'">
                    <input type="hidden" value="'.$_POST['code_emploi'].'" name="code_emploi" id="code_emploi">
                    <button type="submit" style="color: white;border-color: white" type="button" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"></path>
                        </svg>
                    </button>
                </form>';

        }else{
            echo '<form method="post" action="showPlanning.php?semaine=-1">
                    <input type="hidden" value="'.$_POST['code_emploi'].'" name="code_emploi" id="code_emploi">
                    <button type="submit" style="color: white;border-color: white" type="button" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
                        </svg>
                    </button>
                </form>
                <form method="post" action="showPlanning.php?semaine=1">
                    <input type="hidden" value="'.$_POST['code_emploi'].'" name="code_emploi" id="code_emploi">
                    <button type="submit" style="color: white;border-color: white" type="button" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"></path>
                        </svg>
                    </button>
                </form>';
        }
        echo '</th>';
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
            // Afficher la date
            if (date("d/m", strtotime($dateCourante)) == date("d/m")){
                echo "<th id='thShowShift$a'  class=\"active_j_actuelle\">";
            }
            else{
                echo "<th id='thShowShift$a'>";
            }
            echo '<span id="day" class="day">' . date("d/m", strtotime($dateCourante)) . '</span>
                  <span id="long" class="long">'.$joursSemaine[$a].'</span>';
            echo '</th>';
            // Passer au jour suivant
            $dateCourante = date("Y-m-d", strtotime($dateCourante . ' +1 day'));
            $a++;
        }
        echo '  </tr>
            </thead>
        <tbody>';

        $select_employer_planning = $db->query("SELECT * FROM badgeuse_planning where id_employer = ".$employer['id_employer'])->fetchAll();
        echo '<tr>
                <td class="hour" rowspan="1">
                    <span>'.$employer['employer_nom'].' '.$employer['employer_prenom'].'</span>
             </td>';

        $dateCourante = $debutSemaine;
        while ($dateCourante <= $finSemaine) {
            echo "<td>";
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

         echo       '</tr>
                </tbody>
            </table>';
    }
    else{
        header('Location: showPlanning.php');
    }
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
        <form action=\"showPlanning.php\" method=\"post\">
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