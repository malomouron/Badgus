<!DOCTYPE html>
<html>
<head>
    <title>PHP Info</title>
</head>
<body>
<h1>Informations sur PHP</h1>
<?php
$year = 2023;
$month = 10;
$firstDayOfMonth = new DateTime("$year-$month-01");
$weekNumber = $firstDayOfMonth->format("W");
while ($firstDayOfMonth->format("m") == $month) {
    $sundayOfCurrentWeek = clone $firstDayOfMonth;
    $sundayOfCurrentWeek->modify('next Sunday');
    if ($sundayOfCurrentWeek->format("m") == $month) {
        echo "Semaine $weekNumber : du " . $firstDayOfMonth->format("Y-m-d") . " au " . $sundayOfCurrentWeek->format("Y-m-d") . "\n";
    }
    $firstDayOfMonth->modify('next Monday');
    $weekNumber++;
}
?>
</body>
</html>
