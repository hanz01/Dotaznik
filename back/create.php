<?php
if($_POST) {
    $otazky = array();
   foreach($_POST as $p) {
        if(is_array($p)) {
            $pocet = count($_POST['otazka']);

            print_r($p);
        }
    }
}
?>
<!DOCTYPE HTML>
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


    <script type="text/javascript">
       // pole kde index je id otázky a hodbota počet možností
       var moznosti = new Array();
        /*
        funkce přidá novou odpověď
         */
        function addMoznost(data) {
            nameAtr = data.attr('data');
            index = nameAtr.split("[")[1].split("]")[0];
            if(moznosti[index] != undefined) {
                moznosti[index] += 1;
            } else {
                moznosti[index] = 2;
            }
            str = '<div class="row">' +
                '<div class="col-lg-11">' +
                '     <input type="text" class="form-control" name="'+nameAtr+'" placeholder="Odpověď" />' +
                '                </div>' +
                '                <div class="col-lg-1">' +
                '                <i class="fa fa-close col-lg-1 text-center"  data="'+nameAtr+'" onclick="if(abbleToDelete($(this)) )$(this).parent().parent().remove()"></i>' +
                '                <i class="fa fa-plus-square" data="'+nameAtr+'" style="font-size:25px;" onclick="$(this).parent().parent().after(addMoznost($(this)))"></i>' +
                '                </div></div>';
                        return str;
        }

       /**
        * metoda ktera rozhodne jestli se může odstranit možnost
        * @param data instance tlačítka
        * @returns {boolean} true když může odstranit
        */
        function abbleToDelete(data) {
            nameAtr = data.attr('data');
            index = nameAtr.split("[")[1].split("]")[0];
            if(moznosti[index] > 2) {
                moznosti[index] -= 1;
                return true;
            }
            return false;
        }

        var pocet = 0;
       $('input').on('blur', function(){
           alert("Vsvc ");
       })
        $(document).ready(function(){
            $(".sortable").sortable({
                items: ".s1"
            });

            $("#add").click(function(){
                clon = '';
                value = $('select[name="uestionType"]').val();
                switch(value) {
                    case 'tvorena' :
                        temp = document.getElementsByTagName("template")[0];
                        clon = temp.content.cloneNode(true);
                        break;
                    case 'vyber' :
                        temp = document.getElementsByTagName("template")[1];
                        clon = temp.content.cloneNode(true);
                        break;
                    case 'skala' :
                        temp = document.getElementsByTagName("template")[2];
                        clon = temp.content.cloneNode(true);
                        break;
                }
                pocet += 1;
                elem = clon.querySelector("div");
                remove = clon.querySelector(".delete");
                moznostiName = clon.querySelector(".moznosti");
                moznostiName.name = 'moznost[moznost-' + pocet + '][]';

                if(value == 'vyber') {
                    add = clon.querySelector(".add");
                    add.setAttribute('data', 'moznost[moznost-' + pocet + '][]');
                    add = clon.querySelector(".deleteMoznost");
                    add.setAttribute('data', 'moznost[moznost-' + pocet + '][]');
                }
                elem.id = 'q-' + pocet;
                remove.id = 'delete-' + pocet;
                $("#dotaznik").append(clon);
            });
            function validateForm(form) {
                valid = true;
                for (i=0; i<form.length; i++)
                    if(form[i].value == "" && form[i].name != "poznamka[]") {
                        form[i].style.border = '1px solid red';
                        valid = false;
                    }
                    else {
                        form[i].style.border = '1px solid green';
                    }
                return valid;
            }
            //kliknutí na uložení dotazníku
            $("#save").click(function(){
                var nodes = document.querySelectorAll("input[type=text], input[type=hidden], select");
                if(validateForm(nodes) == false) {
                    alert("Formulář nebyl vyplněn korektně");
                    return false;

                }




            });
        });




    </script>
</head>
<body  class="bg-primary">
<form method="post">
    <header class="bg-danger color-white">
        <h1>Nový dotazník</h1>

    tady budou základní údaje
        <h2>Otázky</h2>
    </header>
    <div class="clearfix"></div>
    <main class="bg-primary">
        <br />
        <div class="container sortable" id="dotaznik"></div>
        <nav class="container bg-danger">
            <br />
            <div class="row">
                <div class="col-lg-6 text-center">
                    <select class="form-control" name="uestionType">
                        <option value="tvorena">Tvořená odpověď</option>
                        <option value="vyber">Výběr odpovědi</option>
                        <option value="skala">Škála</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <input id="add" type="button" class="btn btn-large btn-primary" value="Přidat otázku" />
                </div>
            </div>
            <br />
        </nav><br />
        <input type="submit" id="save" value="Uložit dotazník" class="btn btn-danger btn-lg" />
    </main>

</form>

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
                <input type="hidden" class="moznosti" name="" value="hidden" />
                <input type="hidden" name="lable1[]" value="hidden" />
                <input type="hidden" name="lable2[]" value="hidden" />
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
                <label>Počet možností</label>
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

    </div>
    <input type="hidden" name="lable1[]" value="hidden" />
    <input type="hidden" name="lable2[]" value="hidden" />
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
                    <option value="0-5">0 - 5</option>
                    <option value="1-5">1 - 5</option>
                </select>
                <input type="hidden" class="moznosti" name="" value="hidden" />

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
    </div>
</template>


</body>
</html>


