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

$app->get('/enregistrement(/)', function (){
    (new dbproject\controleur\ControleurFrontOffice())->formulaireOk();
})->name("formulaireOk");

$app->get('/admin/formulaire(/)', function (){
    (new dbproject\controleur\ControleurBackOffice())->affichageFormulaires();
})->name("listeFormulaires");

$app->get('/admin/formulaire/:no', function ($no){
    (new dbproject\controleur\ControleurBackOffice())->affichageProjet($no);
})->name("projet");

$app->get('/admin/formulaire/recherche/:recherche', function ($recherche){
    (new dbproject\controleur\ControleurBackOffice())->affichageRecherche($recherche);
})->name("recherche");



///////////////////////////////////////
///               POST              ///
///////////////////////////////////////

$app->post('/postForm(/)',function(){
    (new dbproject\controleur\ControleurFrontOffice())->postFomulaire();
})->name("postFormulaire");

$app->post('/postSupprForm(/)',function(){
    (new dbproject\controleur\ControleurBackOffice())->postSuppressionFomulaire();
})->name("postSuppressionFormulaire");

$app->post('/postRedirection(/)',function(){
    (new dbproject\controleur\ControleurBackOffice())->postRedirectionProjet();
})->name("postRedirection");




///////////////////////////////////////
///             NOT FOUND           ///
///////////////////////////////////////

$app->notFound(function(){
    (new dbproject\controleur\ControleurError404())->affichageErreur();
});

//Lancement de Slim
$app->run();