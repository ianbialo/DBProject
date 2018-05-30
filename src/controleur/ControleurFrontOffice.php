<?php
namespace dbproject\controleur;

use dbproject\vue\VueFrontOffice;
use dbproject\conf\Email;
use dbproject\conf\Formulaire;

class ControleurFrontOffice
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //Il s'agit des méthodes rédirigées par Slim pour la partie Front Office.                            //
    //Elles sont séparées en deux catégorie : GET et POST suivant le type d'action effectué sur la page //
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    
    // /////////////////////////////////////
    // / GET ///
    // /////////////////////////////////////
    /**
     * Méthode d'accès à l'index du site.
     */
    public function index()
    {
        $vue = new VueFrontOffice();
        print $vue->render(VueFrontOffice::AFF_INDEX);
    }

    /**
     * Méthode d'affichage de la page de réussite
     */
    public function formulaireOk()
    {
        $vue = new VueFrontOffice();
        print $vue->render(VueFrontOffice::AFF_OK);
    }

    /**
     * Méthode d'affichage de la page d'échec
     */
    public function formulaireEchec()
    {
        $vue = new VueFrontOffice();
        print $vue->render(VueFrontOffice::AFF_ECHEC);
    }

    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
    /**
     * Méthode d'ajout de formulaire dans la base de données et envoi de mail pour confirmer l'ajout en cas de réussite, sinon renvoie une page inexistante.
     */
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
        $msg = 'Une nouvelle demande de partenariat/sponsoring/mécénat a été effectué le '.$date.' par "'.$nom.'".';
        Email::sendMail($subject, $msg);
        $app->redirect($app->urlFor("formulaireOk"));
    }
}