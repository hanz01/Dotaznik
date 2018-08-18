<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 18.8.2018
 * Time: 14:44
 */
include("../config.php");
include("../classes/Db.php");
Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
if($_POST) {
    if(isset($_POST['kategorie'])) {
        $ot = Db::queryAll('SELECT nazev FROM ' . $bebras['otazky'] .' WHERE kategorie = ? ', $_POST['kategorie']);
        foreach($ot as $o) {
            echo "<li class=\"data-db\">". $o['nazev'] ."</li>";
        }
    }
}
?>