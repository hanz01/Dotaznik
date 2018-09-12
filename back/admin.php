<?php
include("../config.php");
include("../classes/Db.php");
Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
$data = Db::queryAll('SELECT * FROM ' . $tables['dotaznik'] . ' JOIN ' . $bebras['kategorie'] . ' ON id = kategorie');
$data1 = Db::queryAll('SELECT * FROM ' . $tables['dotaznik']);

if(isset($_GET['spustit']) && $_GET['spustit'] > 0) {
    $q = Db::query('UPDATE ' . $tables['dotaznik'] . ' SET stav = 1 WHERE dotaznik_id = ?', $_GET['spustit']);
    if($q) {
        header('location: admin.php');
    }
}

if(isset($_GET['konec']) && $_GET['konec'] > 0) {
    $q = Db::query('UPDATE ' . $tables['dotaznik'] . ' SET stav = 2 WHERE dotaznik_id = ?', $_GET['konec']);
    if($q) {
        header('location: admin.php');
    }
}
if(isset($_GET['delete']) && $_GET['delete'] > 0) {
    $q = Db::query('DELETE FROM ' . $tables['dotaznik'] . ' WHERE dotaznik_id = ?', $_GET['delete']);
    if($q) {
        header('location: admin.php');
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Your Website</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/apps.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script type="text/javascript"></script>
</head>
<body>
    <div class="container">
        <?php
        if(isset($_GET['vytvoreno'])) {
            echo '<p>Dotazník byl vytvořen</p>';
        }
        ?>
        <h2 class="text-center">Seznam dotazníků</h2>
        <h4><a href="create.php">Vytvořit nový</a></h4>
        <?php if(count($data) > 0) : ?>
            <table class="table">
                <tr>
                    <th>Název</th>
                    <th>Kategorie</th>
                    <th>Rok</th>
                    <th>Datum vytvoření</th>
                    <th>Stav</th>
                </tr>
                <?php $i=0; foreach($data as $d) : ?>
                <tr>
                    <td><a href="nahled.php?id=<?= $d['dotaznik_id'] ?>"><?= $data1[$i]['nazev'] ?></a></td>
                    <td><?= $d['nazev'] ?></td>
                    <td><?= $d['rok'] ?></td>
                    <td><?= $d['vytvoreno'] ?></td>
                    <td>
                         <?php
                         $pocet = Db::queryOne('SELECT count(*) FROM (SELECT count(*) FROM `odpovedi` WHERE dotaznik_id = '.  $d['dotaznik_id'] .' GROUP by respondent ) as g');
                         ?>
                        <?php if($d['stav'] == 0)
                            echo 'Rozpracováný <a href="?spustit='.$d['dotaznik_id'].'">spustit <a href="edit.php?id='.$d['dotaznik_id'].'">Upravit</a> <a href="?delete='.$d['dotaznik_id'].'">Smazat</a></a>';
                        elseif($d['stav'] == 1) {
                            echo 'Probíhající <a href="?konec=' . $d['dotaznik_id'] . '">ukončit</a><br />';
                            echo("Počet respondentů: " . $pocet['count(*)']);
                        }

                        else {
                            echo 'Ukončený <a href="?vysledek=' . $d['dotaznik_id'] . '">výsledky <a href="?delete=' . $d['dotaznik_id'] . '">Smazat</a></a>';
                            echo("Počet respondentů: " . $pocet['count(*)']);
                        }
                            ?>
                        </td>
                </tr>
                <?php $i++; endforeach; ?>
            </table>
        <?php else : ?>
        <h3 class="text-center">Nebyli nalezeny žádné dotazníky</h3>
        <?php endif; ?>
    </div>
</body>
</html>

