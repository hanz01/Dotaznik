<?php
include("classes/HtmlBuilder.php");
include("classes/Question.php");
include("classes/QuestionText.php");
include("classes/QuestionSelect.php");

include("classes/Questionnaire.php");
$q = new Questionnaire("Dotazník 1", "Kategorie", 2018);
if($q->isPosted()) {
    $q->validateForm();
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Your Website</title>
</head>
<body>
    <?= $q->renderHeader(); ?>
    <form method="post">
    <?= $q->render(); ?>
    </form>
</body>