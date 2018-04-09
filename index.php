<?php
session_start();
require_once "vendor/autoload.php";

dbproject\conf\ConnexionBase::initialisation('src/conf/conf.ini');

//Initialisation de Slim
$app = new \Slim\Slim(array(
    'cookies.encrypt' => true,
    'cookies.secret_key' => 'dBPorjecT',
    'cookies.cipher' => MCRYPT_RIJNDAEL_256,
    'cookies.cipher_mode' => MCRYPT_MODE_CBC
));

///////////////////////////////////////
///               GET               ///
///////////////////////////////////////

$app->get('/', function (){
    (new dbproject\controleur\ControleurConnexion())->index();
})->name("accueil");

$app->get('/inscription(/)', function (){
    (new dbproject\controleur\ControleurConnexion())->inscritpion();
})->name("inscription");



///////////////////////////////////////
///               POST              ///
///////////////////////////////////////

$app->post('/postConnexion(/)', function (){
    (new dbproject\controleur\ControleurConnexion())->postConnexion();
})->name("postConnexion");

$app->post('/postInscription(/)', function (){
    (new dbproject\controleur\ControleurConnexion())->postInscription();
})->name("postInscription");

//Lancement de Slim
$app->run();