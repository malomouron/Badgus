<?php
include("config.inc.badger.php");
include('functions.php');
include('db.php');

$db = new db($dbhost, $dbuser, $dbpass, $dbname);


$now = new DateTime();
$startTime = new DateTime('23:30');
$endTime = new DateTime('00:30');

if ($endTime < $startTime) {
    $endTime->modify('+1 day');
}


//if ($now >= $startTime && $now <= $endTime) {
    $select_dernier_entree = $db->query("SELECT bb.* FROM badgeuse_badge bb JOIN ( SELECT id_employer, MAX(id_badge) AS max_id_badge FROM badgeuse_badge GROUP BY id_employer ) max_badge ON bb.id_employer = max_badge.id_employer AND bb.id_badge = max_badge.max_id_badge;")->fetchAll();

    foreach ($select_dernier_entree as $employer){
        if ($employer['badge_date_sortie'] == NULL){


            $dateReference = new DateTime($employer['badge_date_entree']);
            $dateReference->modify('+8 hours');
            $dateActuelle = new DateTime();
            if ($dateActuelle > $dateReference) {
                $update = $db->query("UPDATE `badgeuse_badge` SET badge_date_sortie = CURRENT_TIMESTAMP WHERE id_badge = ".$employer['id_badge']);
                $update_cron = $db->query("UPDATE `badgeuse_badge` SET cron = 1 WHERE id_badge = ".$employer['id_badge']);
            }
        }
    }
//}
$db->close();
?>