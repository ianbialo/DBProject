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

    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
    public function postFomulaire(){
        //Continuer ici
    }
}