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
    (new dbproject\controleur\ControleurFrontOffice())->index();
})->name("accueil");

$app->get('/admin(/)', function (){
    //(new dbproject\controleur\ControleurFrontOffice())->index();
})->name("admin");



///////////////////////////////////////
///               POST              ///
///////////////////////////////////////

$app->post('/form(/)',function(){
    (new dbproject\controleur\ControleurFrontOffice())->postFomulaire();
})->name("postFormulaire");



$app->notFound(function(){
    (new dbproject\controleur\ControleurError404())->affichageErreur();
});

//Lancement de Slim
$app->run();