<?php
namespace dbproject\vue;

class VueFrontOffice
{

    const AFF_INDEX = 0;

    const AFF_OK = 1;
    
    const AFF_ECHEC = 2;

    public function render($selecteur, $tab = null)
    {
        switch ($selecteur) {
            case VueFrontOffice::AFF_INDEX:
                $content = $this->index();
                break;
            case VueFrontOffice::AFF_OK:
                $content = $this->succes();
                break;
            case VueFrontOffice::AFF_ECHEC:
                $content = $this->echec();
                break;
        }
        return $content;
    }

    private function index()
    {
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        $val = $app->urlFor("postFormulaire");
        $res = <<<end

        <div class="panel">

        <div class="header">
            <a href="https://www.demathieu-bard.fr/" title="Accueil" rel="home" id="logo-img">
                <img class="site-logo" src="$path/img/db_accueil.jpg" alt="Accueil" pagespeed_no_transform="">
            </a>
        </div>
        <div class="picture">
            <img src="https://www.demathieu-bard.fr/sites/default/files/styles/bandeau/public/images-illustrations/contact.png?itok=AExohBay" alt="">
        </div>

        <div class="title">
        <h1><img src="$path/img/puce-db.png" alt=""> Dépôt d’une demande de partenariat / sponsoring / mécénat</h1>
        </div>
            <h3>Veuillez renseigner ci-dessous les informations nécessaires dans le cadre d'une demande de partenariat / sponsoring / mécénat.<br>Les champs suivis d'un <span style="color: red;"><span style="color: red;">*</span></span> doivent obligatoirement être renseignés.</h3>
            <div class="formulaire">
            <form method="POST" id="formFormulaire" action="$val" enctype="multipart/form-data">            

                <span><strong>STRUCTURE</strong></span>
                <div class="info">
                    <label>Nom de la structure demanderesse <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <div><input type="text" id="nomstruct" name="nomstruct" maxlength="90" autofocus required></div>
                </div>
                <div class="info">
                    <label>Adresse de la structure demanderesse <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <div><input type="text" class="grandeTaille" id="adrstruct" name="adrstruct" placeholder="Adresse" maxlength="80" required></div>
                    <div id="in">
                        <input type="number" id="cpostalstruct" name="cpostalstruct" placeholder="Code Postal" max="99999" required>
                        <input type="text" id="villestruct" name="villestruct" placeholder="Ville" maxlength="60" required>
                    </div>
                </div>
                <div class="info">
                    <label>Raison d'être de la structure demanderesse <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <textarea rows="3" cols="50" style="resize:none" id="raisontruct" name="raisonstruct" maxlength="300" required></textarea>
                </div>
                <div id="selecteur" class="info">
                    <label>Vous êtes : <span style="color: red;"><span style="color: red;">*</span></span></label>
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
                        <label>Veuillez préciser : <span style="color: red;"><span style="color: red;">*</span></span></label>
                        <input type="text" id="autre" name="autre">
                    </div>
                </div>
                <div class="info">
                    <label>Site internet</label>
                    <div><input type="url" id="site" maxlength="200" name="site"></div>
                </div>
                
            
                <span><strong>PERSONNEL</strong></span>
                <div class="info">
                    <label>Nom et prénom du représentant légal (qui signera la convention)<span style="color: red;"><span style="color: red;">*</span></span></label>
                    <div id="in">
                        <input type="text" id="nomrzplegal" name="nomrzplegal" placeholder="Nom" maxlength="30" required>
                        <input type="text" id="prenomrzplegal" name="prenomrzplegal" placeholder="Prenom" maxlength="30" required>
                    </div>
                </div>
                <div class="info">
                    <label>Qualité du signataire <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <div><input type="text" id="qualite" name="qualite" maxlength="60" required></div>
                </div>
                <div class="info">
                    <label>Nom et prénom du responsable du projet <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <div id="in">
                        <input type="text" id="nomresplegal" name="nomresplegal" placeholder="Nom" maxlength="30" required>
                        <input type="text" id="prenomresplegal" name="prenomresplegal" placeholder="Prenom" maxlength="30" required>
                    </div>
                </div>
                <div class="info">
                    <label>Position dans la structure <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <div><input type="text" id="position" name="position" maxlength="60" required></div>
                </div>
                <div class="info">
                    <label>Adresse du porteur du projet <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <div><input type="text" class="grandeTaille" id="adrport" name="adrport" placeholder="Adresse" maxlength="80" required></div>
                    <div id="in">
                    <input type="number" id="cpostalport" name="cpostalport" placeholder="Code Postal" max="99999" required>
                    <input type="text" id="villeport" name="villeport" placeholder="Ville" maxlength="60" required></div>
                </div>
                <div class="info">
                    <label>Telephone <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <div><input type="text" id="tel" name="tel" pattern="[0-9]{9,13}" required></div>
                </div>
                <div class="info">
                    <label>Courriel <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <div><input type="email" id="courriel" name="courriel" maxlength="60" required></div>
                </div>
                

                <span><strong>PROJET</strong></span>
                <div class="info">
                    <label>Exposé synthétique du projet ou des actions à soutenir <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <textarea rows="5" cols="50" style="resize:none" id="expose" name="expose" maxlength="300" required></textarea>
                </div>
                <div class="info">
                    <label>Documents de présentation éventuels (flyer, affiche…) - 5 maximum</label>
                    <div class="coFile">
                    </div>
                    <br>
                    <div><input type="button" id="boutonfileajout" value="Ajouter"></div>
                    <input type="hidden" id="nbFile" name="nbFile" value="0">
                </div>
                <div class="info">
                    <label>Date de début du projet <span style="color: red;"><span style="color: red;">*</span></span></label>
                    <div><input type="date" id="datedeb" name="datedeb" required></div>
                </div>
                <div class="info">
                    <label>Durée du projet (en mois) <span style="color: red;">*</span></label>
                    <div><input type="number" id="duree" name="duree" required></div>
                </div>
                <div class="info">
                    <label>Lieu du projet <span style="color: red;">*</span></label>
                    <div><input type="text" id="lieu" name="lieu" maxlength="90" required></div>
                </div>
                <div class="info">
                    <label>Montant de l'aide financière sollicitée (en euros) <span style="color: red;">*</span></label>
                    <div><input type="number" id="aide" name="aide" required></div>
                </div>
                <div class="info">
                    <label>Budget prévisionnel global du projet (en euros) <span style="color: red;">*</span></label>
                    <div><input type="number" id="budget" name="budget" required></div>
                </div>
                <div class="info">
                    <label>Indiquez à quelles fins sera utilisé le montant demandé à Demathieu Bard <span style="color: red;">*</span></label>
                    <textarea rows="1" cols="50" style="resize:none" id="findb" name="findb" maxlength="300" required></textarea>
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
                    <div><input type="button" id="boutoncofin" value="Ajouter"></div>
                    <input type="hidden" id="nbCo" name="nbCo" value="0">
                </div>
                <div class="info">
                    <label>Votre projet est-il d'intérêt général ? <span style="color: red;">*</span></label>
                    <div id="in"><label>Non</label><input type="radio" name="group0" value="0" checked/></div>
                    <div id="in"><label>Oui</label><input type="radio" name="group0" value="1"/></div>
                </div>
                <div class="info">
                    <label>Domaine principal du projet <span style="color: red;">*</span></label>
                    <textarea rows="1" cols="50" style="resize:none" id="domaine" name="domaine" maxlength="150" required></textarea>
                </div>
                <div id="parrain" class="info">
                    <label>Avez vous un parrain impliqué dans votre projet, salarié ou retraité du Groupe ? <span style="color: red;">*</span></label>
                    <div id="in"><label>Non</label><input type="radio" id="parrain1" name="group1" value="0" checked/></div>
                    <div id="in"><label>Oui</label><input type="radio" id="parrain2" name="group1" value="1"/></div>
                    <div id="divautre2">
                        <div class="info">
                            <label>Nom et prénom du parrain <span style="color: red;">*</span></label>
                            <div id="in">
                                <input type="text" id="nomparrain" name="nomparrain" placeholder="Nom">
                                <input type="text" id="prenomparrain" name="prenomparrain" placeholder="Prenom">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="info">
                    <label>Etes vous en mesure d'établir une convention de Mécénat ? <span style="color: red;">*</span></label>
                    <div id="in"><label>Non</label><input type="radio" name="group2" value="0" checked/></div>
                    <div id="in"><label>Oui</label><input type="radio" name="group2" value="1"/></div>
                </div>
                <div class="info">
                    <label>Etes vous en mesure d'établir un reçu fiscal (cerfa n°11580<span style="color: red;">*</span>03) ? <span style="color: red;">*</span></label>
                    <div id="in"><label>Non</label><input type="radio" name="group3" value="0" checked/></div>
                    <div id="in"><label>Oui</label><input type="radio" name="group3" value="1"/></div>
                </div>
                <div class="info">
                    <label>Valorisation éventuelle proposée</label>
                    <textarea rows="3" cols="50" style="resize:none" id="valorev" name="valorev" maxlength="300"></textarea>
                </div>
                <br><input type="submit" id="submitButton" value="Valider et envoyer votre demande" name="submit">
                </div>
              </form>
          </div>
          <!--<div class="block-inner clearfix">  
            <div class="block-content content"><ul class="menu clearfix"><li class="first leaf active-trail menu-depth-1 menu-item-354"><a href="/cr%C3%A9dits" class="active-trail active">Crédits</a></li><li class="last leaf menu-depth-1 menu-item-356"><a href="/mentions-l%C3%A9gales">Mentions légales</a></li></ul></div>
          </div>-->

end;
        return $res;
    }

    private function succes()
    {
        return <<<end
        <h3>Nous avons bien pris en compte votre demande et prendrons contact avec vous par mail au plus vite.</h3>
end;
    }
        
    private function echec()
    {
        return <<<end
        <h3>Une erreur est survenue.</h3>
end;
    }
}