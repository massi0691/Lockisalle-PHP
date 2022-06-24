<?php

// try to connect to bdd

try {
    $bdd= new PDO('mysql:host=localhost;dbname=tp_final', 'root', '', [PDO::ATTR_ERRMODE =>PDO::ERRMODE_WARNING] );

} catch (Exception $e) {
    die ('ERROR CATCH :'. $e->getMessage());
}

// function debug 

function debug($var){
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

$date = new DateTime();
$newDate=$date->format('Y-m-d H:i:s');

$errorMessages="";
$successMessages="";

session_start();


 // Gestion de l'URL




 $base_url =substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])); 
 $base_url =str_replace('inc','',$base_url); 
 

// function pour verifier date


function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

