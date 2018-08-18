<?php
include("classes/HtmlBuilder.php");
include("classes/Question.php");
include("classes/QuestionText.php");
include("classes/QuestionTextLong.php");

include("classes/QuestionSelect.php");
include("classes/QuestionSelectNumber.php");

include("classes/Questionnaire.php");
$q = new Questionnaire("Dotazník 1", "info", "Kategorie", 2018, 1);
if($q->isPosted()) {
    $q->validateForm();
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Your Website</title>
    <link rel="stylesheet" href="css/apps.css" type="text/css" />
    <script type="text/javascript" src="js/apps.js" ></script>
</head>
<body>
    <?= $q->renderHeader(); ?>
    <form method="post">
    <?= $q->render(); ?>
    </form>
</body>