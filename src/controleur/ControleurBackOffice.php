<?php
namespace dbproject\controleur;

use dbproject\vue\VueBackOffice;

class ControleurBackOffice
{

    // /////////////////////////////////////
    // / GET ///
    // /////////////////////////////////////
    public function index()
    {
        
    }

    public function affichageFormulaires()
    {
        $vue = new VueBackOffice();
        print $vue->render(VueBackOffice::AFF_FORMULAIRE);
    }
    
    public function affichageProjet($no)
    {
        $vue = new VueBackOffice();
        print $vue->render(VueBackOffice::AFF_PROJET,$no);
    }
    
    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
}