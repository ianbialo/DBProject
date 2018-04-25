<?php
namespace dbproject\controleur;

use dbproject\vue\VueBackOffice;
use dbproject\modele\Projet;
use dbproject\conf\Formulaire;
use dbproject\modele\Structure;

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
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        if (null !== Projet::getById($no))
            print $vue->render(VueBackOffice::AFF_PROJET, $no);
        else
            $app->notFound();
    }
    
    public function affichageRecherche($recherche){
        $vue = new VueBackOffice();
        print $vue->render(VueBackOffice::AFF_RECHERCHE,$recherche);
    }

    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
    public function postRedirectionProjet()
    {
        $app = \Slim\Slim::getInstance();
        $nom = filter_var($_POST['autocompleteRecherche'],FILTER_SANITIZE_STRING);
        $struct = Structure::getByName($nom);
       /** foreach ($struct as $s) {
            echo $s . '<br>';
        }*/
        if (sizeof($struct) == 1) {
            $idStruct = $struct[0]->IdStruct;
            $projet = Projet::getById($idStruct);
            $app->redirect($app->urlFor("projet", [
                'no' => $projet->IdProjet
            ]));
        } else {
            $app->redirect($app->urlFor("recherche",['recherche' => $nom]));
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