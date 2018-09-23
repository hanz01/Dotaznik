<?php
include("../config.php");
include("../classes/Db.php");
include("../libs/PHPExcel.php");

if(!isset($_GET['id'])) {
    header('location: admin.php');
    exit();
}
Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
$dotaznik = Db::queryOne('SELECT * FROM ' . $tables['dotaznik'] . ' WHERE dotaznik_id = ?', $_GET['id']);
$otazkyArray = Db::queryAll('SELECT * FROM ' . $tables['otazky'] . ' WHERE dotaznik_id = ?', $_GET['id']);
$soutezniOtazky = Db::QueryAll('SELECT nazev FROM ' . $bebras['otazky'] . ' WHERE kategorie = ?', $dotaznik['kategorie']);

$pocetRespondentu = Db::queryOne('SELECT count(*) FROM (SELECT count(*) FROM `odpovedi` WHERE dotaznik_id = ? GROUP by respondent ) as g', $_GET['id'])['count(*)'];

$odpovedi = Db::queryAll('SELECT respondent, typ, `odpovedi`.otazka, odpoved FROM `odpovedi` JOIN otazky ON `odpovedi`.dotaznik_id = 12 AND `odpovedi`.otazka = otazky_id');
$odpoved = array();

$pocetOtazek = count($otazkyArray);
$pozice = 1;
$index = 0;

for($pruchod=1; $pruchod<=$pocetRespondentu; $pruchod++) {
    $odpoved[$index][] = $odpovedi[$pozice]['respondent'];

    for ($i = $pozice-1; $i < $pocetOtazek * $pruchod; $i++) {
        if ($odpovedi[$i]['typ'] == 'sada1' || $odpovedi[$i]['typ'] == 'sada2') {
            $data = explode(";", $odpovedi[$i]['odpoved']);
            foreach ($data as $d) {
                $odpoved[$index][] = $d;
            }
        } else {
            $odpoved[$index][] = $odpovedi[$i]['odpoved'];
        }
    }

    $pozice = $pocetOtazek * $pruchod + 1;
    $index++;
}




$otazky = array();
foreach($otazkyArray as $o) {
    if($o['typ'] == 'sada1' || $o['typ'] == 'sada2') {
        foreach($soutezniOtazky as $s) {
            $otazky[] = $s['nazev'];
        }
    }
    else
        $otazky[] = $o['otazka'];
}
$xls = new PHPExcel();
$list1 = $xls->setActiveSheetIndex(0);
$list1->setCellValue('A1', 'Výsledky dotazníku ' . $dotaznik['nazev']);
$list1->setCellValue('C3', 'Soutěžní otázky');
$list1->setCellValue('B4', 'Respondent');

$xls->getActiveSheet()
    ->fromArray(
        $otazky,   // The data to set
        NULL,        // Array values with this value will not be set
        'C4'         // Top left coordinate of the worksheet range where
    //    we want to set these values (default is A1)
    );
$i = 5;
foreach ($odpoved as $r) {
    $xls->getActiveSheet()
        ->fromArray(
            $r,   // The data to set
            '',        // Array values with this value will not be set
            'B'  . $i       // Top left coordinate of the worksheet range where
        //    we want to set these values (default is A1)
        );
    $i++;
}

$newExcelWriter = new PHPExcel_Writer_Excel2007($xls);
$newExcelWriter->save('./vysledky.xlsx');

header('location: vysledky.xlsx');


?>
<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Your Website</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Bobřík informatiky - online dotazníky</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/apps.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
<header class="container">
    <img src="../images/header.png" alt="Hlavní logo soutěže" />
</header>
<main class="container">

</main>
<footer class="container">
    <p>&copy; Petr Hanzal 2018</p>
</footer>
</body>