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
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //Il s'agit des méthodes rédirigées par Slim pour la partie Back Office.                            //
    //Elles sont séparées en deux catégorie : GET et POST suivant le type d'action effectué sur la page //
    //////////////////////////////////////////////////////////////////////////////////////////////////////   
    
    // /////////////////////////////////////
    // / GET ///
    // /////////////////////////////////////
    /**
     * Méthode d'accès à l'index du site. Si l'utilisateur est connecté, il est redirigé vers la liste des formulaires.
     */
    public function index()
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        //Si la personne n'est pas authentifiée
        if (! isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_INDEX);
        else
            $app->redirect($app->urlFor("listeFormulaires"));
    }

    /**
     * Méthode de deconnexion de l'utilisateur.
     */
    public function deconnexion()
    {
        $app = \Slim\Slim::getInstance();
        //Si la personne est authentifiée
        if (isset($_COOKIE['user'])) {
            Authentication::disconnect();
            $app->redirect($app->urlFor("connexionAdmin"));
        } else
            $app->notFound();
    }

    /**
     * Méthode d'affichage des formulaires. Renvoie une page inexistante si l'utilisateur n'existe pas.
     */
    public function affichageFormulaires()
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        //Si la personne est authentifiée
        if (isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_FORMULAIRE);
        else
            $app->notFound();
    }

    /**
     * Méthode d'affichage d'un projet. Renvoie une page inexistante si l'utilisateur n'existe pas.
     * @param int $no id du projet
     */
    public function affichageProjet($no)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        //Si la personne est authentifiée et le projet existe
        if (null !== Projet::getById($no) && isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_PROJET, $no);
        else
            $app->notFound();
    }

    /**
     * Méthode d'affichage de la page de recherche de formulmaire. Renvoie une page inexistante si l'utilisateur n'existe pas.
     * @param string $recherche titre de projet recherché
     */
    public function affichageRecherche($recherche)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        //Si la personne est authentifiée
        if (isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_RECHERCHE, $recherche);
        else
            $app->notFound();
    }

    /**
     * Méthode de suppression de fichier.
     * @param int $no id du projet
     */
    public function supprimerFichier($no)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        //Si la personne est authentifiée
        if (isset($_COOKIE['user'])) {
            //Si le fichier existe
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
    /**
     * Méthode de connexion au back office.
     */
    public function postConnexion()
    {
        $id = filter_var($_POST['loginCo'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['mdpCo'], FILTER_SANITIZE_STRING);
        Authentication::authenticate($id, $password);
    }

    /**
     * Méthode de recherche de projet par nom.
     * Si le résultat de la recherche donne un seul projet, redirige vers le projet, sinon redirige vers la page de recherche.
     */
    public function postRedirectionProjet()
    {
        $app = \Slim\Slim::getInstance();
        $nom = filter_var($_POST['autocompleteRecherche'], FILTER_SANITIZE_STRING);
        $struct = Structure::getByName($nom);
 
        //S'il existe un seul projet portant le nom de $nom
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

    /**
     * Méthode de suppression de formulaire.
     */
    public function postSuppressionFomulaire()
    {
        // echo $_POST['IdProjet'];
        $app = \Slim\Slim::getInstance();
        Formulaire::supprimerFormulaire($_POST['IdProjet']);
        $app->redirect($app->urlFor("listeFormulaires"));
    }

    /**
     * Méthode de modification de suivi avec mise à jour du numéro chrono.
     */
    public function postModificationSuivi()
    {
        $app = \Slim\Slim::getInstance();
        //Si la modification a été effectuée avec succès
        if (Formulaire::majSuiviFormulaire($_POST['IdSuivi']))
            $_SESSION['message'] = "Modification du suivi effectué avec succès !";
        else
            $_SESSION['message'] = "Une erreur est survenue lors de la modification du suivi. Vérifiez que les champs rentrés soient cohérents. Contactez l'administrateur si le problème persiste.";
        
        Formulaire::majChronoSuivi();
        $app->redirect($app->urlFor("projet", [
            'no' => $_POST['IdProjet']
        ]));
    }

    /**
     * Méthode d'ajout de fichier de suivi dans le projet.
     */
    public function postAjoutFichier()
    {
        $app = \Slim\Slim::getInstance();
        $id = $_POST["IdProjet"];
        $dir = scandir(Variable::$dossierFichier);
        $directory = null;
        
        //On cherche le dossier qui contient l'id $id au début de son intitulé
        foreach ($dir as $d) {
            if (($val = strpos($d, $id . "_")) === 0)
                $directory = $d;
        }
        
        //Si le dossier existe
        if ($directory != null) {
            $dir = Variable::$dossierFichier . "/" . $directory . "/";
            Uploads::ajoutFichierMultiples($dir, Variable::$dossierSpecifique[1], 'fileToUpload');
            
            //On compte le nombre de fichiers et on enregistre la valeur dans la base de données
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