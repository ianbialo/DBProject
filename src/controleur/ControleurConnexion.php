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
    
    public function postInscription(){
        $app = \Slim\Slim::getInstance();
        $id = $_POST['loginInscr'];
        $mdp = $_POST['mdpInscr'];
        $orga = $_POST['orgInscr'];
        $nom = $_POST['nomInscr'];
        $prenom = $_POST['prenomInscr'];
        $adr = $_POST['adrInscr'];
        $tel = $_POST['telInscr'];
        Authentication::createUser($id, $mdp, $orga, $nom, $prenom, $adr, $tel);
        $app->redirect($app->urlFor("accueil"));
    }
}