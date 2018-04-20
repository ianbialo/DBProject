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
        
        $IdProjet = $proj->IdProjet;
        
        //Ajout des personnes impliquées (co-financeurs et parrains)
        //Co-financeurs
        $x = $_POST['nbCo'];
        $y = 0;
        while($x>0){
            if(isset($_POST['nomco'.$y])){
                $imp = new Implique();
                $imp->IdProjet = $IdProjet;
                $imp->Nom = $_POST['nomco'.$y];
                $imp->Prenom = $_POST['prenomco'.$y];
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
            $imp->Nom = $_POST['nomparrain'];
            $imp->Prenom = $_POST['prenomparrain'];
            $imp->Role = 1;
            $imp->save();
        }
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
