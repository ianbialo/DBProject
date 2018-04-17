<?php
namespace dbproject\controleur;

use dbproject\vue\VueFrontOffice;
use dbproject\modele\Structure;
use dbproject\modele\Representant;
use dbproject\modele\Responsable;
use dbproject\modele\Projet;

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
        
        //Ajout de la structure
        /**$struct = new Structure();
        $struct->Nom = $_POST['nomstruct'];
        $struct->Adresse = $_POST['adrstruct'];
        $struct->CodePostal = $_POST['cpostalstruct'];
        $struct->Ville = $_POST['villestruct'];
        $struct->Raison = $_POST['raisonstruct'];
        if($_POST['vousetes'] != "0") $struct->Type = $_POST['vousetes'];
        else $struct->Type = $_POST['autre'];
        $struct->Site = $_POST['site'];
        $struct->save();
        
        //Ajout du représentant
        $rep = new Representant();
        $rep->Nom = $_POST['nomrzplegal'];
        $rep->Prenom = $_POST['prenomrzplegal'];
        $rep->Qualite = $_POST['qualite'];
        $rep->save();
        
        //Ajout du responsable
        $res = new Responsable();
        $res->Nom = $_POST['nomresplegal'];
        $res->Prenom = $_POST['prenomresplegal'];
        $res->Position = $_POST['position'];
        $res->Adresse = $_POST['adrport'];
        $res->CodePostal = $_POST['cpostalport'];
        $res->Ville = $_POST['villeport'];
        $res->Tel = $_POST['tel'];
        $res->courriel = $_POST['courriel'];
        $res->save();*/
        
        //Ajout du projet
        $proj = new Projet();
        $proj->IdStruct = 7;
        $proj->IdRes = 3;
        $proj->IdRep = 3;
        $proj->DateDep = date("Y-m-d");
        $proj->Expose = $_POST['expose'];
        $proj->DateDeb = $_POST['datedeb'];
        $proj->Duree = $_POST['duree'];
        $proj->Lieu = $_POST['lieu'];
        $proj->Aide = $_POST['aide'];
        $proj->Budget = $_POST['budget'];
        $proj->Fin = $_POST['findb'];
        $proj->InteretGeneral = $_POST['group0'];
        $proj->Domaine = $_POST['domaine'];
        $proj->Mecenat = $_POST['group2'];
        $proj->Fiscal = $_POST['group3'];
        if(strlen($_POST['valorev']) != 0)$proj->Valorisation = $_POST['valorev'];
        else $proj->Valorisation = null;
        $proj->Document = 0;
        $proj->save();
        
        //Ajout des personnes impliquées (co-financeurs et parrains)
        
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor("formulaireOk"));
    }
}