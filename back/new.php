<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 2.4.2018
 * Time: 9:25
 */
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Your Website</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">    <link rel="stylesheet" href="../css/apps.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript">
        var pocet = 0;
        $(document).ready(function(){
           $("#add").click(function(){
               pocet++;
                var type = $("select").val();
                switch(type) {
                    case "kratka" :
                        $(".otazky").append(addText(pocet, 'short'));
                        break;
                    case "dlouha" :
                        $(".otazky").append(addText(pocet, 'long'));
                        break;
                }
            });

        });


        /**
         * funkce generuje kratkou tvorenou odpoved
         * @param index poradi
         * @param type dlouha / kratka
         * @returns {string|string|*|string|string|string}
         */
        function addText(index, type) {
            str = "<div class=\"container\" id=\"question-"+index+"\">";
            str += "<div class=\"row\">";
            str += "<div class=\"col col-lg-10 text-center\">";
            if(type=='short')
                str += "<h3>Krátká tvořená odpověď</h3>";
            else
                str += "<h3>Dlouhá tvořená odpověď</h3>";
            str += "</div>";
            str += "<div class=\"col col-lg-2 text-right\">";
            str += "<p class=\"delete\" data=\""+index+"\" onclick=\"deleteQuestion("+index+")\" >X</p>";
            str += "</div>";
            str += "</div>";

            str += "<input type=\"text\" name=\"question-"+index+"\" class=\"form-control\" placeholder=\"Text otázky\" />";
            str += "<div class=\"form-check\">";
            str += "<input type=\"checkbox\" name=\"req-"+index+"\" value=\"1\" class=\"form-check-input\" id=\"req-"+index+"\" />";
            str += "<input type=\"hidden\" name=\"type-"+index+"\" value=\""+type+"\" />";
            str += "<label class=\"form-check-label\" for=\"req-"+index+"\">Povinné</label>";
            str += "</div>"
            str += "<hr />";
            str += "</div>"
            return str;
        }

        function deleteQuestion(id) {
            id = 'question-' + id;
            $("#" + id).remove();
            pocet--;
        }

    </script>
</head>
<body>
   <h1>Nový dotazník</h1>
    tady budou základní údaje
    <h2>Otázky</h2>
    <form metho="post">
        <select name="uestionType">
            <optgroup label="Textové otázky">
                <option value="kratka">Krátká odpověď</option>
                <option value="dlouha">Dlouhá odpověď</option>
            </optgroup>
        </select>
        <input id="add" type="button" value="Přidat otázku" />
    </form>
    <div class="otazky">

    </div>
</body>
</html>