<?php
namespace dbproject\controleur;

use dbproject\vue\VueBackOffice;
use dbproject\modele\Projet;
use dbproject\modele\User;
use dbproject\conf\Formulaire;
use dbproject\modele\Structure;
use dbproject\conf\Authentication;
use dbproject\conf\Variable;
use dbproject\conf\Uploads;
use dbproject\modele\Suivi;
use dbproject\modele\Representant;
use dbproject\modele\Responsable;
use dbproject\modele\Implique;

class ControleurBackOffice
{

    // ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Il s'agit des méthodes rédirigées par Slim pour la partie Back Office. //
    // Elles sont séparées en deux catégorie : GET et POST suivant le type d'action effectué sur la page //
    // ////////////////////////////////////////////////////////////////////////////////////////////////////
    
    // /////////////////////////////////////
    // / GET ///
    // /////////////////////////////////////
    /**
     * Méthode d'accès à l'index du site.
     * Si l'utilisateur est connecté, il est redirigé vers la liste des formulaires.
     */
    public function index()
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        // Si la personne n'est pas authentifiée
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
        // Si la personne est authentifiée
        if (isset($_COOKIE['user'])) {
            Authentication::disconnect();
            $projets = Projet::getAll();
            foreach ($projets as $p) {
                $p->Nouv = 0;
                $p->save();
            }
            $app->redirect($app->urlFor("connexionAdmin"));
        } else
            $app->notFound();
    }

    public function gestionCompte()
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        // Si la personne est authentifiée
        if (isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_GESTION);
        else
            $app->notFound();
    }

    public function creationCompte()
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        // Si la personne est authentifiée et si l'utilisateur est administrateur
        if (isset($_COOKIE['user'])) {
            $user = User::getById($app->getEncryptedCookie("user"));
            if ($user->droit != "0")
                print $vue->render(VueBackOffice::AFF_CREATION);
            else
                $app->notFound();
        } else
            $app->notFound();
    }

    /**
     * Méthode d'affichage des formulaires.
     * Renvoie une page inexistante si l'utilisateur n'existe pas.
     */
    public function affichageFormulaires()
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        // Si la personne est authentifiée
        if (isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_FORMULAIRE);
        else
            $app->notFound();
    }

    /**
     * Méthode d'affichage d'un projet.
     * Renvoie une page inexistante si l'utilisateur n'existe pas.
     *
     * @param int $no
     *            id du projet
     */
    public function affichageProjet($no)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        // Si la personne est authentifiée et le projet existe
        if (null !== ($proj = Projet::getById($no)) && isset($_COOKIE['user'])) {
            if ($proj->Nouv == "1") {
                $proj->Nouv = "0";
                $proj->save();
            }
            print $vue->render(VueBackOffice::AFF_PROJET, $no);
        } else
            $app->notFound();
    }

    /**
     * Méthode d'affichage de la page de recherche de formulmaire.
     * Renvoie une page inexistante si l'utilisateur n'existe pas.
     *
     * @param string $recherche
     *            titre de projet recherché
     */
    public function affichageRecherche($recherche)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        // Si la personne est authentifiée
        if (isset($_COOKIE['user']))
            print $vue->render(VueBackOffice::AFF_RECHERCHE, $recherche);
        else
            $app->notFound();
    }

    /**
     * Méthode de suppression de fichier.
     *
     * @param int $no
     *            id du projet
     */
    public function supprimerFichier($no)
    {
        $app = \Slim\Slim::getInstance();
        $vue = new VueBackOffice();
        // Si la personne est authentifiée
        if (isset($_COOKIE['user'])) {
            // Si le fichier existe
            if (isset($_GET['file'])) {
                Uploads::supprimerFichier($no, $_GET['file']);
                
                $app->redirect($app->urlFor("projet", [
                    'no' => $no
                ]));
            } else
                $app->notFound();
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
        $app = \Slim\Slim::getInstance();
        $id = filter_var($_POST['loginCo'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['mdpCo'], FILTER_SANITIZE_STRING);
        Authentication::authenticate($id, $password);
        $app->redirect($app->urlFor("listeFormulaires"));
    }

    public function postCreationCompte()
    {
        $app = \Slim\Slim::getInstance();
        $id = filter_var($_POST['loginInscr'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['mdpInscr'], FILTER_SANITIZE_STRING);
        $statut = $_POST['selectStatut'];
        Authentication::createUser($id, $password, $statut);
        $_SESSION['message'] = "Ajout effectué avec succès.";
        $app->redirect($app->urlFor("gestionCompte"));
    }

    public function postModificationCompte()
    {
        $app = \Slim\Slim::getInstance();
        $user = User::getById($_POST['idUser']);
        $user->droit = $_POST['selectDroit'];
        
        if (isset($_POST['checkbox'])) {
            $i = $_POST['numGestion'];
            $user->mdp = password_hash($_POST['mdpModif' . $i . "w1"], PASSWORD_DEFAULT, Array(
                'cost' => 12
            ));
        }
        $user->save();
        $_SESSION['message'] = "Les modifications ont été enregistrées avec succès.";
        $app->redirect($app->urlFor("gestionCompte"));
    }

    public function postSuppressionCompte()
    {
        $app = \Slim\Slim::getInstance();
        Authentication::delete($_POST["idUserModal"]);
        $app->redirect($app->urlFor("gestionCompte"));
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
        
        // S'il existe un seul projet portant le nom de $nom
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
        $app = \Slim\Slim::getInstance();
        Formulaire::supprimerFormulaire($_POST['IdProjet']);
        $app->redirect($app->urlFor("listeFormulaires"));
    }

    public function postModificationProjet()
    {
        $app = \Slim\Slim::getInstance();
        $proj = Projet::getById($_POST['IdProjet']);
        $proj->Expose = filter_var($_POST['expose'], FILTER_SANITIZE_STRING);
        $proj->DateDeb = Formulaire::reconstruireDate($_POST['datedeb']);
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
        $proj->save();
        $_SESSION['message'] = "Modification de projet effectué avec succès !";
        $app->redirect($app->urlFor("projet", [
            'no' => $_POST['IdProjet']
        ]));
    }

    public function postModificationStructure()
    {
        $app = \Slim\Slim::getInstance();
        $struct = Structure::getById($_POST['IdStruct']);
        $struct->Nom = filter_var($_POST['nomstruct'], FILTER_SANITIZE_STRING);
        $struct->Adresse = filter_var($_POST['adrstruct'], FILTER_SANITIZE_STRING);
        $struct->CodePostal = filter_var($_POST['cpostalstruct'], FILTER_SANITIZE_STRING);
        $struct->Ville = filter_var($_POST['villestruct'], FILTER_SANITIZE_STRING);
        $struct->Raison = filter_var($_POST['raisonstruct'], FILTER_SANITIZE_STRING);
        $struct->Type = filter_var($_POST['vousetes'], FILTER_SANITIZE_STRING);
        $struct->Site = filter_var($_POST['site'], FILTER_SANITIZE_URL);
        $struct->save();
        $_SESSION['message'] = "Modification de structure effectué avec succès !";
        $app->redirect($app->urlFor("projet", [
            'no' => $_POST['IdProjet']
        ]));
    }

    public function postModificationCoFinanceur()
    {
        $app = \Slim\Slim::getInstance();
        $cofin = Implique::getCoFinanceur($_POST['IdProjet']);
        $i = 0;
        foreach ($cofin as $co) {
            $co->Nom = $_POST['nomco' . $i];
            $co->Prenom = $_POST['prenomco' . $i];
            $co->save();
            $i ++;
        }
        $_SESSION['message'] = "Modification de co-financeur(s) effectué avec succès !";
        $app->redirect($app->urlFor("projet", [
            'no' => $_POST['IdProjet']
        ]));
    }

    public function postModificationParrain()
    {
        $app = \Slim\Slim::getInstance();
        $parrain = Implique::getParrain($_POST['IdProjet']);
        foreach ($parrain as $p) {
            $p->Nom = $_POST['nomparrain'];
            $p->Prenom = $_POST['prenomparrain'];
            $p->save();
        }
        $_SESSION['message'] = "Modification de parrain effectué avec succès !";
        $app->redirect($app->urlFor("projet", [
            'no' => $_POST['IdProjet']
        ]));
    }

    public function postModificationRepresentant()
    {
        $app = \Slim\Slim::getInstance();
        $rep = Representant::getById($_POST['IdRep']);
        $rep->Nom = filter_var($_POST['nomrpzlegal'], FILTER_SANITIZE_STRING);
        $rep->Prenom = filter_var($_POST['prenomrpzlegal'], FILTER_SANITIZE_STRING);
        $rep->Qualite = filter_var($_POST['qualite'], FILTER_SANITIZE_STRING);
        $rep->save();
        $_SESSION['message'] = "Modification de représentant effectué avec succès !";
        $app->redirect($app->urlFor("projet", [
            'no' => $_POST['IdProjet']
        ]));
    }

    public function postModificationResponsable()
    {
        $app = \Slim\Slim::getInstance();
        $res = Responsable::getById($_POST["IdResp"]);
        $res->Nom = filter_var($_POST['nomresplegal'], FILTER_SANITIZE_STRING);
        $res->Prenom = filter_var($_POST['prenomresplegal'], FILTER_SANITIZE_STRING);
        $res->Position = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
        $res->Adresse = filter_var($_POST['adrport'], FILTER_SANITIZE_STRING);
        $res->CodePostal = filter_var($_POST['cpostalport'], FILTER_SANITIZE_STRING);
        $res->Ville = filter_var($_POST['villeport'], FILTER_SANITIZE_STRING);
        $res->Tel = filter_var($_POST['tel'], FILTER_SANITIZE_STRING);
        $res->courriel = filter_var($_POST['courriel'], FILTER_SANITIZE_EMAIL);
        $res->save();
        $_SESSION['message'] = "Modification de responsable effectué avec succès !";
        $app->redirect($app->urlFor("projet", [
            'no' => $_POST['IdProjet']
        ]));
    }

    /**
     * Méthode de modification de suivi avec mise à jour du numéro chrono.
     */
    public function postModificationSuivi()
    {
        $app = \Slim\Slim::getInstance();
        // Si la modification a été effectuée avec succès
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
        
        // On cherche le dossier qui contient l'id $id au début de son intitulé
        foreach ($dir as $d) {
            if (($val = strpos($d, $id . "_")) === 0)
                $directory = $d;
        }
        
        // Si le dossier existe
        if ($directory != null) {
            $dir = Variable::$dossierFichier . "/" . $directory . "/";
            Uploads::ajoutFichierMultiples($dir, Variable::$dossierSpecifique[1], 'fileToUpload');
            
            // On compte le nombre de fichiers et on enregistre la valeur dans la base de données
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