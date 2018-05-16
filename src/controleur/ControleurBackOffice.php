<?php
namespace dbproject\controleur;

use dbproject\vue\VueBackOffice;
use dbproject\modele\Projet;
use dbproject\conf\Formulaire;
use dbproject\modele\Structure;
use dbproject\conf\Authentication;
use dbproject\conf\Variable;
use dbproject\conf\Uploads;
use dbproject\modele\Suivi;

class ControleurBackOffice
{

    // /////////////////////////////////////
    // / GET ///
    // /////////////////////////////////////
    public function index()
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        if (! isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_INDEX);
        else
            $app->redirect($app->urlFor("listeFormulaires"));
    }

    public function deconnexion()
    {
        $app = \Slim\Slim::getInstance();
        if (isset($_COOKIE['user'])) {
            Authentication::disconnect();
            $app->redirect($app->urlFor("connexionAdmin"));
        } else
            $app->notFound();
    }

    public function affichageFormulaires()
    {
        // Envoi de mail
        /**
         * $subject = "Nouveau dépôt de projet";
         * $msg = "Slt";
         * Email::sendMail($subject, $msg);
         */
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        if (isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_FORMULAIRE);
        else
            $app->notFound();
    }

    public function affichageProjet($no)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        if (null !== Projet::getById($no) && isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_PROJET, $no);
        else
            $app->notFound();
    }

    public function affichageRecherche($recherche)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        if (isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_RECHERCHE, $recherche);
        else
            $app->notFound();
    }

    public function supprimerFichier($no)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        if (isset($_COOKIE['user'])) {
            if (isset($_GET['file'])) {
                Uploads::supprimerFichier($no, $_GET['file']);
                
                $app->redirect($app->urlFor("projet", [
                    'no' => $no
                ]));
            } else $app->notFound();
        } else
            $app->notFound();
    }

    // /////////////////////////////////////
    // / POST ///
    // /////////////////////////////////////
    public function postConnexion()
    {
        $id = filter_var($_POST['loginCo'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['mdpCo'], FILTER_SANITIZE_STRING);
        Authentication::authenticate($id, $password);
    }

    public function postRedirectionProjet()
    {
        $app = \Slim\Slim::getInstance();
        $nom = filter_var($_POST['autocompleteRecherche'], FILTER_SANITIZE_STRING);
        $struct = Structure::getByName($nom);
        /**
         * foreach ($struct as $s) {
         * echo $s .
         * '<br>';
         * }
         */
        if (sizeof($struct) == 1) {
            $idStruct = $struct[0]->IdStruct;
            $projet = Projet::getByStructure($idStruct);
            $app->redirect($app->urlFor("projet", [
                'no' => $projet->IdProjet
            ]));
        } else {
            $app->redirect($app->urlFor("recherche", [
                'recherche' => $nom
            ]));
        }
    }

    public function postSuppressionFomulaire()
    {
        // echo $_POST['IdProjet'];
        $app = \Slim\Slim::getInstance();
        Formulaire::supprimerFormulaire($_POST['IdProjet']);
        $app->redirect($app->urlFor("listeFormulaires"));
    }

    public function postModificationSuivi()
    {
        $app = \Slim\Slim::getInstance();
        if (Formulaire::majSuiviFormulaire($_POST['IdSuivi']))
            $_SESSION['message'] = "Modification du suivi effectué avec succès !";
        else
            $_SESSION['message'] = "Une erreur est survenue lors de la modification du suivi. Vérifiez que les champs rentrés soient cohérents. Contactez l'administrateur si le problème persiste.";
        
        Formulaire::majChronoSuivi();
        $app->redirect($app->urlFor("projet", [
            'no' => $_POST['IdProjet']
        ]));
    }

    public function postAjoutFichier()
    {
        $app = \Slim\Slim::getInstance();
        $id = $_POST["IdProjet"];
        $dir = scandir(Variable::$dossierFichier);
        $directory = null;
        foreach ($dir as $d) {
            if (($val = strpos($d, $id . "_")) === 0)
                $directory = $d;
        }
        
        if ($directory != null) {
            $dir = Variable::$dossierFichier . "/" . $directory . "/";
            Uploads::ajoutFichierMultiples($dir, Variable::$dossierSpecifique[1], 'fileToUpload');
            
            $folder = scandir($dir . Variable::$dossierSpecifique[1] . "/");
            $i = 0;
            foreach ($folder as $f)
                if ($f != "." && $f != "..")
                    $i ++;
            $projet = Projet::getById($id);
            $suivi = Suivi::getById($projet->IdSuivi);
            $suivi->Document = $i;
            $suivi->save();
        } else
            $_SESSION['message'] = "Une erreur est survenue lors de l'enrengistrement du/des fichier(s). Veuillez réessayer. Contactez l'administrateur si le problème persiste.";
        
        $app->redirect($app->urlFor("projet", [
            'no' => $_POST['IdProjet']
        ]));
    }
}