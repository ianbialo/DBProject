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
        $listeProj = Projet::getall();
        $res = <<<end
            <div class="container">
                <h3>Liste des projets</h3>
                
end;
        foreach($listeProj as $p){
            $struct  = Structure::getById($p->IdStruct);
            $rep = Representant::getById($p->IdStruct);
            $resp = Responsable::getById($p->IdStruct);
            $acceder = $app->urlFor("projet",['no'=>$p->IdProjet]);
            $res .= <<<end
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
          <a href="#">Supprimer</a>
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
        
    private function projet($no){
        $proj = Projet::getById($no);
        $struct  = Structure::getById($proj->IdStruct);
        $rep = Representant::getById($proj->IdStruct);
        $resp = Responsable::getById($proj->IdStruct);
        $date = Formulaire::transformerDate($proj->DateDep);
        $cofin = Implique::getCoFinanceur($no);
        //Fixer le bug tmtc
        $res = <<<end
        <div class="container row">
                <div class="card hoverable">
                  <div class="card-content">
                    <div class="col s6">
                        <h5>$struct->Nom</h5>
                    </div>
                    <div class="col s6">
                        <h5>Date de création : $date</h5>
                    </div>
                  </div>
                  <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width">
                      <li class="tab"><a class="active" href="#14c1">Projet</a></li>
                      <li class="tab"><a href="#14c2" class="">Structure</a></li>
                      <li class="tab"><a href="#14c3" class="">Représentant</a></li>
                      <li class="tab"><a href="#14c4" class="">Responsable</a></li>
                      <li class="tab"><a href="#14c5" class="">Impliqué(s)</a></li>
                    <div class="indicator"></div></ul>
                  </div>
                  <div class="card-content grey lighten-4">
                    <div id="14c1" class="active" style="display: block;">
                        
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
                        <table>
                		<thead>
                			<tr>
                				<th>Nom</th>
                				<th>Prenom</th>
                			</tr>
                		</thead>
                
                		<tbody>
end;
        foreach ($cofin as $co){
            $res .= <<<end

                            <tr>
                				<th>$co->Nom</th>
                				<th>$co->Prenom</th>
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
                    </div>
                  </div>
                </div>
        <a class="waves-effect waves-light btn" href="#"><i class="material-icons left">arrow_back</i>Quitter</a>
        </div>
end;
        return $res;
    }
}