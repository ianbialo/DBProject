<?php
namespace dbproject\vue;

use dbproject\conf\Formulaire;
use dbproject\conf\Modal;
use dbproject\modele\Projet;
use dbproject\modele\Structure;
use dbproject\modele\Representant;
use dbproject\modele\Responsable;
use dbproject\modele\Implique;
use dbproject\modele\Suivi;
use dbproject\conf\Variable;
use dbproject\modele\User;
use dbproject\conf\Authentication;

/**
 * Classe répertoriant les codes HTML liés au Back Office.
 *
 * @author IBIALO
 *        
 */
class VueBackOffice
{

    /**
     * Selecteur de l'index
     *
     * @var integer
     */
    const AFF_INDEX = 0;

    /**
     * Selecteur de la liste des formulaires
     *
     * @var integer
     */
    const AFF_FORMULAIRE = 1;

    /**
     * Selecteur d'un projet
     *
     * @var integer
     */
    const AFF_PROJET = 2;

    /**
     * Selecteur de la recherche
     *
     * @var integer
     */
    const AFF_RECHERCHE = 3;

    /**
     * Selecteur de la gestion de compte
     *
     * @var integer
     */
    const AFF_GESTION = 4;

    /**
     * Selecteur de la création de compte
     *
     * @var integer
     */
    const AFF_CREATION = 5;

    /**
     * Selecteur permettant de facilement faire appel aux méthode de la classe
     *
     * @param int $selecteur
     *            selecteur permettant d'accéder à la méthode souhaitée
     * @param object $tab
     *            données pouvant être transmises depuis le controleur
     * @return string
     */
    public function render($selecteur, $tab = null)
    {
        // On vérifie que l'utilisateur n'a pas été supprimé depuis sa connexion au BackOffice
        $app = \Slim\Slim::getInstance();
        $mail = $app->getEncryptedCookie("user");
        $user = User::getById($mail);
        if ($selecteur != VueBackOffice::AFF_INDEX) {
            if ($user == null) {
                Authentication::disconnect();
                $app->redirect($app->urlFor("connexionAdmin"));
            }
        }
        switch ($selecteur) {
            case VueBackOffice::AFF_INDEX:
                $content = $this->index();
                break;
            case VueBackOffice::AFF_FORMULAIRE:
                $content = $this->formulaire();
                break;
            case VueBackOffice::AFF_PROJET:
                $content = $this->projet($tab);
                break;
            case VueBackOffice::AFF_RECHERCHE:
                $content = $this->recherche($tab);
                break;
            case VueBackOffice::AFF_GESTION:
                $content = $this->gestionCompte();
                break;
            case VueBackOffice::AFF_CREATION:
                $content = $this->creationCompte();
                break;
        }
        return $content;
    }

    /**
     * Méthode générant le code HTML de l'index du back office
     *
     * @return string code HTML de l'index du back office
     *        
     */
    private function index()
    {
        $app = \Slim\Slim::getInstance();
        $postCo = $app->urlFor("postConnexion");
        $res = <<<end
        
        <div class="card-panel hoverable">
        <h3>Connexion au back office</h3>
        <form method="POST" action="$postCo">
            <div class="input-field col s12">
                <i class="material-icons prefix">account_circle</i>
                <input id="loginCo" type="email" name="loginCo" class="validate active" required><br>
                <label for="loginCo">Login</label>
                <span class="helper-text" data-error="Il faut rentrer une adresse mail valide" ></span>
            </div>
            <div class="input-field col s12">
                <i class="material-icons prefix">https</i>
                <input id="mdpCo" type="password" name="mdpCo" class="validate active" required><br>
                <label for="mdpCo">Mot de passe</label>
            </div>
            <button class="btn" type="submit" name="action">Valider
                <i class="material-icons right">send</i>
            </button>
        </form>
        </div>
end;
        $res .= Modal::genereModal() . "<script>
$(document).ready(function() {";
        ;
        if (isset($_SESSION['message'])) {
            $msg = $_SESSION['message'];
            $res .= Modal::enclencher($msg);
            $_SESSION['message'] = null;
        }
        $res .= "});
</script>
";
        
        return $res;
    }

    /**
     * Méthode générant le code HTML de la page de la liste des projets
     *
     * @return string code HTML de la page de la liste des projets
     */
    private function formulaire()
    {
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        $mail = $app->getEncryptedCookie("user");
        
        $testQuery = array(
            0,
            1
        );
        $testValidate = array(
            0,
            1,
            2
        );
        
        if (isset($_GET['query'])) {
            if (in_array($_GET['query'], $testQuery)) {
                $query = filter_var($_GET['query'], FILTER_SANITIZE_NUMBER_INT);
            } else
                $query = 0;
        } else
            $query = 0;
        
        if (isset($_GET['validate'])) {
            if (in_array($_GET['validate'], $testValidate)) {
                $validate = filter_var($_GET['validate'], FILTER_SANITIZE_NUMBER_INT);
            } else
                $validate = 0;
        } else
            $validate = 0;
        
        $redirection = $app->urlFor("postRedirection");
        
        switch ($query) {
            case 1:
                $listeProj = Projet::getAllDate($validate);
                break;
            default:
                $listeProj = Projet::getAll($validate);
                break;
        }
        
        $res = <<<end


            <div class="container">
                <h3>Liste des projets - $mail</h3>
   
                    <div class="input-field col s12">
                        <select size="1" name="links" onchange="window.location.href=this.value;">
                            <option value="" disabled selected>Choisissez un projet</option>
end;
        foreach ($listeProj as $p) {
            $struct = Structure::getById($p->IdStruct);
            $changementTri = $app->urlFor("listeFormulaires");
            $redirection2 = $app->urlFor("projet", [
                'no' => $p->IdProjet
            ]);
            $res .= '
                    <option value="' . $redirection2 . '">' . $struct->Nom . '</option>';
        }
        $res .= <<<end
                    </select>
                    <label>Accès rapide aux projets</label>
                </div>

                <div class="row">
                    <form id="formRecherche" action="$redirection" method="POST" autocomplete="off">
                    <div class="col s12">
                      <div class="row">
                        <div class="input-field col s12">
                            <input type="text" name="autocompleteRecherche" id="autocompleteRecherche" class="autocomplete" required>
                            <label for="autocompleteRecherche">Rechercher un projet</label>    
                        </div>
                        <button class="btn" type="submit" name="action">Rechercher
                            <i class="material-icons right">send</i>
                        </button>
                      </div>
                    </div>
                    </form>
                  </div>

                  <div class="input-field col s12">
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                        
end;
        if ($query == 0)
            $res .= '<option value="' . $changementTri . '?query=0&validate=' . $validate . '" selected>Alphabétique</option>
                        ';
        else
            $res .= '<option value="' . $changementTri . '?query=0&validate=' . $validate . '">Alphabétique</option>
                        ';
        if ($query == 1)
            $res .= '<option value="' . $changementTri . '?query=1&validate=' . $validate . '" selected>Date de création</option>
                        ';
        else
            $res .= '<option value="' . $changementTri . '?query=1&validate=' . $validate . '">Date de création</option>
                        ';
        $res .= <<<end
                    </select>
                    <label>Trier par</label>
                  </div>
                  <div class="row col s6">
                    <div class="col s2">
                    <p>
                      <label>
end;
        if ($validate == 0)
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;' . $changementTri . '?query=' . $query . '&validate=0&#39;" checked />';
        else
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;' . $changementTri . '?query=' . $query . '&validate=0&#39;" />';
        $res .= <<<end

                        <span>Tous</span>
                      </label>
                    </p>
                    </div>
                    <div class="col s2">
                    <p>
                      <label>
end;
        if ($validate == 1)
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;' . $changementTri . '?query=' . $query . '&validate=1&#39;" checked />';
        else
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;' . $changementTri . '?query=' . $query . '&validate=1&#39;" />';
        $res .= <<<end

                        <span>Traités</span>
                      </label>
                    </p>
                    </div>
                    <div class="col s2">
                    <p>
                      <label>
end;
        if ($validate == 2)
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;' . $changementTri . '?query=' . $query . '&validate=2&#39;" checked />';
        else
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;' . $changementTri . '?query=' . $query . '&validate=2&#39;" />';
        $res .= <<<end

                        <span>Non-traités</span>
                      </label>
                    </p>
                    </div>
                  </div>
            
            
                
end;
        foreach ($listeProj as $p) {
            $struct = Structure::getById($p->IdStruct);
            $rep = Representant::getById($p->IdRep);
            $resp = Responsable::getById($p->IdRes);
            $suivi = Suivi::getById($p->IdSuivi);
            $acceder = $app->urlFor("projet", [
                'no' => $p->IdProjet
            ]);
            $supprimer = $app->urlFor("postSuppressionFormulaire");
            
            $date = Formulaire::transformerDate($p->DateDep);
            if ($suivi->Chrono != "0")
                $titre = $struct->Nom . " - " . $date . " - <span class='green-text text-accent-4'>n° Chrono : " . $suivi->Chrono . "</span>";
            else
                $titre = $struct->Nom . " - " . $date;
            if ($p->Nouv == "1")
                $titre = "<span class='red-text text-accent-4'>New</span> " . $titre;
            $res .= <<<end

              <!-- Modal Structure -->
              <div id="modal$p->IdProjet" class="modal">
                <div class="modal-content">
                  <h4>Suppression de projet</h4>
                  <p>Supprimer le projet de $struct->Nom est un acte irréversible. Êtes-vous sûr de vouloir continuer ?</p>
                </div>
                <div class="modal-footer">
                  <form methode="POST" action="$supprimer">
                    <a href="" class="modal-action modal-close waves-effect waves-green btn-flat">Annuler</a>
                    <input id="IdProjet" name="IdProjet" type="hidden" value="$p->IdProjet">
                    <input type="submit" formmethod="post" value="Confirmer" class="modal-action modal-close waves-effect waves-green btn-flat">
                  </form>
                </div>
              </div>

                    <div class="row">
    <div class="col s12">
      <div class="hoverable card">
        <div class="card-content">
          <span class="card-title">$titre</span>
          <label>Représentant : $rep->Nom $rep->Prenom - Responsable : $resp->Nom $resp->Prenom</label>
          <p class="truncate">$p->Expose</p>
        </div>
        <div class="card-action">
          <a href="$acceder">Accéder</a>
          <a class="modal-trigger" href="#modal$p->IdProjet">Supprimer</a>
             
        </div>
      </div>
    </div>
  </div>

  
end;
        }
        
        $res .= <<<end
	              
            </div>

end;
        return $res;
    }

    /**
     * Méthode générant le code HTML de la page d'un projet
     *
     * @param int $no
     *            id du projet
     * @return string code HTML de la page de la liste des projets
     */
    private function projet($no)
    {
        $app = \Slim\Slim::getInstance();
        $liste = $app->urlFor("listeFormulaires");
        
        $proj = Projet::getById($no);
        $struct = Structure::getById($proj->IdStruct);
        $rep = Representant::getById($proj->IdRep);
        $resp = Responsable::getById($proj->IdRes);
        $suivi = Suivi::getById($proj->IdSuivi);
        
        // Recherche des fichiers uploadés par le créateur du projet
        $dossier = null;
        $fichiers = array();
        $folderSpecifique = Variable::$dossierSpecifique[0];
        
        if ($proj->Document != 0) {
            $app = \Slim\Slim::getInstance();
            $requete = $app->request();
            $path = $requete->getRootUri();
            
            // Dossier avec tout les dossiers
            $list = scandir(Variable::$path . "/" . Variable::$dossierFichier);
            
            foreach ($list as $l) {
                $id = explode("_", $l)[0];
                if ($no == $id) {
                    
                    $dossier = $l;
                    
                    // Dossier avec tout les fichiers recherchés
                    $list2 = scandir(Variable::$path . "/" . Variable::$dossierFichier . "/" . $l . "/" . $folderSpecifique);
                    
                    $zip = null;
                    foreach ($list2 as $i) {
                        if ($i != "." && $i != "..") {
                            if (self::endsWith($i, ".zip"))
                                $zip = $i;
                            else {
                                array_push($fichiers, $i);
                            }
                        }
                    }
                    array_push($fichiers, $zip);
                }
            }
        }
        
        // Recherche des fichiers uploadés par les personnes ayant accès au backoffice
        $fichiersPerso = array();
        $folderSpecifique = Variable::$dossierSpecifique[1];
        
        if ($suivi->Document != 0) {
            $app = \Slim\Slim::getInstance();
            $requete = $app->request();
            $path = $requete->getRootUri();
            
            // Dossier avec tout les dossiers
            $list = scandir(Variable::$path . "/" . Variable::$dossierFichier);
            
            foreach ($list as $l) {
                $id = explode("_", $l)[0];
                if ($no == $id) {
                    
                    // Dossier avec tout les fichiers recherchés
                    $list2 = scandir(Variable::$path . "/" . Variable::$dossierFichier . "/" . $l . "/" . $folderSpecifique);
                    
                    foreach ($list2 as $i) {
                        if ($i != "." && $i != "..") {
                            array_push($fichiersPerso, $i);
                        }
                    }
                }
            }
        }
        
        // Transformation des dates dans la partie du projet
        $dateDep = Formulaire::transformerDate($proj->DateDep);
        $dateDeb = Formulaire::transformerDate($proj->DateDeb);
        
        // Transformation des dates et ajustement des titres dans la partie de suivi
        if ($suivi->DateRep != null) {
            $dateRep = "value='" . Formulaire::transformerDate($suivi->DateRep) . "'";
            $titleDateRep = "Date de la réponse DB";
        } else {
            $dateRep = "placeholder='" . Formulaire::transformerDate(Date("Y-m-d")) . "'";
            $titleDateRep = "<span class='red-text'>Date de la réponse DB - Valeur inchangée</span>";
        }
        if ($suivi->DateEnvoiConv != null) {
            $dateEnvoiConv = "value='" . Formulaire::transformerDate($suivi->DateEnvoiConv) . "'";
            $titleDateEnvoiConv = "Date de l'envoi de la convention";
        } else {
            $dateEnvoiConv = "placeholder='" . Formulaire::transformerDate(Date("Y-m-d")) . "'";
            $titleDateEnvoiConv = "<span class='red-text'>Date de l'envoi de la convention - Valeur inchangée</span>";
        }
        if ($suivi->DateRecepConv != null) {
            $dateRecepConv = "value='" . Formulaire::transformerDate($suivi->DateRecepConv) . "'";
            $titleDateRecepConv = "Date de la réception de la convention signée";
        } else {
            $dateRecepConv = "placeholder='" . Formulaire::transformerDate(Date("Y-m-d")) . "'";
            $titleDateRecepConv = "<span class='red-text'>Date de la réception de la convention signée - Valeur inchangée</span>";
        }
        if ($suivi->DateRecepRecu != null) {
            $dateRecepRecu = "value='" . Formulaire::transformerDate($suivi->DateRecepRecu) . "'";
            $titleDateRecepRecu = "Date de la réception du reçu / cerfa";
        } else {
            $dateRecepRecu = "placeholder='" . Formulaire::transformerDate(Date("Y-m-d")) . "'";
            $titleDateRecepRecu = "<span class='red-text'>Date de la réception du reçu / cerfa - Valeur inchangée</span>";
        }
        if ($suivi->DateEnvoiCheque != null) {
            $dateEnvoiCheque = "value='" . Formulaire::transformerDate($suivi->DateEnvoiCheque) . "'";
            $titleDateEnvoiCheque = "Date de l'envoi du chèque";
        } else {
            $dateEnvoiCheque = "placeholder='" . Formulaire::transformerDate(Date("Y-m-d")) . "'";
            $titleDateEnvoiCheque = "<span class='red-text'>Date de l'envoi du chèque - Valeur inchangée</span>";
        }
        if ($suivi->Chrono != "0")
            $titleSuivi = "Suivi - <strong>n° chrono : $suivi->Chrono</strong>";
        else
            $titleSuivi = "Suivi";
        
        // Transformation des booléens dans la partie du projet
        // $interetG = Formulaire::transformerBooleen($proj->InteretGeneral);
        $interetG = self::generateSelectOuiNon("group0", $proj->InteretGeneral);
        // $mecenat = Formulaire::transformerBooleen($proj->Mecenat);
        $mecenat = self::generateSelectOuiNon("group2", $proj->Mecenat);
        // $fiscal = Formulaire::transformerBooleen($proj->Fiscal);
        $fiscal = self::generateSelectOuiNon("group3", $proj->Fiscal);
        
        if ($suivi->Chrono != 0)
            $checked = 'checked="checked"';
        else
            $checked = '';
        
        if (isset($proj->Valorisation)) {
            $valor = $proj->Valorisation;
            $valor = '<input type="text" name="valorev" id="valorev" value="' . $valor . '">';
        } else
            $valor = '<input type="text" name="valorev" id="valorev" placeholder="Aucune valorisation">';
        
        if (isset($struct->Site))
            $site = $struct->Site;
        else
            $site = "<label>Aucun site enrengistré</label>";
        
        $cofin = Implique::getCoFinanceur($no);
        $parrain = Implique::getParrain($no);
        
        $modificationProjet = $app->urlFor("postModificationProjet");
        $modificationStructure = $app->urlFor("postModificationStructure");
        $modificationRepresentant = $app->urlFor("postModificationRepresentant");
        $modificationResponsable = $app->urlFor("postModificationResponsable");
        $modificationCofinanceur = $app->urlFor("postModificationCofinanceur");
        $modificationParrain = $app->urlFor("postModificationParrain");
        $modificationSuivi = $app->urlFor("postModificationSuivi");
        $ajoutFichier = $app->urlFor("postAjoutFichier");
        
        $res = <<<end
        <div class="container row">
                <div class="card hoverable">
                  <div class="card-content">
                    <div class="col s6">
                        <h5>$struct->Nom</h5>
                    </div>
                    <div class="col s6">
                        <h5>Date de création : $dateDep</h5>
                    </div>
                  </div>
                  <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width">
                      <li class="tab"><a class="active" href="#14c1">Projet</a></li>
                      <li class="tab"><a href="#14c2" class="">Structure</a></li>
                      <li class="tab"><a href="#14c3" class="">Représentant</a></li>
                      <li class="tab"><a href="#14c4" class="">Responsable</a></li>
                      <li class="tab"><a href="#14c5" class="">Impliqué(s)</a></li>
                      <li class="tab"><a href="#14c6" class="">Fichier(s)</a></li>
                      <li class="tab"><a href="#suivi" class="blue-text">Suivi</a></li>
                    <div class="indicator"></div></ul>
                  </div>
                  <div class="card-content grey lighten-4">
                    <div id="14c1" class="active" style="display: block;">
                        <form method="POST" id="formSuivi" action="$modificationProjet" autocomplete="off">
                        <table>
                		<thead>
                			<tr>
                				<th>Intitulé</th>
                				<th>Description</th>
                			</tr>
                		</thead>
                
                		<tbody>
                			<tr>
                				<td>Exposé synthétique du projet ou des actions à soutenir</td>
                				<td><input type="text" name="expose" id="expose" size="50" value="$proj->Expose" required></td>
                			</tr>
                			<tr>
                				<td>Date de début du projet</td>
                				<td><input type="text" class="datepicker" name="datedeb" id="datedeb" value="$dateDeb" required></td>
                			</tr>
                			<tr>
                				<td>Durée (en mois)</td>
                				<td><input type="number" name="duree" id="duree" value="$proj->Duree" required></td>
                			</tr>
                			<tr>
                				<td>Lieu</td>
                				<td><input type="text" name="lieu" id="lieu" value="$proj->Lieu" required></td>
                			</tr>
                			<tr>
                				<td>Montant de l'aide financière sollicitée (en euros)</td>
                				<td><input type="number" name="aide" id="aide" value="$proj->Aide" required></td>
                			</tr>
                			<tr>
                				<td>Budget prévisionnel global du projet (en euros)</td>
                				<td><input type="number" name="budget" id="budget" value="$proj->Budget" required><td>
                			</tr>
                			<tr>
                				<td>Fins utilisé du montant demandé à Demathieu Bard</td>
                				<td><input type="text" name="findb" id="findb" value="$proj->Fin" required></td>
                			</tr>
                            <tr>
                				<td>Le projet est-il d'intérêt général ?</td>
                				<td>$interetG</td>
                			</tr>
                            <tr>
                				<td>Domaine principal du projet</td> 
                				<td><input type="text" name="domaine" id="domaine" value="$proj->Domaine" required></td>
                			</tr>
                            <tr>
                				<td>Possibilité d'établir une convention de Mécénat ?</td>
                				<td>$mecenat</td>
                			</tr>
                            <tr>
                				<td>Possibilité d'établir un reçu fiscal (cerfa n°11580*03) ?</td>
                				<td>$fiscal</td>
                			</tr>
                            <tr>
                				<td>Valorisation éventuelle</td>
                				<td>$valor</td>
                			</tr>
                		</tbody>
	                   </table>
                        <br>
                        <input type="hidden" value="$no" name="IdProjet" id="IdProjet">
                        <button class="btn waves-light" type="submit" name="action">Modifier
                            <i class="material-icons right">send</i>
                        </button>
                        </form>
                    </div>
                    <div id="14c2" class="" style="display: none;">
                        <form method="POST" id="formStructure" action="$modificationStructure" autocomplete="off">
                        <table>
                		<thead>
                			<tr>
                				<th>Intitulé</th>
                				<th>Description</th>
                			</tr>
                		</thead>
                
                		<tbody>
                			<tr>
                				<td>Nom</td>
                				<td><input type="text" name="nomstruct" id="nomstruct" value="$struct->Nom" required></td>
                			</tr>
                			<tr>
                				<td>Adresse</td>
                				<td><input type="text" name="adrstruct" id="adrstruct" value="$struct->Adresse" required></td>
                			</tr>
                			<tr>
                				<td>Code Postal</td>
                				<td><input type="text" name="cpostalstruct" id="cpostalstruct" value="$struct->CodePostal" required></td>
                			</tr>
                			<tr>
                				<td>Ville</td>
                				<td><input type="text" name="villestruct" id="villestruct" value="$struct->Ville" required></td>
                			</tr>
                			<tr>
                				<td>Type</td>
                				<td><input type="text" name="vousetes" id="vousetes" value="$struct->Type" required></td>
                			</tr>
                			<tr>
                				<td>Site</td>
                				<td><input type="text" name="site" id="site" value="$site" required></td>
                			</tr>
                			<tr>
                				<td>Raison</td>
                				<td><input type="text" name="raisonstruct" id="raisonstruct" value="$struct->Raison" required></td>
                			</tr>
                		</tbody>
	                   </table>
                        <br>
                        <input type="hidden" value="$no" name="IdProjet" id="IdProjet">
                        <input type="hidden" value="$struct->IdStruct" name="IdStruct" id="IdStruct">
                        <button class="btn waves-light" type="submit" name="action">Modifier
                            <i class="material-icons right">send</i>
                        </button>
                        </form>
                    </div>
                    <div id="14c3" class="" style="display: none;">
                        <form method="POST" id="formRepresentant" action="$modificationRepresentant" autocomplete="off">
                        <table>
                		<thead>
                			<tr>
                				<th>Intitulé</th>
                				<th>Description</th>
                			</tr>
                		</thead>
                
                		<tbody>
                			<tr>
                				<td>Nom</td>
                				<td><input type="text" name="nomrpzlegal" id="nomrpzlegal" value="$rep->Nom" required></td>
                			</tr>
                			<tr>
                				<td>Prenom</td>
                				<td><input type="text" name="prenomrpzlegal" id="prenomrpzlegal" value="$rep->Prenom" required</td>
                			</tr>
                			<tr>
                				<td>Qualité</td>
                				<td><input type="text" name="qualite" id="qualite" value="$rep->Qualite" required></td>
                			</tr>
                		</tbody>
	                   </table>
                        <br>
                        <input type="hidden" value="$no" name="IdProjet" id="IdProjet">
                        <input type="hidden" value="$rep->IdRep" name="IdRep" id="IdRep">
                        <button class="btn waves-light" type="submit" name="action">Modifier
                            <i class="material-icons right">send</i>
                        </button>
                        </form>
                    </div>
                    <div id="14c4" class="" style="display: none;">
                        <form method="POST" id="formRepresentant" action="$modificationResponsable" autocomplete="off">
                        <table>
                		<thead>
                			<tr>
                				<th>Intitulé</th>
                				<th>Description</th>
                			</tr>
                		</thead>
                
                		<tbody>
                			<tr>
                				<td>Nom</td>
                				<td><input type="text" name="nomresplegal" id="nomresplegal" value="$resp->Nom" required></td>
                			</tr>
                			<tr>
                				<td>Prenom</td>
                				<td><input type="text" name="prenomresplegal" id="prenomresplegal" value="$resp->Prenom" required></td>
                			</tr>
                			<tr>
                				<td>Position</td>
                				<td><input type="text" name="position" id="position" value="$resp->Position" required></td>
                			</tr>
                			<tr>
                				<td>Adresse</td>
                				<td><input type="text" name="adrport" id="adrport" value="$resp->Adresse" required></td>
                			</tr>
                			<tr>
                				<td>Code Postal</td>
                				<td><input type="text" name="cpostalport" id="cpostalport" value="$resp->CodePostal" required></td>
                			</tr>
                			<tr>
                				<td>Ville</td>
                				<td><input type="text" name="villeport" id="villeport" value="$resp->Ville" required></td>
                			</tr>
                			<tr>
                				<td>Tel</td>
                				<td><input type="text" name="tel" id="tel" value="$resp->Tel" required></td>
                			</tr>
                            <tr>
                				<td>Courriel</td>
                				<td><input type="text" name="courriel" id="courriel" value="$resp->Courriel" required></td>
                			</tr>
                		</tbody>
	                   </table>
                        <br>
                        <input type="hidden" value="$no" name="IdProjet" id="IdProjet">
                        <input type="hidden" value="$resp->IdRes" name="IdResp" id="IdResp">
                        <button class="btn waves-light" type="submit" name="action">Modifier
                            <i class="material-icons right">send</i>
                        </button>
                        </form>
                    </div>
                    <div id="14c5" class="" style="display: none;">
                        <form method="POST" id="formSuivi" action="$modificationCofinanceur" autocomplete="off">
                        <div class="hoverable card">
                        <div class="card-content">
                        <div class="col s12">
                        <h5 >Co-financeur(s)</h5>
                        </div>
end;
        if (sizeof($cofin) == 0) {
            $res .= <<<end
                        <table>
                		<thead>
                			<tr>
                				<th></th>
                			</tr>
                		</thead>
                
                		<tbody>
                			<tr>
                				<td>Aucun co-financeur enrengistré</td>
                			</tr>
                		</tbody>
	                   </table>
                       </form> 
end;
        } else {
            $res .= <<<end

                        <table>
                		<thead>
                			<tr>
                				<th>Nom</th>
                				<th>Prenom</th>
                			</tr>
                		</thead>
                
                		<tbody>
end;
            $i = 0;
            foreach ($cofin as $co) {
                $res .= <<<end

                            <tr>
                				<td><input type="text" name="nomco$i" id="nomco$i" value="$co->Nom" required></td>
                				<td><input type="text" name="prenomco$i" id="prenomco$i" value="$co->Prenom" required></td>
                			</tr>
   
end;
                $i ++;
            }
            $res .= <<<end

                		</tbody>
	                   </table>
                        <br>
                        <input type="hidden" value="$no" name="IdProjet" id="IdProjet">
                        <button class="btn waves-light" type="submit" name="action">Modifier
                            <i class="material-icons right">send</i>
                        </button>
                        </form>
end;
        }
        $res .= <<<end

                        </div>
                       </div>

<!--///////////////////////////////////////////////////////////-->
                        <form method="POST" id="formSuivi" action="$modificationParrain" autocomplete="off">
                        <div class="hoverable card">
                        <div class="card-content">
                        <div class="col s12">
                        <h5 >Parrain</h5>
                        </div>
end;
        if (sizeof($parrain) == 0) {
            $res .= <<<end
                        <table>
                		<thead>
                			<tr>
                				<th></th>
                			</tr>
                		</thead>
                
                		<tbody>
                			<tr>
                				<td>Aucun parrain enrengistré</td>
                			</tr>
                		</tbody>
	                   </table>
                       </form>
end;
        } else {
            $res .= <<<end

                        <table>
                		<thead>
                			<tr>
                				<th>Nom</th>
                				<th>Prenom</th>
                			</tr>
                		</thead>
                
                		<tbody>
end;
            foreach ($parrain as $p) {
                $res .= <<<end

                            <tr>
                				<td><input type="text" name="nomparrain" id="nomparrain" value="$p->Nom" required></td>
                				<td><input type="text" name="prenomparrain" id="prenomparrain" value="$p->Prenom" required></td>
                			</tr>
   
end;
            }
            $res .= <<<end

                		</tbody>
	                   </table>
                        <br>
                        <input type="hidden" value="$no" name="IdProjet" id="IdProjet">
                        <button class="btn waves-light" type="submit" name="action">Modifier
                            <i class="material-icons right">send</i>
                        </button>
                        </form>
end;
        }
        $res .= <<<end

                        </div>
                       </div>
                    </div>
                    <div id="14c6" class="" style="display: none;">
                        <table>
end;
        if (sizeof($fichiers) == 0) {
            $res .= <<<end

                        <thead>
                			<tr>
                				<th></th>
                			</tr>
                		</thead>
            
                		<tbody>
                			<tr>
                				<td>Aucun fichier à télécharger</td>
                			</tr>
                		</tbody>
end;
        } else {
            $res .= <<<end

                        <thead>
                			<tr>
                				<th>Intitulé</th>
                				<th></th>
                			</tr>
                		</thead>
            
                		<tbody>
end;
            foreach ($fichiers as $f) {
                $folder = Variable::$dossierFichier;
                $folderSpecifique = Variable::$dossierSpecifique[0];
                if (self::endsWith($f, ".zip"))
                    $nomFichier = "-- Archive contenant tout les fichiers (format .zip) --";
                else
                    $nomFichier = $f;
                $res .= <<<end
                            <tr>
                				<td>$nomFichier</td>
                				<td><a href="$path/$folder/$dossier/$folderSpecifique/$f">Télécharger</a></td>
                			</tr>
end;
            }
            $res .= "
                        </tbody>";
        }
        
        $res .= <<<end

	                   </table>
                    </div>
                    <div id="suivi" class="active" style="display: block;">
                        <div class="hoverable card">
                        <div class="card-content">
                        <div class="col s12">
                        <h5>$titleSuivi</h5>
                        </div>
                        <form method="POST" id="formSuivi" action="$modificationSuivi" enctype="multipart/form-data" autocomplete="off">  
                        <table>
                		<thead>
                			<tr>
                				<th>Intitulé</th>
                				<th></th>
                			</tr>
                		</thead>
                
                		<tbody>
                            <tr>
                				<td>Décision</td>
                				<td><p>
                                      <label>
                                        <input type="checkbox" class="filled-in" id="decision" name="decision" $checked />
                                        <span>Cochez si validé</span>
                                      </label>
                                    </p>
                                </td>
                			</tr>
                            <tr>
                				<td>Montant accordé (en euros)</td>
                				<td><input type="text" id="montant" value="$suivi->Montant" name="montant" pattern="\d*" required /></td>
                			</tr>
                			<tr>
                				<td>$titleDateRep</td>
                				<td><input type="text" class="datepicker" id="dateRep" name="dateRep" $dateRep /></td>
                			</tr>
                            <tr>
                				<td>$titleDateEnvoiConv</td>
                				<td><input type="text" class="datepicker" id="dateEnvoiConv" name="dateEnvoiConv" $dateEnvoiConv /></td>
                			</tr>
                            <tr>
                				<td>$titleDateRecepConv</td>
                				<td><input type="text" class="datepicker" id="dateRecepConv" name="dateRecepConv" $dateRecepConv /></td>
                			</tr>
                            <tr>
                				<td>$titleDateRecepRecu</td>
                				<td><input type="text" class="datepicker" id="dateRecepRecu" name="dateRecepRecu" $dateRecepRecu /></td>
                			</tr>
                            <tr>
                				<td>$titleDateEnvoiCheque</td>
                				<td><input type="text" class="datepicker" id="dateEnvoiCheque" name="dateEnvoiCheque" $dateEnvoiCheque /></td>
                			</tr>
                            <tr>
                				<td>Observations éventuelles</td>
                				<td><textarea id="textarea1" class="materialize-textarea" id="observations" name="observations">$suivi->Observations</textarea></td>
                			</tr>
                            <input type="hidden" id="IdSuivi" name="IdSuivi" value="$suivi->IdSuivi" />
                            <input type="hidden" id="IdProjet" name="IdProjet" value="$no" />
                		</tbody>
	                   </table><br>
                       <button class="btn waves-effect waves-light" type="submit" name="action">Valider
                        <i class="material-icons right">send</i>
                      </button>
                      </form>
                      </div>
                      </div>

                      <div class="hoverable card">
                        <div class="card-content">
                        <div class="col s12">
                            <h5>Fichiers</h5>
                        </div>

                        <table>
end;
        if (sizeof($fichiersPerso) == 0) {
            $res .= <<<end

                        <thead>
                			<tr>
                				<th></th>
                			</tr>
                		</thead>
            
                		<tbody>
                			<tr>
                				<td>Aucun fichier à télécharger</td>
                			</tr>
                		</tbody>
end;
        } else {
            $res .= <<<end

                        <thead>
                			<tr>
                                <th></th>
                				<th>Intitulé</th>
                				<th></th>
                			</tr>
                		</thead>
            
                		<tbody>
end;
            foreach ($fichiersPerso as $f) {
                $folder = Variable::$dossierFichier;
                $folderSpecifique = Variable::$dossierSpecifique[1];
                $nomFichier = $f;
                $suppFichier = $app->urlFor("suppFichier", [
                    "no" => $no
                ]) . "?file=" . $f;
                $res .= <<<end
                            <tr>
                                <td><a class="waves-effect waves-light btn red" href="$suppFichier"><i class="material-icons">delete</i></a></td>
                				<td>$nomFichier</td>
                				<td><a href="$path/$folder/$dossier/$folderSpecifique/$f">Télécharger</a></td>
                			</tr>
end;
            }
            $res .= "
                        </tbody>";
        }
        
        $res .= <<<end

	                   </table>
                
                        <form method="POST" id="formFormulaire" action="$ajoutFichier" enctype="multipart/form-data">
                            <div class="col s12" id="fichierPerso">
                                <label for="fileToUpload" class="label-file">Ajouter un/plusieurs fichier(s)</label>
                                <input class="input-file" type="file" name="fileToUpload[]" id="fileToUpload" multiple required hidden>
                                <span id="nomFichier">- Aucun fichier sélectionné</span>
                            </div>
                            <input id="IdProjet" name="IdProjet" type="hidden" value="$no">
                            <button class="btn waves-light" type="submit" name="action">Valider
                                <i class="material-icons right">send</i>
                            </button>
                        </form>
    
    
                      </div>
                      </div>

                    </div>
                  </div>
                </div>
        <a class="waves-effect waves-light btn" href="$liste"><i class="material-icons left">arrow_back</i>Retour</a>
        </div>

end;
        $res .= Modal::genereModal("Modification de suivi") . "
<script>
$(document).ready(function() {";
        ;
        if (isset($_SESSION['message'])) {
            $msg = $_SESSION['message'];
            $res .= Modal::enclencher($msg);
            $_SESSION['message'] = null;
        }
        $res .= "});
</script>
";
        return $res;
    }

    /**
     * Méthode générant le code HTML de la page de recherche projet
     *
     * @param int $tab
     *            nom du projet
     * @return string code HTML de la page de recherche projet
     */
    private function recherche($tab)
    {
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        
        $redirection = $app->urlFor("postRedirection");
        $liste = $app->urlFor("listeFormulaires");
        
        $struct = Structure::getByName($tab);
        
        $res = <<<end
        
            <div class="container">
                <h3>Résultat de la recherche pour "$tab"</h3>
                
                
end;
        if (sizeof($struct) == 0) {
            $res .= <<<end
                <div class="col s12">
                        <h5>Aucun résultat</h5>
                        <a class="waves-effect waves-light btn" href="$liste"><i class="material-icons left">arrow_back</i>Retour</a>
                </div>
end;
        } else {
            foreach ($struct as $s) {
                $p = Projet::getByStructure($s->IdStruct);
                $rep = Representant::getById($p->IdRes);
                $resp = Responsable::getById($p->IdRep);
                $acceder = $app->urlFor("projet", [
                    'no' => $p->IdProjet
                ]);
                $supprimer = $app->urlFor("postSuppressionFormulaire");
                $res .= <<<end
                
              <!-- Modal Structure -->
              <div id="modal$p->IdProjet" class="modal">
                <div class="modal-content">
                  <h4>Suppression de projet</h4>
                  <p>Supprimer le projet de $s->Nom est un acte irréversible. Êtes-vous sûr de vouloir continuer ?</p>
                </div>
                <div class="modal-footer">
                  <form methode="POST" action="$supprimer">
                    <a href="" class="modal-action modal-close waves-effect waves-green btn-flat">Annuler</a>
                    <input id="IdProjet" name="IdProjet" type="hidden" value="$p->IdProjet">
                    <input type="submit" formmethod="post" value="Confirmer" class="modal-action modal-close waves-effect waves-green btn-flat">
                  </form>
                </div>
              </div>
              
                    <div class="row">
    <div class="col s12">
      <div class="hoverable card">
        <div class="card-content">
          <span class="card-title">$s->Nom</span>
          <label>Représentant : $rep->Nom $rep->Prenom - Responsable : $resp->Nom $resp->Prenom</label>
          <p>$p->Expose</p>
        </div>
        <div class="card-action">
          <a href="$acceder">Accéder</a>
          <a class="modal-trigger" href="#modal$p->IdProjet">Supprimer</a>
          
        </div>
      </div>
    </div>
end;
            }
            $res .= <<<end
            
    <a class="waves-effect waves-light btn" href="$liste"><i class="material-icons left">arrow_back</i>Retour</a>
  </div>
  
  
end;
        }
        
        $res .= <<<end
        
            </div>
            
end;
        return $res;
    }

    /**
     * Méthode générant le code HTML de la page de gestion de compte
     * @return string
     */
    private function gestionCompte()
    {
        $app = \Slim\Slim::getInstance();
        $user = User::getById($app->getEncryptedCookie("user"));
        //On vérifie si l'utilisateur est un administrateur ou non
        if ($user->droit == "0")
            return self::gestionCompteNormal();
        else
            return self::gestionCompteAdmin();
    }

    /**
     * Méthode générant le code HTML de la page de gestion de compte pour un compte administratueur
     * @return string
     */
    private function gestionCompteAdmin()
    {
        $app = \Slim\Slim::getInstance();
        
        $retour = $app->urlFor("listeFormulaires");
        $modifCompte = $app->urlFor("postModifCompte");
        $suppCompte = $app->urlFor("postSuppCompte");
        $ajoutCompte = $app->urlFor("creationCompte");
        
        $user = User::getById($app->getEncryptedCookie("user"));
        $users = User::getAll();
        $admin = User::getSuperAdmin();
        
        $res = <<<end
        <div class="container">
            <h3>Menu de gestion des comptes du back-office</h3>
            <div class="col s1 offset-s6"><span><i class="material-icons tiny">person_pin</i> Vous</span></div>
            <div class="col s1 offset-s6"><span><i class="material-icons tiny">people</i> Super Administrateur</span></div>
            <div class="col s1 offset-s6"><span><i class="material-icons tiny">person</i> Administrateur</span></div>
            <div class="col s1 offset-s6"><span><i class="material-icons tiny">perm_identity</i> Utilisateur normal</span></div>
        <div class="card-panel hoverable">

            <ul class="collapsible">
end;
        $i = 0;
        foreach ($users as $u) {
            $icon = $u->droit;
            $mail = $u->login;
            
            if ($icon == "2") {
                if ($u->login != $user->login)
                    $icon = "people";
                else
                    $icon = "person_pin";
                $selection = '<option value="2" selected>Super Administrateur</option>';
            }
            if ($icon == "1") {
                if ($u->login != $user->login)
                    $icon = "person";
                else
                    $icon = "person_pin";
                $selection = '<option value="1" selected>Administrateur</option>
                          <option value="0">Normal</option>';
            }
            if ($icon == "0") {
                $icon = "perm_identity";
                $selection = '<option value="1">Administrateur</option>
                          <option value="0" selected>Normal</option>';
            }
            
            $mdpModif1 = 'mdpModif' . $i . "w1";
            $mdpModif2 = 'mdpModif' . $i . "w2";
            
            $res .= <<<end

                <li>
                  <div class="collapsible-header"><i class="material-icons">$icon</i>$u->login</div>
                  <div class="collapsible-body">
                    <form id="formModifCompte$i" name="formModifCompte$i" action="$modifCompte" method="POST" autocomplete="off">
                        <div class="input-field col s12">
                        <select name="selectDroit">
                          $selection
                        </select>
                        <label>Selection des droits</label>
                      </div>
                        <div class="card-panel hoverable">
                            <label>
                                <input id="cbmdp$i" name="checkbox" type="checkbox" class="filled-in"/>
                                <span>Cochez si vous voulez changer le mot de passe</span>
                             </label>

                            <div class="input-field col s12">
                                <i class="material-icons prefix">https</i>
                                <input disabled type="password" minlength="6" id="$mdpModif1" name="$mdpModif1"><br>
                                <label for="$mdpModif1">Nouveau mot de passe</label>
                            </div>
                            
                            <div class="input-field col s12">
                                <i class="material-icons prefix">https</i>
                                <input disabled type="password" id="$mdpModif2" name="$mdpModif2"><br>
                                <label for="$mdpModif2">Répétez le mot de passe</label>
                                
                            </div>
            
                        </div>
                        <input type="hidden" name="numGestion" id="numGestion$i" value="$i">
                        <input type="hidden" name="idUser" id="idUser$i" value="$u->login">

end;
            if ($u->login != $user->login && $u->droit != "2")
                $res .= '                        <a class="btn red modal-trigger" href="#modalw' . $i . '"><i class="material-icons left">delete</i>Supprimer</a>
';
            if ($user->droit == "2" || ($u->droit != "2" && $u->login != $admin->login))
                $res .= '                           <button class="btn" type="submit" name="action">Modifier
                            <i class="material-icons right">send</i>
                          </button>
';
            $res .= <<<end
                        
                    </form>
                  </div>
                </li>
end;
            if ($u->login != $user->login) {
                $res .= <<<end

                <!-- Modal Structure -->
              <div id="modalw$i" class="modal">
                <div class="modal-content">
                  <h4>Suppression d'un utilisateur</h4>
                  <p>Supprimer l'utilisateur $mail est un acte irréversible. Êtes-vous sûr de vouloir continuer ?</p>
                </div>
                <div class="modal-footer">
                  <form methode="POST" action="$suppCompte">
                    <a href="" class="modal-action modal-close waves-effect waves-green btn-flat">Annuler</a>
                    <input type="hidden" name="idUserModal" id="idUserModal$i" value="$u->login">
                    <input type="submit" formmethod="post" value="Confirmer" class="modal-action modal-close waves-effect waves-green btn-flat">
                  </form>
                </div>
              </div>                


end;
            }
            $res .= <<<end

<script>
$('#formModifCompte$i').submit(function() {
    if($("#cbmdp$i").is(":checked")){
    let id1 = $('#$mdpModif1').val(); //if #id1 is input element change from .text() to .val() 
    let id2 = $('#$mdpModif2').val(); //if #id2 is input element change from .text() to .val()
    if (!(/\d/.test(id1)) || !(/[a-z]/.test(id1)) || !(/[A-Z]/.test(id1))) {
end;
            $msg = "Le mot de passe doit contenir au moins une lettre majuscule et minuscule ainsi qu'un chiffre.";
            $res .= Modal::enclencher($msg);
            $res .= <<<end
        return false;
    }

    if (id1 != id2) {
end;
            $msg = "Les deux mot de passe ne correspondent pas. Veuillez réessayer.";
            $res .= Modal::enclencher($msg);
            $res .= <<<end
        return false;
    }
}    
    return true;
});
</script>

<script>
$("#cbmdp$i").click(function() {
    if($(this).is(":checked")){
        $("#$mdpModif1").prop('disabled', false);
        $("#$mdpModif2").prop('disabled', false);
        $("#$mdpModif1").prop('required', true);
        $("#$mdpModif2").prop('required', true);
    }else{
        $("#$mdpModif1").prop('disabled', true);
        $("#$mdpModif2").prop('disabled', true);
        $("#$mdpModif1").prop('required', false);
        $("#$mdpModif2").prop('required', false);
        $("#$mdpModif1").val('');
        $("#$mdpModif2").val('');
    }
});
</script>
end;
            $i ++;
        }
        $res .= <<<end
              </ul>
end;
        $res .= Modal::genereModal() . "
<script>
$(document).ready(function() {";
        
        if (isset($_SESSION['message'])) {
            $msg = $_SESSION['message'];
            $res .= Modal::enclencher($msg);
            $_SESSION['message'] = null;
        }
        $res .= <<<end
});
</script>

            <div class="col s1 offset-s6"><a class="waves-effect waves-light btn green" href="$ajoutCompte"><i class="material-icons">person_add</i></a></div>

            </div>

            <div class="row">
            <a class="waves-effect waves-light btn" href="$retour"><i class="material-icons left">arrow_back</i>Retour</a>
            </div>
        </div>
end;
        return $res;
    }

    /**
     * Méthode générant le code HTML de la page de gestion de compte pour un compte normal
     * @return string
     */
    private function gestionCompteNormal()
    {
        $app = \Slim\Slim::getInstance();
        
        $retour = $app->urlFor("listeFormulaires");
        $modifCompte = $app->urlFor("postModifCompte");
        
        $user = User::getById($app->getEncryptedCookie("user"));
        
        $res = <<<end
        <div class="container">
            <h3>Menu de gestion de compte</h3>
            <div class="card-panel hoverable">

                <form id="formModifCompte" name="formModifCompte" action="$modifCompte" method="POST" autocomplete="off">

                    <div class="input-field col s12">
                        <i class="material-icons prefix">account_circle</i>
                        <input type="email" id="login" name="login" class="validate" value="$user->login" disabled required><br>
                        <label for="login">Login (inchangeable)</label>
                    </div>
    
                    <div class="input-field col s12">
                        <i class="material-icons prefix">account_balance</i>
                        <input type="text" id="orgInscr" name="orgInscr" value="Compte normal" disabled required><br>
                        <input type="hidden" id="selectDroit" name="selectDroit" value="0">
                        <label for="orgInscr">Statut (inchangeable)</label>    
                    </div>

                    <input type="hidden" id="checkbox" name="checkbox" value="salut">
                    <input type="hidden" id="numGestion" name="numGestion" value="0">
                    <input type="hidden" id="idUser" name="idUser" value="$user->login">

                    <div class="input-field col s12">
                        <i class="material-icons prefix">https</i>
                        <input type="password" minlength="6" id="mdpModif0w1" name="mdpModif0w1" required><br>
                        <label for="mdpModif0w1">Mot de passe</label>
                    </div>
        
                    <div class="input-field col s12">
                        <i class="material-icons prefix">https</i>
                        <input type="password" id="mdpModif0w2" name="mdpModif0w2" required><br>
                        <label for="mdpModif0w2">Répétez le mot de passe</label>   
                    </div>
                      <button class="btn" type="submit" name="action">Modifier
                        <i class="material-icons right">send</i>
                      </button>
                </form>
            </div>

            <a class="waves-effect waves-light btn" href="$retour"><i class="material-icons left">arrow_back</i>Retour</a>

        </div>
end;
        $res .= <<<end
        
<script>
$('#formModifCompte').submit(function() {
    let id1 = $('#mdpModif0w1').val(); //if #id1 is input element change from .text() to .val()
    let id2 = $('#mdpModif0w2').val(); //if #id2 is input element change from .text() to .val()
    if (!(/\d/.test(id1)) || !(/[a-z]/.test(id1)) || !(/[A-Z]/.test(id1))) {
end;
        $msg = "Le mot de passe doit contenir au moins une lettre majuscule et minuscule ainsi qu'un chiffre.";
        $res .= Modal::enclencher($msg);
        $res .= <<<end
        return false;
    }
    
    if (id1 != id2) {
end;
        $msg = "Les deux mot de passe ne correspondent pas. Veuillez réessayer.";
        $res .= Modal::enclencher($msg);
        $res .= <<<end
        return false;
    }
    return true;
});
</script>
end;
        $res .= Modal::genereModal() . "
<script>
$(document).ready(function() {";
        
        if (isset($_SESSION['message'])) {
            $msg = $_SESSION['message'];
            $res .= Modal::enclencher($msg);
            $_SESSION['message'] = null;
        }
        $res .= <<<end
});
</script>
end;
        return $res;
    }

    /**
     * Méthode générant le code HTML de la page de création de compte
     * @return string
     */
    private function creationCompte()
    {
        $app = \Slim\Slim::getInstance();
        
        $creation = $app->urlFor("postCreationCompte");
        $retour = $app->urlFor("gestionCompte");
        
        $res = <<<end
        <div class="container">
            <h3>Création d'un nouveau compte</h3>
            <div class="card-panel hoverable">
                <form id="formInscr" class="col s12" method="POST" action="$creation" autocomplete="off">
            
                    <div class="input-field col s12">
                        <i class="material-icons prefix">account_circle</i>
                        <input type="email" id="loginInscr" name="loginInscr" class="validate" required><br>
                        <label for="loginInscr">Login</label>
                        <span class="helper-text" data-error="Il faut rentrer une adresse mail valide" ></span>
                    </div>
        
                    <div class="input-field col s12">
                        <i class="material-icons prefix">https</i>
                        <input type="password" minlength="6" id="mdpInscr" name="mdpInscr" required><br>
                        <label for="mdpInscr">Mot de passe</label>
                    </div>
        
                    <div class="input-field col s12">
                        <i class="material-icons prefix">https</i>
                        <input type="password" id="mdpInscr2" name="mdpInscr2" required><br>
                        <label for="mdpInscr2">Répétez le mot de passe</label>
        
                    </div>
        
                    <div class="input-field col s12">
                        <select name="selectStatut">
                          <option value="1">Administrateur</option>
                          <option value="0" selected>Normal</option>
                        </select>
                        <label>Selection des droits.</label>
                    </div>

                    <button class="btn" type="submit" name="action">Créer
                        <i class="material-icons right">send</i>
                    </button>

            </div>
                    <a class="waves-effect waves-light btn" href="$retour"><i class="material-icons left">arrow_back</i>Retour</a>
                </form>
        </div>
        
end;
        
        $res .= Modal::genereModal() . "<script>
$(document).ready(function() {";
        
        if (isset($_SESSION['message'])) {
            $msg = $_SESSION['message'];
            $res .= Modal::enclencher($msg);
            $_SESSION['message'] = null;
        }
        $res .= <<<end
});
$('#formInscr').submit(function() {
    let id1 = $('#mdpInscr').val(); //if #id1 is input element change from .text() to .val() 
    let id2 = $('#mdpInscr2').val(); //if #id2 is input element change from .text() to .val()
    if (!(/\d/.test(id1)) || !(/[a-z]/.test(id1)) || !(/[A-Z]/.test(id1))) {
end;
        $msg = "Le mot de passe doit contenir au moins une lettre majuscule et minuscule ainsi qu'un chiffre.";
        $res .= Modal::enclencher($msg);
        $res .= <<<end
        return false;
    }

    if (id1 != id2) {
end;
        $msg = "Les deux mot de passe ne correspondent pas. Veuillez réessayer.";
        $res .= Modal::enclencher($msg);
        $res .= <<<end
        return false;
    }    
    return true;
});
</script>
end;
        return $res;
    }

    /**
     * Méthode permettant la génération du code HTML pour un sélecteur
     * @param string $name nom du sélecteur
     * @param number $booleen valeur du sélecteur à attribuer
     * @return string code HTML du sélecteur
     */
    function generateSelectOuiNon($name, $booleen = 0)
    {
        $select = '<div class="input-field col s12">
                        <select name="' . $name . '" id="' . $name . '">
                          ';
        if ($booleen == 0)
            $select .= '<option value="0" selected>Non</option>
                          <option value="1">Oui</option>';
        else
            $select .= '<option value="0">Non</option>
                          <option value="1" selected>Oui</option>
';
        
        $select .= '                        </select>
                      </div>';
        return $select;
    }

    /**
     * Méthode permettant de savoir si une chaîne est situé à la fin d'une autre
     *
     * @param string $haystack
     *            chaîne principale où l'on doit chercher une chaîne à la fin
     * @param string $needle
     *            chaîne de recherche
     * @return boolean indicatif de réussite
     */
    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        
        return $length === 0 || (substr($haystack, - $length) === $needle);
    }
}