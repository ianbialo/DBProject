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
    'cookies.secret_key' => 'dBProjecT',
    'cookies.cipher' => MCRYPT_RIJNDAEL_256,
    'cookies.cipher_mode' => MCRYPT_MODE_CBC
));

//require 'hooks.php';
require 'routes.php';

//Lancement de Slim
$app->run();