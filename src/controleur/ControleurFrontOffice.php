<?php
namespace dbproject\controleur;

use dbproject\vue\VueFrontOffice;
use dbproject\conf\Formulaire;

class ControleurFrontOffice
{

    // /////////////////////////////////////
    // / GET ///
    // /////////////////////////////////////
    public function index()
    {
            $vue = new VueFrontOffice();
            print $vue->render(VueFrontOffice::AFF_INDEX);
    }

    public function formulaireOk()
    {
        if (Formulaire::getFormulaireOk()) {
            Formulaire::switchFormulaireOk();
            $vue = new VueFrontOffice();
            print $vue->render(VueFrontOffice::AFF_OK);
        } else {
            $app = \Slim\Slim::getInstance();
            return $app->notFound();
        }
    }

    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
    public function postFomulaire()
    {
        // Ajout de la BDD
        Formulaire::insertionFormulaire();
        
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor("formulaireOk"));
    }
}