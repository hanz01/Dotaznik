<?php
session_start();
include("config.php");
include("classes/Db.php");
Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
if(!empty($_SESSION['zprava']))  {
    $zprava = $_SESSION['zprava'];
    unset($_SESSION['zprava']);
}
if($_POST) {
    if(isset($_POST['kategorie'])) {
        $kategorie = $_POST['kategorie'];
        $dotazniky = Db::queryAll('SELECT * FROM ' . $tables['dotaznik'] . ' WHERE kategorie = ? AND stav = 1', $kategorie);
        $pocet = count($dotazniky);
        if($pocet == 0) {
            $zprava = 'Pro zvolenou kategorii nebyl nalezen žádný dotazník.';
        }
        else if($pocet == 1) {
            $id = $dotazniky[0]['dotaznik_id'];
            $odpovedi = Db::queryAll('SELECT * FROM ' . $tables['odpovedi'] . ' WHERE dotaznik_id = ?  AND respondent like("%anonym%") GROUP BY respondent', $id);
            $akt = count($odpovedi) + 1;
            $respondent = 'anonym-' .  $akt;
            header('location: dotaznik.php?id=' . $id . '&respondent=' . $respondent);
            exit();

        }

    }
    else if(isset($_POST['kod'])) {
        $kod = $_POST['kod'];
        echo $kod;
    }
}
$kategorie = Db::queryAll('SELECT id, nazev FROM ' . $bebras['kategorie']);
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Bobřík informatiky - online dotazníky</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/apps.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
<header class="container">
    <img src="images/header.png" alt="Hlavní logo soutěže" />
</header>
<main class="container">
    <?php if(isset($zprava)) : ?>
        <h4 class="text-center text-danger"><?= $zprava ?></h4>
    <?php endif; ?>
    <div class="jumbotron">
        <h1 class="text-center">Bobřík informatiky - online dotazníky</h1>

            <p>Vítejte v online dotaznících pro podporu inforamtické soutěže Bobřík informatiky.</p>
            <p>Pokud se chcete zúčastnit dotazníkového šetření, zvolte si prosím jednu z následujích možností.</p>
            <form method="post" action="dotaznik.php">
                <input type="hidden" name="render" value="true" />
            <div id="accordion">
                <div class="card">
                    <div class="card-header">
                        <a class="card-link" data-toggle="collapse" href="#collapseOne">
                            <h3 class="text-center">Pamatuji si svůj soutěžní kód</h3>
                        </a>
                    </div>
                    <div id="collapseOne" class="collapse show" data-parent="#accordion">
                        <div class="card-body text-center">
                            <h4 class="text-center">Zadejte svůj soutěžní kód</h4><br />
                            <input type="text" class="form-control text-center" placeholder="Soutěžní kód" name="kod" /><br />
                            <input type="submit" class="btn btn-danger btn-lg" value="Pokračovat" />
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
                            <h3 class="text-center">Nepamatuji si soutěžní kód</h3>
                        </a>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-body text-center">
                            <h4 class="text-center">Zvolte si prosím svou soutěžní kategorii</h4>
                            <select name="kategorie" class="form-control text-center">
                                <option value="" disabled selected>Soutěžní kategorie</option>
                                <?php foreach ($kategorie as $k) : ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nazev'] ?></option>
                                <?php endforeach; ?>
                            </select><br />
                            <input type="submit" class="btn btn-danger btn-lg" value="Pokračovat" />
                        </div>
                    </div>
                </div>
            </div>
            </form>
</main>
<footer class="container">
    <p>&copy; Petr Hanzal 2018</p>
</footer>
</body>