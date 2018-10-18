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
session_start();
if(empty($_SESSION['user'])) {
    header('location: login.php');
    exit();
}

Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
if(isset($_GET['id'])) {
    $dotaznik = Db::queryOne('SELECT * FROM ' . $tables['dotaznik'] . ' WHERE dotaznik_id = ?', $_GET['id']);
    $Questionnaire = new Questionnaire($dotaznik['nazev'], $dotaznik['doplneni'], $dotaznik['kategorie'], $dotaznik['rok'], $dotaznik['dotaznik_id'], $dotaznik['podminky']);

}

?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Bobřík informatiky - online dotazníky</title>
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
        <nav class="navbar navbar-expand-lg">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Administrace</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create.php">Vytvořit nový dotazník</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Přidat uživatele</a>
                    </li>
                </ul>
            </div>
        </nav>
        <h2 class="text-center">Náhled dotazníku</h2>
        <?= $Questionnaire->renderHeader(); ?>
        <?php if(!empty($Questionnaire->getPodminky())) : ?>
        <p>Odesláním dotazníku souhlasím s <a href="#" data-toggle="modal" data-target="#licence">podmínakami výzkumu.</a>
        <?php endif; ?>
        <form method="post">
            <?=     $Questionnaire->render();       ?>
        </form>
        <div class="modal fade" id="licence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Podmínky výzkumu</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left">
                       <p><?= $Questionnaire->getPodminky(); ?></p>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="container">
        <p>&copy; Petr Hanzal 2018</p>
    </footer>
</body>
</html>

