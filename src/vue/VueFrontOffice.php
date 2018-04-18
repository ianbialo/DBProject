<?php
namespace dbproject\vue;

class VueFrontOffice
{

    const AFF_INDEX = 0;

    const AFF_OK = 1;

    public function render($selecteur, $tab = null)
    {
        switch ($selecteur) {
            case VueFrontOffice::AFF_INDEX:
                $content = $this->index();
                break;
            case VueFrontOffice::AFF_OK:
                $content = $this->succes();
                break;
        }
        return VuePageHTMLFrontOffice::header() . $content . VuePageHTMLFrontOffice::getFooter();
    }

    private function index()
    {
        $app = \Slim\Slim::getInstance();
        $val = $app->urlFor("postFormulaire");
        return <<<end
        <h1>Dépôt d’une demande de partenariat / sponsoring / mécénat</h1>
            <form method="POST" action="$val" enctype="multipart/form-data">

                <h2>Structure</h2>
                <div class="info">
                    <label>Nom de la structure demanderesse *</label>
                    <input type="text" id="nomstruct" name="nomstruct" maxlength="90" autofocus required>
                </div>
                <div class="info">
                    <label>Adresse de la structure demanderesse *</label>
                    <input type="text" class="grandeTaille" id="adrstruct" name="adrstruct" placeholder="Adresse" maxlength="80" required>
                    <div id="in">
                        <input type="number" id="cpostalstruct" name="cpostalstruct" placeholder="Code Postal" max="99999" required>
                        <input type="text" id="villestruct" name="villestruct" placeholder="Ville" maxlength="60" required>
                    </div>
                </div>
                <div class="info">
                    <label>Raison d'être de la structure demanderesse *</label>
                    <textarea rows="3" cols="50" style="resize:none" id="raisontruct" name="raisonstruct" required></textarea>
                </div>
                <div id="selecteur" class="info">
                    <label>Vous êtes : *</label>
                    <select id="vousetes" name="vousetes">
                        <option value="Une association">Une association</option>
                        <option value="Un particulier">Un particulier</option>
                        <option value="Une collectivité / organisme public">Une collectivité / organisme public</option>
                        <option value="Une entreprise commerciale">Une entreprise commerciale</option>
                        <option value="Une entreprise à but non lucratif">Une entreprise à but non lucratif</option>
                        <option value="Une institution">Une institution</option>
                        <option value="Un organisme de solidarité international">Un organisme de solidarité international</option>
                        <option value="0">Autre...</option>
                    </select>
                    <div id="divautre">
                        <label>Veuillez préciser : *</label>
                        <input type="text" id="autre" name="autre">
                    </div>
                </div>
                <div class="info">
                    <label>Site internet</label>
                    <input type="url" id="site" maxlength="60" name="site">
                </div>
                
            
                <h2>Personnel</h2>
                <div class="info">
                    <label>Nom et prénom du représentant légal (qui signera la convention)*</label>
                    <div id="in">
                        <input type="text" id="nomrzplegal" name="nomrzplegal" placeholder="Nom" maxlength="30" required>
                        <input type="text" id="prenomrzplegal" name="prenomrzplegal" placeholder="Prenom" maxlength="30" required>
                    </div>
                </div>
                <div class="info">
                    <label>Qualité du signataire *</label>
                    <input type="text" id="qualite" name="qualite" maxlength="60" required>
                </div>
                <div class="info">
                    <label>Nom et prénom du responsable du projet *</label>
                    <div id="in">
                        <input type="text" id="nomresplegal" name="nomresplegal" placeholder="Nom" maxlength="30" required>
                        <input type="text" id="prenomresplegal" name="prenomresplegal" placeholder="Prenom" maxlength="30" required>
                    </div>
                </div>
                <div class="info">
                    <label>Position dans la structure *</label>
                    <input type="text" id="position" name="position" maxlength="60" required>
                </div>
                <div class="info">
                    <label>Adresse du porteur du projet *</label>
                    <input type="text" class="grandeTaille" id="adrport" name="adrport" placeholder="Adresse" maxlength="80" required>
                    <div id="in">
                    <input type="number" id="cpostalport" name="cpostalport" placeholder="Code Postal" max="99999" required>
                    <input type="text" id="villeport" name="villeport" placeholder="Ville" maxlength="60" required></div>
                </div>
                <div class="info">
                    <label>Telephone *</label>
                    <input type="text" id="tel" name="tel" pattern="[0-9]{9,13}" required>
                </div>
                <div class="info">
                    <label>Courriel *</label>
                    <input type="email" id="courriel" name="courriel" maxlength="60" required>
                </div>
                

                <h2>Projet</h2>
                <div class="info">
                    <label>Exposé synthétique du projet ou des actions à soutenir *</label>
                    <textarea rows="5" cols="50" style="resize:none" id="expose" name="expose" required></textarea>
                </div>
                <div class="info">
                    <label>Documents de présentation éventuels (flyer, affiche…) </label>
                    <input type="file" name="fileToUpload" id="fileToUpload">
                </div>
                <div class="info">
                    <label>Date de début du projet *</label>
                    <input type="date" id="datedeb" name="datedeb" required>
                </div>
                <div class="info">
                    <label>Durée du projet (en mois) *</label>
                    <input type="number" id="duree" name="duree" required>
                </div>
                <div class="info">
                    <label>Lieu du projet *</label>
                    <input type="text" id="lieu" name="lieu" maxlength="90" required>
                </div>
                <div class="info">
                    <label>Montant de l'aide financière sollicitée (en euros) *</label>
                    <input type="number" id="aide" name="aide" required>
                </div>
                <div class="info">
                    <label>Budget prévisionnel global du projet (en euros) *</label>
                    <input type="number" id="budget" name="budget" required>
                </div>
                <div class="info">
                    <label>Indiquez à quelles fins sera utilisé le montant demandé à Demathieu Bard *</label>
                    <textarea rows="1" cols="50" style="resize:none" id="findb" name="findb" maxlength="90" required></textarea>
                </div>
                <!--<div class="info">
                    <label>Co-financeur(s) éventuel(s)</label>
                    <input type="text" id="cofindb" name="cofindb" maxlength="90"></input>
                </div>-->
                <div class="info" >
                    <label id="labelcofin">Co-financeur(s) éventuel(s) ? (maximum 5)</label>
                    <div class="cofin">
                    </div>
                    <br>
                    <input type="button" id="boutoncofin" value="Ajouter">
                    <input type="hidden" id="nbCo" name="nbCo" value="0">
                </div>
                <div class="info">
                    <label>Votre projet est-il d'intérêt général ? *</label>
                    <div id="in"><label>Non</label><input type="radio" name="group0" value="0" checked/></div>
                    <div id="in"><label>Oui</label><input type="radio" name="group0" value="1"/></div>
                </div>
                <div class="info">
                    <label>Domaine principal du projet *</label>
                    <textarea rows="1" cols="50" style="resize:none" id="domaine" name="domaine" maxlength="90" required></textarea>
                </div>
                <div id="parrain" class="info">
                    <label>Avez vous un parrain impliqué dans votre projet, salarié ou retraité du Groupe ? *</label>
                    <div id="in"><label>Non</label><input type="radio" id="parrain1" name="group1" value="0" checked/></div>
                    <div id="in"><label>Oui</label><input type="radio" id="parrain2" name="group1" value="1"/></div>
                    <div id="divautre2">
                        <div class="info">
                            <label>Nom et prénom du parrain *</label>
                            <div id="in">
                                <input type="text" id="nomparrain" name="nomparrain" placeholder="Nom">
                                <input type="text" id="prenomparrain" name="prenomparrain" placeholder="Prenom">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="info">
                    <label>Etes vous en mesure d'établir une convention de Mécénat ? *</label>
                    <div id="in"><label>Non</label><input type="radio" name="group2" value="0" checked/></div>
                    <div id="in"><label>Oui</label><input type="radio" name="group2" value="1"/></div>
                </div>
                <div class="info">
                    <label>Etes vous en mesure d'établir un reçu fiscal (cerfa n°11580*03) ? *</label>
                    <div id="in"><label>Non</label><input type="radio" name="group3" value="0" checked/></div>
                    <div id="in"><label>Oui</label><input type="radio" name="group3" value="1"/></div>
                </div>
                <div class="info">
                    <label>Valorisation éventuelle proposée</label>
                    <textarea rows="3" cols="50" style="resize:none" id="valorev" name="valorev"></textarea>
                </div>
                <br><input type="submit" value="Validation de votre demande" name="submit">
              </form>  
            
end;
    }

    private function succes()
    {
        return <<<end
        <h3>Nous avons bien pris en compte votre demande et prendrons contact avec vous par mail au plus vite</h3>
end;
    }
}