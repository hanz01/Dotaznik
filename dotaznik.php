<?php
session_start();
include("config.php");
include("classes/Db.php");
include("classes/Question.php");
include("classes/HtmlBuilder.php");
include("classes/Questionnaire.php");
include("classes/QuestionText.php");
include("classes/QuestionTextLong.php");
include("classes/QuestionSelect.php");
include("classes/QuestionSelectNumber.php");
include("classes/QuestionSelectNumberSet.php");


Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
if($_POST) {
    if(isset($_POST['kategorie'])) {
        $kategorie = $_POST['kategorie'];
        $dotazniky = Db::queryAll('SELECT * FROM ' . $tables['dotaznik'] . ' WHERE kategorie = ? AND stav = 1', $kategorie);
        $pocet = count($dotazniky);
        if($pocet == 0) {
            $_SESSION['zprava'] = 'Pro zvolenou kategorii nebyl nalezen žádný dotazník.';
            header('location: index.php');
            exit();
        }
        else if($pocet == 1) {
            $id = $dotazniky[0]['dotaznik_id'];
            $odpovedi = Db::queryAll('SELECT * FROM ' . $tables['odpovedi'] . ' WHERE dotaznik_id = ?  AND respondent like("%anonym%") GROUP BY respondent', $id);
            $akt = count($odpovedi) + 1;
            $respondent = 'anonym-' .  $akt;
        }
    }
    else if(isset($_POST['kod'])) {
        $kod = $_POST['kod'];
        echo $kod;
    }
}
if(isset($id) && isset($respondent)) {
    if(Db::query('SELECT * FROM ' . $tables['odpovedi'] . ' WHERE respondent = ? AND dotaznik_id = ?', $respondent, $id) != 0) {
        $_SESSION['zprava'] = 'Na jeden dotazník není možné odpovídat vícekrát';
        header('location: index.php');
        exit();
    }
    $dotaznik = Db::queryOne('SELECT * FROM ' . $tables['dotaznik'] . ' WHERE dotaznik_id = ? AND stav = 1', $id);
    if(!is_array($dotaznik)) {
        $_SESSION['zprava'] = 'Dotazník nebyl nalezen';
        header('location: index.php');
        exit;
    }
    $Questionnaire = new Questionnaire($dotaznik['nazev'], $dotaznik['doplneni'], $dotaznik['kategorie'], $dotaznik['rok'], $dotaznik['dotaznik_id']);
    if($Questionnaire->isPosted()) {
        if($Questionnaire->validateForm()) {
            foreach ($Questionnaire->getData() as $k => $item) {
                $data = array(
                    'dotaznik_id' => $Questionnaire->getId(),
                    'respondent' => $_POST['respondent'],
                    'otazka' => $k,
                    'odpoved' => $item
                );
                Db::insert($tables['odpovedi'], $data);
            }
            header('location: konec.php');
        }
        }
}
else {
    $_SESSION['zprava'] = 'Přístup zamítnut';
    header('location: index.php');
    exit();
}
?>
    <!DOCTYPE html>
    <html>

    <head>
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


        <script type="text/javascript"></script>
    </head>
    <body>
    <header class="container">
        <img src="images/header.png" alt="Hlavní logo soutěže" />
    </header>
    <main class="container">
        <?= $Questionnaire->renderHeader(); ?>
        <form method="post">
            <input type="hidden" name="kategorie" value="<?php if(isset($_POST['kategorie'])) echo $_POST['kategorie']; ?>" />
            <input type="hidden" name="kod" value="<?php if(isset($_POST['kod'])) echo $_POST['kod']; ?>" />
            <input type="hidden" name="respondent" value="<?= $respondent ?>" />
            <?=            $Questionnaire->render();       ?>
        </form>
    </main>
    <footer class="container">
        <p>&copy; Petr Hanzal 2018</p>
    </footer>
    </body>
    </html>
