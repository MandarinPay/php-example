<?php

spl_autoload_register(function ($class_name) {
    require_once 'Class/' . $class_name . '.php';
});
$heeader = getallheaders();
$testform = new NewPay();
$calc_sigh = $testform->check_sign($_POST);
if ($calc_sigh == true) {
//    $file = fopen('text.txt', 'w+');
//    foreach ($_POST as $k => $v) {
//        fwrite($file, $k . '=>' . $v . "\r \n");
//    }
//    foreach ($heeader as $k => $v) {
//        fwrite($file, $k . '=>' . $v . "\r \n");
//    }
//    fclose($file);
    $new_index = new IndexForm('my_database');
    $new_index->updtade_status($_POST);
}
