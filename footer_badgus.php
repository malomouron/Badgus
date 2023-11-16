<?php /** @noinspection PhpUndefinedVariableInspection */
if(isset($_COOKIE['Etablissement_id'])){
    echo '<a href="changer_etab.php" id="img_changer"><img alt="changer d\'établissement" src="media/rest.png"></a>';
    $select_etab = $db->query("SELECT * FROM badgeuse_etablissements where id_etablissement = ".$_COOKIE['Etablissement_id'])->fetchArray();
}
echo   '<div id="footer_div">
            <hr>
            <p style="margin: 0"><span style="float: left;margin-inline-start: 45%;font-size: 19px;font-weight: bold;text-decoration: underline;">';
if (isset($select_etab)){
    echo $select_etab["etablissement_nom"];
}
echo    '</span>Badg\'us - © Malo MOURON - Createur de site internet<br>Adresse de contact : <a href="mailto:contact-support@badgus.fr">contact-support@badgus.fr</a></p>
        </div>';
    $db->close();
?>