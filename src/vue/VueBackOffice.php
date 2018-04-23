<?php
namespace dbproject\vue;

use dbproject\conf\Formulaire;
use dbproject\modele\Projet;
use dbproject\modele\Structure;
use dbproject\modele\Representant;
use dbproject\modele\Responsable;
use dbproject\modele\Implique;

class VueBackOffice
{

    const AFF_INDEX = 0;

    const AFF_FORMULAIRE = 1;

    const AFF_PROJET = 2;

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
        }
        return VuePageHTMLBackOffice::header() . $content . VuePageHTMLBackOffice::getFooter();
    }

    private function index()
    {
        $app = \Slim\Slim::getInstance();
        return <<<end
        <h1>Dépôt d’une demande de partenariat / sponsoring / mécénat</h1>
end;
    }

    private function formulaire()
    {
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        
        $app = \Slim\Slim::getInstance();
        $listeProj = Projet::getall();
        $res = <<<end
            <div class="container">

                <a href="$path/uploads/3_unix_23_04_2018_15_16_07/Suivi stage.docx">Slt</a>
                <h3>Liste des projets</h3>
                
end;
        foreach ($listeProj as $p) {
            $struct = Structure::getById($p->IdStruct);
            $rep = Representant::getById($p->IdStruct);
            $resp = Responsable::getById($p->IdStruct);
            $acceder = $app->urlFor("projet", [
                'no' => $p->IdProjet
            ]);
            $supprimer = $app->urlFor("postSuppressionFormulaire");
            $res .= <<<end

              <!-- Modal Structure -->
              <div id="modal$p->IdProjet" class="modal">
                <div class="modal-content">
                  <h4>Modal Header</h4>
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
          <span class="card-title">$struct->Nom</span>
          <label>Représentant : $rep->Nom $rep->Prenom - Responsable : $resp->Nom $resp->Prenom</label>
          <p>$p->Expose</p>
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
        
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        $list = scandir("C:\Users\ibialo\Documents\GIT\DBProject\uploads");
        foreach ($list as $l){
            echo $l;
        }
        
        $proj = Projet::getById($no);
        $struct = Structure::getById($proj->IdStruct);
        $rep = Representant::getById($proj->IdStruct);
        $resp = Responsable::getById($proj->IdStruct);
        
        $dateDep = Formulaire::transformerDate($proj->DateDep);
        $dateDeb = Formulaire::transformerDate($proj->DateDeb);
        $interetG = Formulaire::transformerBooleen($proj->InteretGeneral);
        $mecenat = Formulaire::transformerBooleen($proj->Mecenat);
        $fiscal = Formulaire::transformerBooleen($proj->Fiscal);
        if(isset($proj->Valorisation)) $valor = $proj->Valorisation;
        else $valor = "<label>Aucun</label>";
        
        $cofin = Implique::getCoFinanceur($no);
        $parrain = Implique::getParrain($no);
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
                				<td>Possibilité d'établir une convention de Mécénat</td>
                				<td>$mecenat</td>
                			</tr>
                            <tr>
                				<td>Possibilité d'établir un reçu fiscal (cerfa n°11580*03)</td>
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
                				<td>$struct->Site</td>
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

                			<tr>
                				<td></td>
                				<td></td>
                			</tr>
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

                			<tr>
                				<td></td>
                				<td></td>
                			</tr>
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
                  </div>
                </div>
        <a class="waves-effect waves-light btn" href="$liste"><i class="material-icons left">arrow_back</i>Quitter</a>
        </div>
end;
        return $res;
    }
}