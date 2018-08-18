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


Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
if(isset($_GET['id'])) {
    $dotaznik = Db::queryOne('SELECT * FROM ' . $tables['dotaznik'] . ' WHERE dotaznik_id = ?', $_GET['id']);
    $Questionnaire = new Questionnaire($dotaznik['nazev'], $dotaznik['doplneni'], $dotaznik['kategorie'], $dotaznik['rok'], $dotaznik['dotaznik_id']);
}
?>