<?php
namespace dbproject\controleur;

use dbproject\vue\VueConnexion;
use dbproject\conf\Authentication;

require_once "vendor/autoload.php";
require_once "src/vue/VueConnexion.php";
require_once "src/conf/Authentication.php";

class ControleurConnexion{
    
    
    ///////////////////////////////////////
    ///               GET               ///
    ///////////////////////////////////////
    
    public function index(){
        $vue = new VueConnexion();
        print $vue->render(VueConnexion::AFF_CONNEXION);
    }
    
    public function inscritpion(){
        $vue = new VueConnexion();
        print $vue->render(VueConnexion::AFF_INSCRIPTION);
    }
    
    
    ///////////////////////////////////////
    ///               POST              ///
    ///////////////////////////////////////
    
    public function postConnexion(){
        $app = \Slim\Slim::getInstance();
        $id = $_POST['loginCo'];
        $mdp = $_POST['mdpCo'];
        Authentication::authenticateEns($id, $mdp);
        $app->redirect($app->urlFor("accueil"));
    }
}