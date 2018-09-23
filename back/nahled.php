<?php
include("../config.php");
include("../classes/Db.php");
include("../classes/Question.php");
include("../classes/HtmlBuilder.php");
include("../classes/Questionnaire.php");
include("../classes/QuestionText.php");
include("../classes/QuestionTextLong.php");
include("../classes/QuestionSelect.php");
include("../classes/QuestionSelectNumber.php");
include("../classes/QuestionSelectNumberSet.php");


Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
if(isset($_GET['id'])) {
    $dotaznik = Db::queryOne('SELECT * FROM ' . $tables['dotaznik'] . ' WHERE dotaznik_id = ?', $_GET['id']);
    $Questionnaire = new Questionnaire($dotaznik['nazev'], $dotaznik['doplneni'], $dotaznik['kategorie'], $dotaznik['rok'], $dotaznik['dotaznik_id']);

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

    <header class="container">
        <img src="../images/header.png" alt="Hlavní logo soutěže" />
    </header>
    <main class="container">
        <?= $Questionnaire->renderHeader(); ?>
        <form method="post">
            <?=            $Questionnaire->render();       ?>
        </form>
    </main>
    <footer class="container">
        <p>&copy; Petr Hanzal 2018</p>
    </footer>
</body>
</html>

