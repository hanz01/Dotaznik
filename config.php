<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 29.7.2018
 * Time: 10:58
 */
$db = array(
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'db' => 'dotaznik'
);

$tables = array(
    'dotaznik' => 'dotaznik',
    'otazky' => 'otazky',
    'moznosti' => 'moznosti',
    'odpovedi' => 'odpovedi',
    'users' => 'users'
);

$bebras = array(
    'otazky' => 'soutezni_otazky',
    'kategorie' => 'kategorie',
    'soutezici' => ' ibobr_2015_soutezici'

);

$typy = array(
      'kratka' => 'Krátká odpověď',
      'dlouha' => 'Dlouhá odpověď',
      'sada1' => '1-6',
      'sada2' => '1-5'
);

$user = array(
  'login' => 'admin',
  'pass' => 'admin'
);

class config
{
    const otazky = 'soutezni_otazky';
}




