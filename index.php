<?php
//Lancement de la session
session_start();

//Récupération de l'autoloader
require_once "vendor/autoload.php";

//Initialistion de la connexion à la base de données
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
    (new dbproject\controleur\ControleurConnexion())->inscription();
})->name("inscription");

$app->get('/recuperation(/)', function (){
    (new dbproject\controleur\ControleurConnexion())->recuperation();
})->name("recuperation");

$app->get('/modification(/)', function (){
    (new dbproject\controleur\ControleurConnexion())->modification();
})->name("modification");

$app->get('/postDeconnexion(/)', function (){
    (new dbproject\controleur\ControleurConnexion())->postDeconnexion();
})->name("postDeconnexion");



///////////////////////////////////////
///               POST              ///
///////////////////////////////////////

$app->post('/postConnexion(/)', function (){
    (new dbproject\controleur\ControleurConnexion())->postConnexion();
})->name("postConnexion");

$app->post('/inscription(/)', function (){
    (new dbproject\controleur\ControleurConnexion())->postInscription();
})->name("postInscription");

$app->post('/recuperation(/)', function (){
    (new dbproject\controleur\ControleurConnexion())->postRecuperation();
})->name("postRecuperation");

$app->post('/modification(/)', function (){
    (new dbproject\controleur\ControleurConnexion())->postModification();
})->name("postModification");

//Lancement de Slim
$app->run();