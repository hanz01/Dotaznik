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
session_start();
if(empty($_SESSION['user'])) {
    header('location: login.php');
    exit();
}
Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
if(isset($_GET['id'])) {
    $dotaznik = Db::queryOne('SELECT * FROM ' . $tables['dotaznik'] . ' JOIN ' . $bebras['kategorie'] . ' ON id = kategorie WHERE dotaznik_id = ?', $_GET['id']);
    $dotaznik1 = Db::queryOne('SELECT * FROM ' . $tables['dotaznik'] . ' WHERE dotaznik_id = ?', $_GET['id']);
    $dotaznikId = $dotaznik['dotaznik_id'];
    if($dotaznik['stav'] != 0) {
        header('location: admin.php');
        exit;
    }
    $kategorie = Db::queryAll('SELECT id, nazev FROM ' . $bebras['kategorie'] . ' WHERE id NOT in (?)', $dotaznik['kategorie']);
    $otazky = Db::queryAll('SELECT * FROM '. $tables['otazky'] . ' WHERE dotaznik_id = ? ORDER BY otazky_id', $dotaznik['dotaznik_id']);
    if($_POST) {
        $poradi = 1;
        $moznosti = array();
        foreach($_POST['moznost'] as $moznost) {
            $moznosti['moznost-'.$poradi] = $moznost;
            $poradi++;
        }
        $_POST['moznost'] = $moznosti;
        if(isset($_POST['otazka'])) {
            $form = array(
                'nazev' => $_POST['nazev'],
                'doplneni' => $_POST['informace'],
                'kategorie' => $_POST['kategorie'],
                'rok' => $_POST['rok']
            );
            Db::query('UPDATE ' . $tables['dotaznik'] . ' SET nazev = ?, kategorie = ?, doplneni = ?, rok = ? WHERE dotaznik_id = ?', $form['nazev'], $form['kategorie'], $form['doplneni'], $form['rok'], $_GET['id']);
            $dotaznik = $dotaznik['dotaznik_id'];
            $pocet = count($_POST['otazka']);
            Db::query('DELETE FROM ' . $tables['otazky'] . ' WHERE dotaznik_id = ?', $dotaznikId);

            for ($i = 0; $i < $pocet; $i++) {
                $idOtazky = $_POST['id'][$i];
                $otazka = $_POST['otazka'][$i];
                $doplneni = $_POST['poznamka'][$i];
                $typ = $_POST['typ'][$i];
                $otazka = $_POST['otazka'][$i];
                $label1 = $_POST['lable1'][$i];
                $label2 = $_POST['lable2'][$i];

                if (!isset($_POST['cancel'][$i]))
                    $cancel = 'NULL';
                else
                    $cancel = $_POST['cancel'][$i];
                $otazka = array(
                    'dotaznik_id' => $dotaznik,
                    'otazka' => $otazka,
                    'doplneni' => $doplneni,
                    'typ' => $typ,
                    'label1' => $label1,
                    'label2' => $label2,
                    'cancel' => $cancel
                );

                Db::insert($tables['otazky'], $otazka);

                $a = $i+1; //možnost index
                $moznosti = $_POST['moznost']['moznost-' . $a];

                if(is_array($moznosti) and $moznosti[0] != 'NULL') {
                    $id = Db::getLastId($tables['otazky']);
                    $typ = Db::queryOne('SELECT * FROM otazky WHERE otazky_id = ?', $id)['typ'];
                    if($typ == '1' || $typ == '2' || $typ == '3' || $typ == '4' ) {
                        $id = $id;
                    }
                    else {
                        $id = $id - 1;
                    }
                    echo $id;
                    foreach ($moznosti as $m) {
                        $moznost = array(
                            'moznost' => $m,
                            'otazky_id' => $id
                        );

                        Db::query('DELETE FROM moznosti WHERE otazky_id = ?', $idOtazky);
                        Db::insert($tables['moznosti'], $moznost);
                    }
                }
            }
            header('location: edit.php?id=' . $_GET['id']);
            exit;
        }

    }
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>


    <script type="text/javascript" src="editor.js">   </script>
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
<p id="pocet" style="display: none">
    <?php
    echo count($otazky);
    ?>
</p>
<form method="post">
    <header>
        <h2>Úprava dotazníku</h2>
        <div class="container ">
            <input type="text" name="nazev" value="<?= $dotaznik1['nazev'] ?>" class="form-control" placeholder="Název formuláře" /><br />
            <textarea name="informace" class="form-control" placeholder="Informace pro doplnění" ><?= $dotaznik['doplneni'] ?></textarea><br />
            <?php if(count($kategorie) > 0) : ?>
                <div class="row">
                    <div class="col-lg-2 md-6">
                        <span>Kategorie</span>
                    </div>
                    <div class="col-lg-10 md-6">
                        <select name="kategorie" class="form-control">
                            <option value="<?= $dotaznik['kategorie'] ?>"><?= $dotaznik['nazev'] ?></option>
                            <?php foreach ($kategorie as $k) : ?>
                                <option value="<?= $k['id'] ?>"><?= $k['nazev'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <br />
            <?php endif; ?>
            <select name="rok" class="form-control">
                <option vlaue="<?= $dotaznik['rok'] ?>"><?= $dotaznik['rok'] ?></option>
                <option vlaue="<?= $dotaznik['rok'] - 1 ?>"><?= $dotaznik['rok'] - 1 ?></option>
                <option vlaue="<?= $dotaznik['rok'] + 1 ?>"><?= $dotaznik['rok'] + 1 ?></option>
            </select>

        </div>
        <h2>Otázky</h2>
    </header>
    <div class="clearfix"></div>
    <section>
        <br />
        <div class="container sortable" id="dotaznik">
            <?php $pocet = 0; ?>
            <?php foreach ($otazky as $otazka) : $pocet += 1; ?>
                <?php if($otazka['typ'] == 'kratka' || $otazka['typ'] == 'dlouha') : ?>
                <div id="" class="s1 bg-light jumbotron">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                            <h3>Tvořená odpověď</h3>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 text-right">
                            <i class="delete fa fa-close" onclick="$(this).parent().parent().parent().remove(); pocet--"></i>
                        </div>
                    </div>
                    <input type="text" name="otazka[]" class="form-control" placeholder="Zadejte text otázky" value="<?= $otazka['otazka'] ?>" />
                    <br />
                    <input type="text" value="<?= $otazka['doplneni'] ?>"  name="poznamka[]"  onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />
                    <div class="row">
                        <div class="col-lg-2 col-md-12 col-form-label">
                            <label>Typ odpovědi</label>
                        </div>
                        <div class="col-lg-10 col-md-12">
                            <select name="typ[]" class="form-control text-center">
>                               <option value="<?= $otazka['typ'] ?>"><?= $typy[$otazka['typ']] ?></option>
                                <?php if($otazka['typ'] == 'kratka') : ?>
                                    <option value="dlouha">Dlouhá odpověď</option>
                                <?php else : ?>
                                    <option value="kratka">Krátká odpověď</option>
                                <?php endif; ?>
                            </select>
                            <input type="hidden" class="moznosti" name="moznost[moznost-<?= $pocet; ?>][]" value="NULL" />
                            <input type="hidden" name="lable1[]" value="NULL" />
                            <input type="hidden" name="lable2[]" value="NULL" />
                            <input type="hidden" name="cancel[]" value="NULL" />
                            <input type="hidden" name="id[]" value="<?= $otazka['otazky_id'] ?>" />

                        </div>
                    </div>
                </div>
                <?php elseif($otazka['typ'] == '1' || $otazka['typ'] == '2' || $otazka['typ'] == '3' || $otazka['typ'] == '4') : ?>
                    <div id="" class="s1 bg-light jumbotron">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-12">
                                <h3>Otázka s možností výběru</h3>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 text-right">
                                <i class="delete fa fa-close" onclick="$(this).parent().parent().parent().remove(); pocet--"></i>
                            </div>
                        </div>
                        <input type="text" name="otazka[]" class="form-control" value="<?= $otazka['otazka'] ?>" placeholder="Zadejte text otázky"  />
                        <br />
                        <input type="text" name="poznamka[]" value="<?= $otazka['doplneni'] ?>" onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />
                        <div class="row">
                            <div class="col-lg-2 col-md-12 col-form-label">
                                <label>Maximální počet odpovědí</label>
                            </div>
                            <div class="col-lg-10 col-md-12">
                                <select name="typ[]" class="form-control text-center">
                                    <option value="<?= $otazka['typ'] ?>"><?= $otazka['typ'] ?></option>
                                    <?php for($i=1; $i<=4; $i++) : ?>
                                        <?php if($i!=$otazka['typ']) : ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <h4>Odpovědi</h4>
                            <?php $moznosti = Db::queryAll('SELECT * FROM ' . $tables['moznosti'] . ' WHERE otazky_id = ?', $otazka['otazky_id']); ?>
                            <?php foreach ($moznosti as $moznost) : ?>
                            <div class="row">
                                <?php if(count($moznosti) > 0) : ?>
                                <div class="col-lg-11">
                                    <input type="text" value="<?= $moznost['moznost'] ?>" class="form-control moznosti" placeholder="Odpověď" name="moznost[moznost-<?= $pocet; ?>][]" />
                                </div>
                                <div class="col-lg-1">
                                    <i class="fa fa-close col-lg-1 text-center deleteMoznost" data="moznost[moznost-<?= $pocet; ?>][]" onclick="$(this).parent().parent().remove()"></i>
                                    <i class="fa fa-plus-square add" style="font-size:25px;" data="moznost[moznost-<?= $pocet; ?>][]" onclick="$(this).parent().parent().after(addMoznost($(this)))"></i>
                                </div>
                                    <?php else : ?>
                                        <div class="col-lg-11">
                                            <input type="text" class="form-control moznosti" placeholder="Odpověď" data="moznost[moznost-<?= $pocet; ?>][]" " />
                                        </div>
                                        <div class="col-lg-1">
                                            <i class="fa fa-close col-lg-1 text-center deleteMoznost" data="moznost[moznost-<?= $pocet; ?>][]" onclick="if(abbleToDelete($(this, 5)) )$(this).parent().parent().remove()"></i>
                                            <i class="fa fa-plus-square add" style="font-size:25px;" data="moznost[moznost-<?= $pocet; ?>][]" onclick="$(this).parent().parent().after(addMoznost($(this)))"></i>
                                        </div>


                                <?php endif; ?>

                            </div>
                            <?php endforeach; ?>
                            <div clas="row">
                            <div class="col-lg-11">
                            </div>
                            <div class="col-lg-1">
                                <i class="fa fa-close col-lg-1 text-center deleteMoznost" data="moznost[moznost-<?= $pocet; ?>][]" onclick="if(abbleToDelete($(this, 5)) )$(this).parent().parent().remove()"></i>
                                <i class="fa fa-plus-square add" style="font-size:25px;" data="moznost[moznost-<?= $pocet; ?>][]" onclick="$(this).parent().parent().after(addMoznost($(this)))"></i>
                            </div>
                            </div>
                        <input type="hidden" name="lable1[]" value="NULL" />
                        <input type="hidden" name="lable2[]" value="NULL" />
                        <input type="hidden" name="cancel[]" value="NULL" />
                        <input type="hidden" name="id[]" value="<?= $otazka['otazky_id'] ?>" />

                    </div>
                <?php elseif($otazka['typ'] == 'sada1' || $otazka['typ'] == 'sada2') : ?>
                    <div id="" class="s1 bg-light jumbotron">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-12">
                                <h3>Škála sada</h3>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 text-right">
                                <i class="delete fa fa-close" onclick="$(this).parent().parent().parent().remove(); pocet--"></i>
                            </div>
                        </div>
                        <input type="text" name="otazka[]" value="<?= $otazka['otazka'] ?>" class="form-control" placeholder="Zadejte text otázky" />
                        <br />
                        <input type="text" name="poznamka[]" value="<?= $otazka['doplneni'] ?>" onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <input type="text" name="lable1[]"  class="form-control" value="<?= $otazka['label1'] ?>" placeholder="Štítek pro první číslo" />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <input type="text" name="lable2[]" class="form-control" value="<?= $otazka['label2'] ?>" placeholder="Štítek pro poslední číslo"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-12 col-form-label">
                                <label>Rozsah</label>
                            </div>
                            <div class="col-lg-10 col-md-12">
                                <select name="typ[]" class="form-control text-center">
                                    <option value="<?= $otazka['typ'] ?>"><?= $typy[$otazka['typ']] ?></option>
                                    <?php if($otazka['typ'] == 'sada1') : ?>
                                        <option value="sada2">1-5</option>
                                    <?php else : ?>
                                        <option value="sada1">1-6</option>
                                    <?php endif; ?>
                                </select>
                                <input type="hidden" class="moznosti" name="moznost[moznost-<?= $pocet; ?>][]" value="NULL" />

                            </div>
                        </div>
                        Vzít odpověď zpět:
                        <?php if($otazka['cancel'] != 'NULL') : ?>
                            <input type="checkbox" name="cancel[]" checked="checked" value="Nechci odpovídat" />
                        <?php else :  ?>
                            <input type="checkbox" name="cancel[]" value="Nechci odpovídat" />
                        <?php endif; ?>
                        <input type="hidden" name="id[]" value="<?= $otazka['otazky_id'] ?>" />

                    </div>

                <?php else : ?>
                    <div id="" class="s1 bg-light jumbotron">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-12">
                                <h3>Škála</h3>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 text-right">
                                <i class="delete fa fa-close" onclick="$(this).parent().parent().parent().remove(); pocet--"></i>
                            </div>
                        </div>
                        <input type="text" name="otazka[]" value="<?= $otazka['otazka'] ?>" class="form-control" placeholder="Zadejte text otázky" />
                        <br />
                        <input type="text" name="poznamka[]" value="<?= $otazka['doplneni'] ?>" onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />
                        <div class="row">
                            <div class="col-lg-2 col-md-12 col-form-label">
                                <label>Rozsah</label>
                            </div>
                            <div class="col-lg-10 col-md-12">
                                <select name="typ[]" class="form-control text-center">
                                    <option value="<?= $otazka['typ'] ?>"><?= $otazka['typ'] ?></option>
                                    <?php if($otazka['typ'] == '0-5') : ?>
                                        <option value="1-5">1-5</option>
                                    <?php else : ?>
                                        <option value="1-6">1-6</option>
                                    <?php endif; ?>
                                </select>
                                <input type="hidden" class="moznosti" name="moznost[moznost-<?= $pocet; ?>][]" value="NULL" />

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <input type="text" name="lable1[]" value="<?= $otazka['label1'] ?>" class="form-control" placeholder="Štítek pro první číslo" />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <input type="text" name="lable2[]" value="<?= $otazka['label2'] ?>" class="form-control" placeholder="Štítek pro poslední číslo"/>
                            </div>
                        </div>
                        Vzít odpověď zpět:
                        <?php if($otazka['cancel'] != 'NULL') : ?>
                            <input type="checkbox" name="cancel[]" checked="checked" value="Nechci odpovídat" />
                        <?php else :  ?>
                            <input type="checkbox" name="cancel[]" value="Nechci odpovídat" />
                        <?php endif; ?>
                        <input type="hidden" name="id[]" value="<?= $otazka['otazky_id'] ?>" />

                    </div>

                <?php endif; ?>
            <?php  endforeach; ?>
        </div>
        <section>
            <br />
            <div class="row">
                <div class="col-lg-6">
                    <select class="form-control" name="uestionType" style="margin: auto; width: 80%">
                        <option value="tvorena">Tvořená odpověď</option>
                        <option value="vyber">Výběr odpovědi</option>
                        <option value="skala">Škála</option>
                        <option value="sada">Škála sada</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <input id="add" type="button" class="btn btn-large btn-primary" value="Přidat otázku" />
                </div>
            </div>
            <br />
        </section><br />
        <input type="submit" id="save" value="Uložit dotazník" class="btn btn-danger btn-lg" />
    </section>

</form>
</main>
<footer class="container">
    <p>&copy; Petr Hanzal 2018</p>
</footer>

<template id="short">
    <div id="" class="s1 bg-light jumbotron">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12">
                <h3>Tvořená odpověď</h3>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 text-right">
                <i class="delete fa fa-close" onclick="$(this).parent().parent().parent().remove(); pocet--"></i>
            </div>
        </div>
        <input type="text" name="otazka[]" class="form-control" placeholder="Zadejte text otázky" />
        <br />
        <input type="text" name="poznamka[]"  onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />
        <div class="row">
            <div class="col-lg-2 col-md-12 col-form-label">
                <label>Typ odpovědi</label>
            </div>
            <div class="col-lg-10 col-md-12">
                <select name="typ[]" class="form-control text-center">
                    <option value="kratka">Krátká odpověď</option>
                    <option value="dlouha">Dlouhá odpověď</option>
                </select>
                <input type="hidden" class="moznosti" name="moznost[][]" value="NULL" />
                <input type="hidden" name="lable1[]" value="NULL" />
                <input type="hidden" name="lable2[]" value="NULL" />
                <input type="hidden" name="cancel[]" value="NULL" />
                <input type="hidden" name="id[]" value="NULL" />

            </div>
        </div>

    </div>

</template>

<template id="vyber">
    <div id="" class="s1 bg-light jumbotron">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12">
                <h3>Otázka s možností výběru</h3>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 text-right">
                <i class="delete fa fa-close" onclick="$(this).parent().parent().parent().remove(); pocet--"></i>
            </div>
        </div>
        <input type="text" name="otazka[]" class="form-control" placeholder="Zadejte text otázky"  />
        <br />
        <input type="text" name="poznamka[]" onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />
        <div class="row">
            <div class="col-lg-2 col-md-12 col-form-label">
                <label>Maximální počet odpovědí</label>
            </div>
            <div class="col-lg-10 col-md-12">
                <select name="typ[]" class="form-control text-center">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
        </div>
        <h4>Odpovědi</h4>
        <div class="row">
            <div class="col-lg-11">
                <input type="text" class="form-control moznosti" placeholder="Odpověď" name="moznost[][]" />
            </div>
            <div class="col-lg-1">
                <i class="fa fa-close col-lg-1 text-center deleteMoznost" onclick="if(abbleToDelete($(this)) )$(this).parent().parent().remove()"></i>
                <i class="fa fa-plus-square add" style="font-size:25px;" onclick="$(this).parent().parent().after(addMoznost($(this)))"></i>
            </div>
        </div>
        <input type="hidden" name="lable1[]" value="NULL" />
        <input type="hidden" name="lable2[]" value="NULL" />
        <input type="hidden" name="cancel[]" value="NULL" />
        <input type="hidden" name="id[]" value="NULL" />
    </div>

</template>

<template id="skala">
    <div id="" class="s1 bg-light jumbotron">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12">
                <h3>Škála</h3>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 text-right">
                <i class="delete fa fa-close" onclick="$(this).parent().parent().parent().remove(); pocet--"></i>
            </div>
        </div>
        <input type="text" name="otazka[]" class="form-control" placeholder="Zadejte text otázky" />
        <br />
        <input type="text" name="poznamka[]" onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />
        <div class="row">
            <div class="col-lg-2 col-md-12 col-form-label">
                <label>Rozsah</label>
            </div>
            <div class="col-lg-10 col-md-12">
                <select name="typ[]" class="form-control text-center">
                    <option value="1-6">1 - 6</option>
                    <option value="1-5">1 - 5</option>
                </select>
                <input type="hidden" class="moznosti" name="moznost[][]" value="NULL" />

            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <input type="text" name="lable1[]"  class="form-control" placeholder="Štítek pro první číslo" />
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <input type="text" name="lable2[]" class="form-control" placeholder="Štítek pro poslední číslo"/>
            </div>
        </div>
        Vzít odpověď zpět:
        <input type="checkbox" name="cancel[]" value="Nechci odpovídat" />
        <input type="hidden" name="id[]" value="NULL" />

    </div>

    </div>
</template>

<template id="sada">
    <div id="" class="s1 bg-light jumbotron">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12">
                <h3>Škála sada</h3>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 text-right">
                <i class="delete fa fa-close" onclick="$(this).parent().parent().parent().remove(); pocet--"></i>
            </div>
        </div>
        <input type="text" name="otazka[]" class="form-control" placeholder="Zadejte text otázky" />
        <br />
        <input type="text" name="poznamka[]" onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <input type="text" name="lable1[]"  class="form-control" placeholder="Štítek pro první číslo" />
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <input type="text" name="lable2[]" class="form-control" placeholder="Štítek pro poslední číslo"/>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-12 col-form-label">
                <label>Rozsah</label>
            </div>
            <div class="col-lg-10 col-md-12">
                <select name="typ[]" class="form-control text-center">
                    <option value="sada1">1 - 6</option>
                    <option value="sada2">1 - 5</option>
                </select>
                <input type="hidden" class="moznosti" name="moznost[][]" value="NULL" />

            </div>
        </div>
        Vzít odpověď zpět:
        <input type="checkbox" name="cancel[]" value="Nechci odpovídat" />
        <input type="hidden" name="id[]" value="NULL" />

    </div>

    </div>
</template>


<!-- The Modal -->
<div class="modal fade" id="db">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Výběr dat z databáze soutěže</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="post">

                    <sapn>Rok:</sapn>
                    <select name="rok">
                        <option value="<?= date('Y'); ?>"><?= date('Y') ?></option>
                        <option value="<?= date('Y')- 1; ?>"><?= date('Y') - 1 ?></option>
                        <option value="<?= date('Y') - 2; ?>"><?= date('Y') - 2 ?></option>
                    </select>
                    <?php if(count($kategorie) > 0) : ?>

                        <span>Kategorie</span>
                        <select name="kategorie2">
                            <option value="<?= $dotaznik['kategorie'] ?>"><?= $dotaznik['nazev'] ?></option>
                            <?php foreach ($kategorie as $k) : ?>
                                <option value="<?= $k['id'] ?>"><?= $k['nazev'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                    <input type="button" id="vloz-otazky" value="Zobrazit otázky" />
                </form>
                <ul id="otazky-db">

                </ul>
                <p>Vybraná data <span id="db-data">dd</span></p>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modal" data-dismiss="modal">Vložit data</button>
            </div>

        </div>

</body>
</html>
