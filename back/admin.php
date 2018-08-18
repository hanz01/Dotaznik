<?php
include("../config.php");
include("../classes/Db.php");
Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
$data = Db::queryAll('SELECT * FROM ' . $tables['dotaznik']);
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
        <h2 class="text-center">Seznam dotazníků</h2>
        <?php if(count($data) > 0) : ?>
            <table class="table">
                <tr>
                    <th>Název</th>
                    <th>Kategorie</th>
                    <th>Rok</th>
                    <th>Datum vytvoření</th>
                    <th>Stav</th>
                </tr>
                <?php foreach($data as $d) : ?>
                <tr>
                    <td><a href="nahled.php?id=<?= $d['dotaznik_id'] ?>"><?= $d['nazev'] ?></a></td>
                    <td><?= $d['kategorie'] ?></td>
                    <td><?= $d['rok'] ?></td>
                    <td><?= $d['vytvoreno'] ?></td>
                    <td>
                        <?php if($d['stav'] == 0)
                            echo 'Rozpracováný <a href="?spustit='.$d['dotaznik_id'].'">spustit</a>';
                        elseif($d['stav'] == 1)
                            echo "Probíhající ukončit";
                        else
                            echo "Ukončený";
                            ?>
                        </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
        <h3 class="text-center">Nebyli nalezeny žádné dotazníky</h3>
        <?php endif; ?>
    </div>
</body>
</html>

