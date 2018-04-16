<?php
namespace dbproject\controleur;

use dbproject\vue\VueFrontOffice;

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
        $vue = new VueFrontOffice();
        print $vue->render(VueFrontOffice::AFF_OK);
    }

    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
    public function postFomulaire(){
        //Ajout de la BDD
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor("formulaireOk"));
    }
}