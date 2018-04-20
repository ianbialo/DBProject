<?php
namespace dbproject\controleur;

use dbproject\vue\VueBackOffice;
use dbproject\modele\Projet;
use dbproject\conf\Formulaire;

class ControleurBackOffice
{

    // /////////////////////////////////////
    // / GET ///
    // /////////////////////////////////////
    public function index()
    {}

    public function affichageFormulaires()
    {
        $vue = new VueBackOffice();
        print $vue->render(VueBackOffice::AFF_FORMULAIRE);
    }

    public function affichageProjet($no)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        if (null !== Projet::getById($no))
            print $vue->render(VueBackOffice::AFF_PROJET, $no);
        else
            $app->notFound();
    }

    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
    public function suppressionFomulaire()
    {
        //echo $_POST['IdProjet'];
        $app = \Slim\Slim::getInstance();
        Formulaire::supprimerFormulaire($_POST['IdProjet']);
        $app->redirect($app->urlFor("listeFormulaires"));
    }
}