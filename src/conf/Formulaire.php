<?php
namespace dbproject\conf;

use dbproject\modele\Structure;
use dbproject\modele\Representant;
use dbproject\modele\Responsable;
use dbproject\modele\Projet;
use dbproject\modele\Implique;
use dbproject\modele\Suivi;

class Formulaire
{

    /**
     * Méthode d'insertion d'un nouveau formulaire complet dans la base de données
     * @return boolean indicatif de réussite d'insertion de formulaire
     */
    public static function insertionFormulaire()
    {
        // Ajout de la structure
        $struct = new Structure();
        $struct->Nom = filter_var($_POST['nomstruct'], FILTER_SANITIZE_STRING);
        $struct->Adresse = filter_var($_POST['adrstruct'], FILTER_SANITIZE_STRING);
        $struct->CodePostal = filter_var($_POST['cpostalstruct'], FILTER_SANITIZE_STRING);
        $struct->Ville = filter_var($_POST['villestruct'], FILTER_SANITIZE_STRING);
        $struct->Raison = filter_var($_POST['raisonstruct'], FILTER_SANITIZE_STRING);
        if ($_POST['vousetes'] != "0")
            $struct->Type = $_POST['vousetes'];
        else
            $struct->Type = filter_var($_POST['autre'], FILTER_SANITIZE_STRING);
        if (strlen($_POST['site']) != 0)
            $struct->Site = filter_var($_POST['site'], FILTER_SANITIZE_URL);
        else
            $struct->Site = null;
        if (! $struct->save())
            return false;
        
        // Ajout du représentant
        $rep = new Representant();
        $rep->Nom = filter_var($_POST['nomrzplegal'], FILTER_SANITIZE_STRING);
        $rep->Prenom = filter_var($_POST['prenomrzplegal'], FILTER_SANITIZE_STRING);
        $rep->Qualite = filter_var($_POST['qualite'], FILTER_SANITIZE_STRING);
        if (! $rep->save())
            return false;
        
        // Ajout du responsable
        $res = new Responsable();
        $res->Nom = filter_var($_POST['nomresplegal'], FILTER_SANITIZE_STRING);
        $res->Prenom = filter_var($_POST['prenomresplegal'], FILTER_SANITIZE_STRING);
        $res->Position = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
        $res->Adresse = filter_var($_POST['adrport'], FILTER_SANITIZE_STRING);
        $res->CodePostal = filter_var($_POST['cpostalport'], FILTER_SANITIZE_STRING);
        $res->Ville = filter_var($_POST['villeport'], FILTER_SANITIZE_STRING);
        $res->Tel = filter_var($_POST['tel'], FILTER_SANITIZE_STRING);
        $res->courriel = filter_var($_POST['courriel'], FILTER_SANITIZE_EMAIL);
        if (! $res->save())
            return false;
        
        // Création du suivi
        $suivi = new Suivi();
        $suivi->Chrono = 0;
        $suivi->DateRep = null;
        $suivi->Montant = 0;
        $suivi->DateEnvoiConv = null;
        $suivi->DateRecepConv = null;
        $suivi->DateRecepRecu = null;
        $suivi->DateEnvoiCheque = null;
        $suivi->Observations = null;
        $suivi->Document = 0;
        if (! $suivi->save())
            return false;
        
        $IdStruct = $struct->IdStruct;
        $IdRes = $res->IdRes;
        $IdRep = $rep->IdRep;
        $IdSuivi = $suivi->IdSuivi;
        
        // Ajout du projet
        $proj = new Projet();
        $proj->IdStruct = $IdStruct;
        $proj->IdRes = $IdRes;
        $proj->IdRep = $IdRep;
        $proj->IdSuivi = $IdSuivi;
        $proj->DateDep = date("Y-m-d");
        $proj->Expose = filter_var($_POST['expose'], FILTER_SANITIZE_STRING);
        $proj->DateDeb = $_POST['datedeb'];
        $proj->Duree = filter_var($_POST['duree'], FILTER_SANITIZE_NUMBER_INT);
        $proj->Lieu = filter_var($_POST['lieu'], FILTER_SANITIZE_STRING);
        $proj->Aide = filter_var($_POST['aide'], FILTER_SANITIZE_NUMBER_INT);
        $proj->Budget = filter_var($_POST['budget'], FILTER_SANITIZE_NUMBER_INT);
        $proj->Fin = filter_var($_POST['findb'], FILTER_SANITIZE_STRING);
        $proj->InteretGeneral = filter_var($_POST['group0'], FILTER_SANITIZE_NUMBER_INT);
        $proj->Domaine = filter_var($_POST['domaine'], FILTER_SANITIZE_STRING);
        $proj->Mecenat = filter_var($_POST['group2'], FILTER_SANITIZE_NUMBER_INT);
        $proj->Fiscal = filter_var($_POST['group3'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['valorev']) != 0)
            $proj->Valorisation = filter_var($_POST['valorev'], FILTER_SANITIZE_STRING);
        else
            $proj->Valorisation = null;
        $proj->Document = $_POST['nbFile'];
        $proj->Nouv = 1;
        if (! $proj->save())
            return false;
        
        $IdProjet = $proj->IdProjet;
        
        // Ajout des personnes impliquées (co-financeurs et parrains)
        // Co-financeurs
        $x = $_POST['nbCo'];
        $y = 0;
        while ($x > 0) {
            if (isset($_POST['nomco' . $y])) {
                $imp = new Implique();
                $imp->IdProjet = $IdProjet;
                $imp->Nom = filter_var($_POST['nomco' . $y], FILTER_SANITIZE_STRING);
                $imp->Prenom = filter_var($_POST['prenomco' . $y], FILTER_SANITIZE_STRING);
                $imp->Role = 0;
                if (! $imp->save())
                    return false;
                $x --;
            }
            $y ++;
        }
        // Parrains
        if ($_POST['group1'] == 1) {
            $imp = new Implique();
            $imp->IdProjet = $IdProjet;
            $imp->Nom = filter_var($_POST['nomparrain'], FILTER_SANITIZE_STRING);
            $imp->Prenom = filter_var($_POST['prenomparrain'], FILTER_SANITIZE_STRING);
            $imp->Role = 1;
            if (! $imp->save())
                return false;
        }
        
        // Ajout des fichiers
        Uploads::ajoutFichierFormulaire($IdProjet);
        
        return true;
    }

    /**
     * Méthode de mise à jour de suivi de formulaire
     * @param int $id id du formulaire
     * @return boolean indicatif de réussite de mise à jour de formulaire
     */
    public static function majSuiviFormulaire($id)
    {
        $suivi = Suivi::getById($id);
        
        if (isset($_POST['decision'])) $suivi->chrono = -1;
        else $suivi->chrono = 0;
        $suivi->montant = $_POST["montant"];
        if ($_POST["dateRep"] != null)
            $suivi->DateRep = Formulaire::reconstruireDate($_POST["dateRep"]);
        else
            $suivi->DateRep = null;
        if ($_POST["dateEnvoiConv"] != null)
            $suivi->dateEnvoiConv = Formulaire::reconstruireDate($_POST["dateEnvoiConv"]);
        else
            $suivi->dateEnvoiConv = null;
        if ($_POST["dateRecepConv"] != null)
            $suivi->dateRecepConv = Formulaire::reconstruireDate($_POST["dateRecepConv"]);
        else
            $suivi->dateRecepConv = null;
        if ($_POST["dateRecepRecu"] != null)
            $suivi->dateRecepRecu = Formulaire::reconstruireDate($_POST["dateRecepRecu"]);
        else
            $suivi->dateRecepRecu = null;
        if ($_POST["dateEnvoiCheque"] != null)
            $suivi->dateEnvoiCheque = Formulaire::reconstruireDate($_POST["dateEnvoiCheque"]);
        else
            $suivi->dateEnvoiCheque = null;
        if ($_POST["observations"] != null)
            $suivi->observations = $_POST["observations"];
        else
            $suivi->observations = null;
        
        return $suivi->save();
    }

    //Méthode de suppression complète du formulaire
    public static function supprimerFormulaire($id)
    {
        
        //Supprimer ici les fichiers
        Uploads::supprimerFichierFormulaire($id);
        
        $implique = Implique::getImplique($id);
        foreach ($implique as $i)
            $i->delete();
        $projet = Projet::getById($id);
        $structure = Structure::getById($projet->IdStruct);
        $rep = Representant::getById($projet->IdRep);
        $res = Responsable::getById($projet->IdRes);
        $suivi = Suivi::getById($projet->IdSuivi);
        $projet->delete();
        $structure->delete();
        $rep->delete();
        $res->delete();
        $suivi->delete();
        
        Formulaire::majChronoSuivi();
    }

    //Méthode de mise à jour des numéros chrono
    public static function majChronoSuivi()
    {
        $listeSuivi = Suivi::getAllDate();
        if ($listeSuivi != null) {
            $chrono = 1;
            $date = explode("-", $listeSuivi[0]->DateDep)[0];
            foreach ($listeSuivi as $suivi) {
                if ($suivi->Chrono != 0) {
                    if (explode("-", $suivi->DateDep)[0] != $date) {
                        $chrono = 1;
                        $date = explode("-", $suivi->DateDep)[0];
                    }
                    $suivi->Chrono = $date."_".$chrono;
                    $suivi->save();
                    $chrono ++;
                }
            }
        }
    }

    //Méthode de transformation de date de type "année-mois-jour" en "jour mois année" (le mois n'étant plus un entier).
    public static function transformerDate($date)
    {
        $date = explode("-", $date);
        $monthArray = array(
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Août",
            "Septembre",
            "Octobre",
            "Novembre",
            "Décembre"
        );
        $jour = $date[2];
        $mois = $monthArray[$date[1] - 1];
        $annee = $date[0];
        return $jour . " " . $mois . " " . $annee;
    }

    //Méthode de transformation de date de type "jour mois année" (le mois n'étant plus un entier) en "année-mois-jour".
    public static function reconstruireDate($date)
    {
        $date = explode(" ", $date);
        $monthArray = array(
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Août",
            "Septembre",
            "Octobre",
            "Novembre",
            "Décembre"
        );
        $jour = $date[0];
        $mois = array_search($date[1], $monthArray) + 1;
        $annee = $date[2];
        return $annee . "-" . $mois . "-" . $jour;
    }

    //Méthode de transformation d'un booléen (entier) en chaîne de caractère
    public static function transformerBooleen($int)
    {
        if ($int == 0)
            return "Non";
        if ($int == 1)
            return "Oui";
        return null;
    }
}
