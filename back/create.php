<?php
session_start();
include("../config.php");
include("../classes/Db.php");
Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
if(empty($_SESSION['user'])) {
    header('location: login.php');
    exit();
}
$kategorie = Db::queryAll('SELECT id, nazev FROM ' . $bebras['kategorie']);
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
            'rok' => $_POST['rok'],
            'podminky' => $_POST['podminky']
        );
        $insert1 = Db::insert($tables['dotaznik'], $form);
        $dotaznik = Db::getLastId($tables['dotaznik']);
        $pocet = count($_POST['otazka']);
        for ($i = 0; $i < $pocet; $i++) {
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
            if (!isset($_POST['povinne'][$i]))
                $povinne = 0;
            else
                $povinne = $_POST['povinne'][$i];
            $otazka = array(
                    'dotaznik_id' => $dotaznik,
                    'otazka' => $otazka,
                    'doplneni' => $doplneni,
                    'typ' => $typ,
                    'label1' => $label1,
                    'label2' => $label2,
                    'cancel' => $cancel,
                    'povinne' => $povinne
            );
            Db::insert($tables['otazky'], $otazka);

            $a = $i+1; //možnost index
            $moznosti = $_POST['moznost']['moznost-' . $a];


            if(is_array($moznosti) and $moznosti[0] != 'NULL') {
                $id = Db::getLastId($tables['otazky']);
                foreach ($moznosti as $m) {
                    $moznost = array(
                            'moznost' => $m,
                            'otazky_id' => $id
                    );
                    Db::insert($tables['moznosti'], $moznost);
                }
            }
        }
        header('location: admin.php?vytvoreno=ok');
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
<p id="pocet" style="display: none">0</p>
<form method="post">
    <header class="header-editor">
        <h2>Nový dotazník</h2>
                <input type="text" name="nazev" class="form-control" placeholder="Název formuláře" /><br />
                <textarea name="informace" class="form-control" placeholder="Informace pro doplnění" ></textarea><br />
                <textarea name="podminky" class="form-control" placeholder="Podmínky výzkumu" ></textarea><br />

            <?php if(count($kategorie) > 0) : ?>
                       <select name="kategorie" class="form-control">
                           <option disabled="disabled" selected >Soutěžní kategorie</option>
                        <?php foreach ($kategorie as $k) : ?>
                            <option value="<?= $k['id'] ?>"><?= $k['nazev'] ?></option>
                        <?php endforeach; ?>
                    </select>

            <?php endif; ?>
             <br />
             <select name="rok" class="form-control">
                  <option disabled="disabled" selected="selected">Ročník soutěže</option>
                  <option vlaue="<?= date('Y') ?>"><?= date('Y') ?></option>
                  <option vlaue="<?= date('Y') + 1 ?>"><?= date('Y') + 1 ?></option>
             </select>
            <div class="container text-center">
                <input type="button" class="zobraz-otazky btn btn-danger btn-md" value="Přidat otázky" />
            </div>
    </header>
    <div class="clearfix"></div>
        <br />
        <div class="hide" style="display: none">
            <h2 class="text-center">Otázky</h2>

            <div class="container sortable" id="dotaznik"></div>
                <div class="pridat-otazku">
                        <h4 class="text-center">Přidat otázku</h4>
                        <select class="form-control" name="uestionType">
                            <option value="tvorena">Tvořená odpověď</option>
                            <option value="vyber">Výběr odpovědi</option>
                            <option value="skala">Škála</option>
                            <option value="sada">Škála sada</option>
                        </select>
                        <input id="add" type="button" class="btn btn-large btn-danger" value="Přidat otázku" />
                </div>
            </div>
            <div class="container text-center">
                <input type="submit" id="save" value="Uložit dotazník" class="btn btn-danger btn-lg" />
            </div>

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
        <p>Tip: pokud chcete vložit data z databáze soutěžních úloh, klikněte dvakrát do jakéhokoliv textového pole.</p>

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

            </div>
        </div>
        Povinná odpověď: <input type="checkbox" name="povinne[]" checked="checked" value="1" />

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
        <p>Tip: pokud chcete vložit data z databáze soutěžních úloh, klikněte dvakrát do jakéhokoliv textového pole.</p>
        <input type="text" name="otazka[]" class="form-control" placeholder="Zadejte text otázky"  />
        <br />
        <input type="text" name="poznamka[]" onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />
        <div class="row">
            <div class="col-lg-4 col-md-12 col-form-label">
                <label>Maximální počet odpovědí</label>
            </div>
            <div class="col-lg-8 col-md-12">
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
                <div class="col-lg-10">
                    <input type="text" class="form-control moznosti" placeholder="Odpověď" name="moznost[][]" /><br />
                </div>
                <div class="col-lg-2">
                    <i class="fa fa-close col-lg-1 text-center deleteMoznost" onclick="if(abbleToDelete($(this)) )$(this).parent().parent().remove()"></i>
                    <i class="fa fa-plus-square add" style="font-size:25px;" onclick="$(this).parent().parent().after(addMoznost($(this)))"></i>
                </div>
            </div>

    </div>
    <input type="hidden" name="lable1[]" value="NULL" />
    <input type="hidden" name="lable2[]" value="NULL" />
    <input type="hidden" name="cancel[]" value="NULL" />
    <input type="hidden" name="povinne[]" value="1" />

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
        <p>Tip: pokud chcete vložit data z databáze soutěžních úloh, klikněte dvakrát do jakéhokoliv textového pole.</p>
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

            Přidat možnost nechci odpovídat:
            <input type="checkbox" name="cancel[]" value="Nechci odpovídat" />
        <input type="hidden" name="povinne[]" value="1" />


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
        <p>Otázka vygeneruje automaticky sadu škálových otázek se všemi soutěžními otázkami ze zvolené kategorie.</p>
        <p>Tip: pokud chcete vložit data z databáze soutěžních úloh, klikněte dvakrát do jakéhokoliv textového pole.</p>

        <input type="text" name="otazka[]" class="form-control" placeholder="Zadejte text otázky" />
        <br />
        <input type="text" name="poznamka[]" onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <input type="text" name="lable1[]"  class="form-control" placeholder="Štítek pro první číslo" />
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <input type="text" name="lable2[]" class="form-control" placeholder="Štítek pro poslední číslo"/><br />
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
        Přidat možnost nechci odpovídat:
        <input type="checkbox" name="cancel[]" value="Nechci odpovídat" />
        <input type="hidden" name="povinne[]" value="1" />



    </div>

    </div>
</template>

<template id="vyber-auto">
    <div id="" class="s1 bg-light jumbotron">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12">
                <h3>Otázka s možností výběru</h3>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 text-right">
                <i class="delete fa fa-close" onclick="$(this).parent().parent().parent().remove(); pocet--"></i>
            </div>
        </div>
        <p>Tip: pokud chcete vložit data z databáze soutěžních úloh, klikněte dvakrát do jakéhokoliv textového pole.</p>

        <p>Do odpovědí se automaticky načtou názvy soutěžních otázek z dané kategorie.</p>
        <input type="text" name="otazka[]" class="form-control" placeholder="Zadejte text otázky"  />
        <br />
        <input type="text" name="poznamka[]" onblur="if($(this).val() == '') $(this).val(' ')" class="form-control" placeholder="Zadejte doplňující text" /><br />
        <div class="row">
            <div class="col-lg-4 col-md-12 col-form-label">
                <label>Maximální počet odpovědí</label>
            </div>
            <div class="col-lg-8 col-md-12">
                <select name="lable2[]" class="form-control text-center">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="lable1[]" value="NULL" />
        <input type="hidden" name="typ[]" value="vyber-auto" />
        <input type="hidden" name="cancel[]" value="NULL" />
        <input type="hidden" name="id[]" value="NULL" />
        <input type="hidden" name="povinne[]" checked="checked" value="1" />
        <input type="hidden" class="moznosti" name="moznost[][]" value="NULL" />

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
            <p id="pois-modal"></p>
            <!-- Modal body -->
            <div class="modal-body">
                <form method="post">

                    <!--
                    <sapn>Rok:</sapn>
                    <select name="rok2">
                        <option value="<?= date('Y'); ?>"><?= date('Y') ?></option>
                        <option value="<?= date('Y')- 1; ?>"><?= date('Y') - 1 ?></option>
                        <option value="<?= date('Y') - 2; ?>"><?= date('Y') - 2 ?></option>
                    </select>
                    <?php if(count($kategorie) > 0) : ?>
                    <span>Kategorie</span>
                    <select name="kategorie2">
                        <?php foreach ($kategorie as $k) : ?>
                        <option value="<?= $k['id'] ?>"><?= $k['nazev'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>

                    <input type="button" id="vloz-otazky" value="Zobrazit otázky" />
                    !-->
                </form>
                <ol id="otazky-db">

                </ol>
                <p>Vybraná data <span id="db-data"></span></p>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modal" data-dismiss="modal">Vložit data</button>
            </div>

        </div>

</body>
</html>


