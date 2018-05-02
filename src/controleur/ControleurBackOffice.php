<?php
namespace dbproject\controleur;

use dbproject\vue\VueBackOffice;
use dbproject\modele\Projet;
use dbproject\conf\Formulaire;
use dbproject\modele\Structure;
use dbproject\conf\Authentication;

class ControleurBackOffice
{

    // /////////////////////////////////////
    // / GET ///
    // /////////////////////////////////////
    public function index()
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        if (! isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_INDEX);
        else
            $app->redirect($app->urlFor("listeFormulaires"));
    }

    public function deconnexion()
    {
        $app = \Slim\Slim::getInstance();
        if (isset($_COOKIE['user'])) {
            Authentication::disconnect();
            $app->redirect($app->urlFor("connexionAdmin"));
        } else
            $app->notFound();
    }

    public function affichageFormulaires()
    {
        // Envoi de mail
        /**
         * $subject = "Nouveau dépôt de projet";
         * $msg = "Slt";
         * Email::sendMail($subject, $msg);
         */
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        if (isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_FORMULAIRE);
        else
            $app->notFound();
    }

    public function affichageProjet($no)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        if (null !== Projet::getById($no) && isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_PROJET, $no);
        else
            $app->notFound();
    }

    public function affichageRecherche($recherche)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        if (isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_RECHERCHE, $recherche);
        else
            $app->notFound();
    }

    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
    public function postConnexion()
    {
        $id = filter_var($_POST['loginCo'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['mdpCo'], FILTER_SANITIZE_STRING);
        Authentication::authenticate($id, $password);
    }

    public function postRedirectionProjet()
    {
        $app = \Slim\Slim::getInstance();
        $nom = filter_var($_POST['autocompleteRecherche'], FILTER_SANITIZE_STRING);
        $struct = Structure::getByName($nom);
        /**
         * foreach ($struct as $s) {
         * echo $s .
         * '<br>';
         * }
         */
        if (sizeof($struct) == 1) {
            $idStruct = $struct[0]->IdStruct;
            $projet = Projet::getById($idStruct);
            $app->redirect($app->urlFor("projet", [
                'no' => $projet->IdProjet
            ]));
        } else {
            $app->redirect($app->urlFor("recherche", [
                'recherche' => $nom
            ]));
        }
    }

    public function postSuppressionFomulaire()
    {
        // echo $_POST['IdProjet'];
        $app = \Slim\Slim::getInstance();
        Formulaire::supprimerFormulaire($_POST['IdProjet']);
        $app->redirect($app->urlFor("listeFormulaires"));
    }
}