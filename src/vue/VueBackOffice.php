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

class VueBackOffice
{

    const AFF_INDEX = 0;

    const AFF_FORMULAIRE = 1;

    const AFF_PROJET = 2;

    const AFF_RECHERCHE = 3;

    public function render($selecteur, $tab = null)
    {
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
        }
        return $content;
    }

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

    private function formulaire()
    {
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        
        $testQuery = array(0,1);
        $testValidate = array(0,1,2);
        
        if (isset($_GET['query'])){
            if(in_array($_GET['query'], $testQuery)){
                $query = $_GET['query'];
            } else $query = 0;
        } else $query = 0;
        
        if (isset($_GET['validate'])){
            if(in_array($_GET['validate'], $testValidate)){
                $validate = $_GET['validate'];
            } else $validate = 0;
        } else $validate = 0;
        
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
                <h3>Liste des projets</h3>
   
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
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;'. $changementTri . '?query=' . $query . '&validate=0&#39;" checked />';
        else
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;'. $changementTri . '?query=' . $query . '&validate=0&#39;" />';
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
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;'. $changementTri . '?query=' . $query . '&validate=1&#39;" checked />';
        else
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;'. $changementTri . '?query=' . $query . '&validate=1&#39;" />';
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
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;'. $changementTri . '?query=' . $query . '&validate=2&#39;" checked />';
        else
            $res .= '<input name="group1" type="radio" onclick="window.location.href=&#39;'. $changementTri . '?query=' . $query . '&validate=2&#39;" />';
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
            if($suivi->Chrono != "0")$titre = $struct->Nom." - ".$date." - <span class='green-text text-accent-4'>n° Chrono : ".$suivi->Chrono."</span>";
            else $valide = $titre = $struct->Nom." - ".$date;
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
            $list = scandir(Variable::$path . "\\" . Variable::$dossierFichier);
            
            foreach ($list as $l) {
                $id = explode("_", $l)[0];
                if ($no == $id) {
                    
                    $dossier = $l;
                    
                    // Dossier avec tout les fichiers recherchés
                    $list2 = scandir(Variable::$path . "\\" . Variable::$dossierFichier . "\\" . $l . "\\" . $folderSpecifique);
                    
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
            $list = scandir(Variable::$path . "\\" . Variable::$dossierFichier);
            
            foreach ($list as $l) {
                $id = explode("_", $l)[0];
                if ($no == $id) {
                    
                    // Dossier avec tout les fichiers recherchés
                    $list2 = scandir(Variable::$path . "\\" . Variable::$dossierFichier . "\\" . $l . "\\" . $folderSpecifique);
                    
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
        if ($suivi->Chrono != null)
            $titleSuivi = "Suivi - <strong>n° chrono : $suivi->Chrono</strong>";
        else
            $titleSuivi = "Suivi";
        
        // Transformation des booléens dans la partie du projet
        $interetG = Formulaire::transformerBooleen($proj->InteretGeneral);
        $mecenat = Formulaire::transformerBooleen($proj->Mecenat);
        $fiscal = Formulaire::transformerBooleen($proj->Fiscal);
        
        if ($suivi->Chrono != 0)
            $checked = 'checked="checked"';
        else
            $checked = '';
        
        if (isset($proj->Valorisation))
            $valor = $proj->Valorisation;
        else
            $valor = "<label>Aucune valorisation</label>";
        
        if (isset($struct->Site))
            $site = $struct->Site;
        else
            $site = "<label>Aucun site enrengistré</label>";
        
        $cofin = Implique::getCoFinanceur($no);
        $parrain = Implique::getParrain($no);
        
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
                				<td>$proj->Expose</td>
                			</tr>
                			<tr>
                				<td>Date de début du projet</td>
                				<td>$dateDeb</td>
                			</tr>
                			<tr>
                				<td>Durée (en mois)</td>
                				<td>$proj->Duree</td>
                			</tr>
                			<tr>
                				<td>Lieu</td>
                				<td>$proj->Lieu</td>
                			</tr>
                			<tr>
                				<td>Montant de l'aide financière sollicitée (en euros)</td>
                				<td>$proj->Aide</td>
                			</tr>
                			<tr>
                				<td>Budget prévisionnel global du projet (en euros)</td>
                				<td>$proj->Budget</td>
                			</tr>
                			<tr>
                				<td>Fins utilisé du montant demandé à Demathieu Bard</td>
                				<td>$proj->Fin</td>
                			</tr>
                            <tr>
                				<td>Le projet est-il d'intérêt général ?</td>
                				<td>$interetG</td>
                			</tr>
                            <tr>
                				<td>Domaine principal du projet</td> 
                				<td>$proj->Domaine</td>
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
                    </div>
                    <div id="14c2" class="" style="display: none;">
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
                				<td>$struct->Nom</td>
                			</tr>
                			<tr>
                				<td>Adresse</td>
                				<td>$struct->Adresse</td>
                			</tr>
                			<tr>
                				<td>Code Postal</td>
                				<td>$struct->CodePostal</td>
                			</tr>
                			<tr>
                				<td>Ville</td>
                				<td>$struct->Ville</td>
                			</tr>
                			<tr>
                				<td>Type</td>
                				<td>$struct->Type</td>
                			</tr>
                			<tr>
                				<td>Site</td>
                				<td>$site</td>
                			</tr>
                			<tr>
                				<td>Raison</td>
                				<td>$struct->Raison</td>
                			</tr>
                		</tbody>
	                   </table>
                    </div>
                    <div id="14c3" class="" style="display: none;">
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
                				<td>$rep->Nom</td>
                			</tr>
                			<tr>
                				<td>Prenom</td>
                				<td>$rep->Prenom</td>
                			</tr>
                			<tr>
                				<td>Qualité</td>
                				<td>$rep->Qualite</td>
                			</tr>
                		</tbody>
	                   </table>
                    </div>
                    <div id="14c4" class="" style="display: none;">
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
                				<td>$resp->Nom</td>
                			</tr>
                			<tr>
                				<td>Prenom</td>
                				<td>$resp->Prenom</td>
                			</tr>
                			<tr>
                				<td>Position</td>
                				<td>$resp->Position</td>
                			</tr>
                			<tr>
                				<td>Adresse</td>
                				<td>$resp->Adresse</td>
                			</tr>
                			<tr>
                				<td>Code Postal</td>
                				<td>$resp->CodePostal</td>
                			</tr>
                			<tr>
                				<td>Ville</td>
                				<td>$resp->Ville</td>
                			</tr>
                			<tr>
                				<td>Tel</td>
                				<td>$resp->Tel</td>
                			</tr>
                            <tr>
                				<td>Courriel</td>
                				<td>$resp->Courriel</td>
                			</tr>
                		</tbody>
	                   </table>
                    </div>
                    <div id="14c5" class="" style="display: none;">
                        <div class="hoverable card">
                        <div class="card-content">
                        <div class="col s12">
                        <h5 >Co-fondateurs</h5>
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
                				<td>Aucun co-fondateur enrengistré</td>
                			</tr>
                		</tbody>
	                   </table>
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
            foreach ($cofin as $co) {
                $res .= <<<end

                            <tr>
                				<td>$co->Nom</td>
                				<td>$co->Prenom</td>
                			</tr>
   
end;
            }
            $res .= <<<end

                		</tbody>
	                   </table>
end;
        }
        $res .= <<<end

                        </div>
                       </div>

<!--///////////////////////////////////////////////////////////-->

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
                				<td>$p->Nom</td>
                				<td>$p->Prenom</td>
                			</tr>
   
end;
            }
            $res .= <<<end

                		</tbody>
	                   </table>
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
                        <form method="POST" id="formSuivi" action="$modificationSuivi" enctype="multipart/form-data">  
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

    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        
        return $length === 0 || (substr($haystack, - $length) === $needle);
    }
}