<?php
namespace dbproject\controleur;

use dbproject\vue\VueFrontOffice;
use dbproject\conf\Email;
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
        // if ($form::$insertionOk) {
        // Formulaire::switchFormulaireOk();
        $vue = new VueFrontOffice();
        print $vue->render(VueFrontOffice::AFF_OK);
    /**
     * } else {
     * $app = \Slim\Slim::getInstance();
     * return $app->notFound();
     * }
     */
    }

    public function formulaireEchec()
    {
        $vue = new VueFrontOffice();
        print $vue->render(VueFrontOffice::AFF_ECHEC);
    }

    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
    public function postFomulaire()
    {
        $app = \Slim\Slim::getInstance();
        
        // Ajout de la BDD
        if (! Formulaire::insertionFormulaire())
            $app->redirect($app->urlFor("formulaireEchec"));
        
        $nom = filter_var($_POST['nomstruct'],FILTER_SANITIZE_STRING);
        $date = Formulaire::transformerDate(date("Y-m-d"));
        
        // Envoi de mail
        $subject = "Nouveau dépôt de projet";
        $msg = "Une nouvelle demande de partenariat/sponsoring/mécénat a été effectué le ".$date." par ".$nom;
        Email::sendMail($subject, $msg);
        $app->redirect($app->urlFor("formulaireOk"));
    }
}