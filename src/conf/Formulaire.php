<?php
namespace dbproject\conf;

use dbproject\modele\Structure;
use dbproject\modele\Representant;
use dbproject\modele\Responsable;
use dbproject\modele\Projet;
use dbproject\modele\Implique;

class Formulaire
{
    public static $insertionOk = false;

    public static function insertionFormulaire()
    {
        //Ajout de la structure
        $struct = new Structure();
        $struct->Nom = filter_var($_POST['nomstruct'],FILTER_SANITIZE_STRING);
        $struct->Adresse = filter_var($_POST['adrstruct'],FILTER_SANITIZE_STRING);
        $struct->CodePostal = filter_var($_POST['cpostalstruct'],FILTER_SANITIZE_STRING);
        $struct->Ville = filter_var($_POST['villestruct'],FILTER_SANITIZE_STRING);
        $struct->Raison = filter_var($_POST['raisonstruct'],FILTER_SANITIZE_STRING);
        if($_POST['vousetes'] != "0") $struct->Type = $_POST['vousetes'];
        else $struct->Type = filter_var($_POST['autre'],FILTER_SANITIZE_STRING);
        $struct->Site = filter_var($_POST['site'],FILTER_SANITIZE_URL);
        $struct->save();
        
        //Ajout du représentant
        $rep = new Representant();
        $rep->Nom = filter_var($_POST['nomrzplegal'],FILTER_SANITIZE_STRING);
        $rep->Prenom = filter_var($_POST['prenomrzplegal'],FILTER_SANITIZE_STRING);
        $rep->Qualite = filter_var($_POST['qualite'],FILTER_SANITIZE_STRING);
        $rep->save();
        
        //Ajout du responsable
        $res = new Responsable();
        $res->Nom = filter_var($_POST['nomresplegal'],FILTER_SANITIZE_STRING);
        $res->Prenom = filter_var($_POST['prenomresplegal'],FILTER_SANITIZE_STRING);
        $res->Position = filter_var($_POST['position'],FILTER_SANITIZE_STRING);
        $res->Adresse = filter_var($_POST['adrport'],FILTER_SANITIZE_STRING);
        $res->CodePostal = filter_var($_POST['cpostalport'],FILTER_SANITIZE_STRING);
        $res->Ville = filter_var($_POST['villeport'],FILTER_SANITIZE_STRING);
        $res->Tel = filter_var($_POST['tel'],FILTER_SANITIZE_STRING);
        $res->courriel = filter_var($_POST['courriel'],FILTER_SANITIZE_EMAIL);
        $res->save();
        
        $IdStruct = $struct->IdStruct;
        $IdRes = $res->IdRes;
        $IdRep = $rep->IdRep;
        
        //Ajout du projet
        $proj = new Projet();
        $proj->IdStruct = $IdStruct;
        $proj->IdRes = $IdRes;
        $proj->IdRep = $IdRep;
        $proj->DateDep = date("Y-m-d");
        $proj->Expose = filter_var($_POST['expose'],FILTER_SANITIZE_EMAIL);
        $proj->DateDeb = $_POST['datedeb'];
        $proj->Duree = filter_var($_POST['duree'],FILTER_SANITIZE_NUMBER_INT);
        $proj->Lieu = filter_var($_POST['lieu'],FILTER_SANITIZE_STRING);
        $proj->Aide = filter_var($_POST['aide'],FILTER_SANITIZE_NUMBER_INT);
        $proj->Budget = filter_var($_POST['budget'],FILTER_SANITIZE_NUMBER_INT);
        $proj->Fin = filter_var($_POST['findb'],FILTER_SANITIZE_STRING);
        $proj->InteretGeneral = filter_var($_POST['group0'],FILTER_SANITIZE_NUMBER_INT);
        $proj->Domaine = filter_var($_POST['domaine'],FILTER_SANITIZE_STRING);
        $proj->Mecenat = filter_var($_POST['group2'],FILTER_SANITIZE_NUMBER_INT);
        $proj->Fiscal = filter_var($_POST['group3'],FILTER_SANITIZE_NUMBER_INT);
        if(strlen($_POST['valorev']) != 0)$proj->Valorisation = filter_var($_POST['valorev'],FILTER_SANITIZE_STRING);
        else $proj->Valorisation = null;
        $proj->Document = $_POST['nbFile'];
        $proj->save();
        
        $IdProjet = $proj->IdProjet;
        
        //Ajout des personnes impliquées (co-financeurs et parrains)
        //Co-financeurs
        $x = $_POST['nbCo'];
        $y = 0;
        while($x>0){
            if(isset($_POST['nomco'.$y])){
                $imp = new Implique();
                $imp->IdProjet = $IdProjet;
                $imp->Nom = filter_var($_POST['nomco'.$y],FILTER_SANITIZE_STRING);
                $imp->Prenom = filter_var($_POST['prenomco'.$y],FILTER_SANITIZE_STRING);
                $imp->Role = 0;
                $imp->save();
                $x--;
            }
            $y++;
        }
        //Parrains
        if($_POST['group1'] == 1){
            $imp = new Implique();
            $imp->IdProjet = $IdProjet;
            $imp->Nom = filter_var($_POST['nomparrain'],FILTER_SANITIZE_STRING);
            $imp->Prenom = filter_var($_POST['prenomparrain'],FILTER_SANITIZE_STRING);
            $imp->Role = 1;
            $imp->save();
        }
        
        //Ajout des fichiers
        Uploads::ajoutFichierFormulaire($IdProjet);
        
         //self::switchFormulaireOk();
    }
    
    public static function supprimerFormulaire($id){
        $implique = Implique::getImplique($id);
        foreach($implique as $i)$i->delete();
        $projet = Projet::getById($id);
        $structure = Structure::getById($projet->IdStruct);        
        $rep = Representant::getById($projet->IdRep);        
        $res = Responsable::getById($projet->IdRes);
        $projet->delete();
        $structure->delete();
        $rep->delete();
        $res->delete();  
    }
    
    public static function switchFormulaireOk(){
        if(self::$insertionOk) self::$insertionOk = false;
        else self::$insertionOk = true;
    }
    
    public static function transformerDate($date){
        $date = explode("-",$date);
        $monthArray = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
        $jour = $date[2];
        $mois = $monthArray[$date[1]-1];
        $annee = $date[0];
        return $jour." ".$mois." ".$annee;       
    }
    
    public static function transformerBooleen($int){
        if($int == 0) return "Non";
        if($int == 1) return "Oui";
        return null;
    }
}
