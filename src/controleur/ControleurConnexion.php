<?php
namespace dbproject\controleur;

use dbproject\vue\VueConnexion;
use dbproject\conf\Authentication;
use dbproject\vue\VueMail;
use dbproject\modele\User;

class ControleurConnexion
{

    // /////////////////////////////////////
    // / GET ///
    // /////////////////////////////////////
    public function index()
    {
        $vue = new VueConnexion();
        if (isset($_COOKIE['user']))
            print $vue->render(VueConnexion::AFF_INDEX);
        else
            print $vue->render(VueConnexion::AFF_CONNEXION);
    }

    public function inscription()
    {
        $vue = new VueConnexion();
        print $vue->render(VueConnexion::AFF_INSCRIPTION);
    }

    public function recuperation()
    {
        $vue = new VueConnexion();
        print $vue->render(VueConnexion::AFF_MDPPERDU);
    }

    public function modification()
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueConnexion();
        if (isset($_COOKIE['user'])) {;
            print $vue->render(VueConnexion::AFF_MODIFICATION);
        } else
            $app->redirect($app->urlFor("accueil"));
    }

    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
    public function postConnexion()
    {
        $app = \Slim\Slim::getInstance();
        $id = filter_var($_POST['loginCo'], FILTER_SANITIZE_EMAIL);
        $mdp = filter_var($_POST['mdpCo'], FILTER_SANITIZE_STRING);
        Authentication::authenticateEns($id, $mdp);
        $app->redirect($app->urlFor("accueil"));
    }

    public function postInscription()
    {
        $app = \Slim\Slim::getInstance();
        
        // Envoi de mail
        
        $id = filter_var($_POST['loginInscr'], FILTER_SANITIZE_EMAIL);
        $mdp = filter_var($_POST['mdpInscr'], FILTER_SANITIZE_STRING);
        $mdp2 = filter_var($_POST['mdpInscr2'], FILTER_SANITIZE_STRING);
        $orga = filter_var($_POST['orgInscr'], FILTER_SANITIZE_STRING);
        $nom = filter_var($_POST['nomInscr'], FILTER_SANITIZE_STRING);
        $prenom = filter_var($_POST['prenomInscr'], FILTER_SANITIZE_STRING);
        $adr = filter_var($_POST['adrInscr'], FILTER_SANITIZE_STRING);
        $tel = filter_var($_POST['telInscr'], FILTER_SANITIZE_STRING);
        if (strcmp($mdp, $mdp2) == 0) {
            Authentication::createUser($id, $mdp, $orga, $nom, $prenom, $adr, $tel);
        }
        $vue = new VueMail();
        print $vue->render(VueMail::AFF_INSCR);
    }

    public function postDeconnexion()
    {
        Authentication::disconnect();
        $app->redirect($app->urlFor("accueil"));
    }

    public function postRecuperation()
    {
        // Envoi de mail
        $vue = new VueMail();
        print $vue->render(VueMail::AFF_MDP);
    }

    public function postModification()
    {
        $app = \Slim\Slim::getInstance();
        
        // Envoi de mail
        $id = filter_var($_POST['loginModifhide'], FILTER_SANITIZE_EMAIL);
        $orga = filter_var($_POST['orgModif'], FILTER_SANITIZE_STRING);
        $nom = filter_var($_POST['nomModif'], FILTER_SANITIZE_STRING);
        $prenom = filter_var($_POST['prenomModif'], FILTER_SANITIZE_STRING);
        $adr = filter_var($_POST['adrModif'], FILTER_SANITIZE_STRING);
        $tel = filter_var($_POST['telModif'], FILTER_SANITIZE_STRING);
        if (isset($_POST['mdpModif']) && isset($_POST['mdpModif2'])) {
            $mdp = filter_var($_POST['mdpModif'], FILTER_SANITIZE_STRING);
            $mdp2 = filter_var($_POST['mdpModif2'], FILTER_SANITIZE_STRING);
            if (strcmp($mdp, $mdp2) == 0) {
                Authentication::updateUser($id, $orga, $nom, $prenom, $adr, $tel, $mdp );
            }
        }
        Authentication::updateUser($id, $orga, $nom, $prenom, $adr, $tel);
        $vue = new VueMail();
        print $vue->render(VueMail::AFF_MODIFICATION);
    }
}