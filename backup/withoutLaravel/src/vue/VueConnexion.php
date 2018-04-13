<?php
namespace dbproject\vue;

use dbproject\conf\Modal;
use dbproject\modele\User;

class VueConnexion
{

    const AFF_INDEX = 0;

    const AFF_CONNEXION = 1;

    const AFF_INSCRIPTION = 2;

    const AFF_MDPPERDU = 3;

    const AFF_MODIFICATION = 4;

    public function render($selecteur, $tab = null)
    {
        switch ($selecteur) {
            case VueConnexion::AFF_INDEX:
                $content = $this->index();
                break;
            case VueConnexion::AFF_CONNEXION:
                $content = $this->connexion();
                break;
            case VueConnexion::AFF_INSCRIPTION:
                $content = $this->inscription();
                break;
            case VueConnexion::AFF_MDPPERDU:
                $content = $this->mdpPerdu();
                break;
            case VueConnexion::AFF_MODIFICATION:
                $content = $this->modification();
                break;
        }
        return VuePageHTML::header() . $content . VuePageHTML::getFooter();
    }

    private function index()
    {
        $app = \Slim\Slim::getInstance();
        $deco = $app->urlFor("postDeconnexion");
        return <<<end
        <p>Ceci est le menu et vous êtes connecté</p>
        <a href="$deco">Se déconnecter</a>
end;
    }

    private function connexion()
    {
        $app = \Slim\Slim::getInstance();
        $postCo = $app->urlFor("postConnexion");
        $inscr = $app->urlFor("inscription");
        $mdpOublie = $app->urlFor("recuperation");
        $res = <<<end

        <div class="card-panel hoverable">
        <h3>Connexion</h3>
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
        <a href="$mdpOublie">Mot de passe oublié</a><br>
        <a href="$inscr">Pas de compte? Inscrivez-vous</a>
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

    private function inscription()
    {
        $app = \Slim\Slim::getInstance();
        $retour = $app->urlFor("accueil");
        $inscr = $app->urlFor("postInscription");
        // if(isset($_SESSION['message'])) $msg = $_SESSION['message'];
        // else $msg = "";
        $res = <<<end
        <h3>Inscription</h3>
        <div class="card-panel hoverable">
        <form id="formInscr" class="col s12" method="POST" action="$inscr">
            
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
                <i class="material-icons prefix">account_balance</i>
                <input type="text" id="orgInscr" name="orgInscr" required><br>
                <label for="orgInscr">Nom de l'organisme</label>

            </div>

            <div class="input-field col s12">
                <i class="material-icons prefix">assignment_ind</i>
                <input type="text" id="nomInscr" name="nomInscr" required><br>
                <label for="nomInscr">Nom du responsable</label>

            </div>

            <div class="input-field col s12">
                <i class="material-icons prefix">assignment_ind</i>
                <input type="text" id="prenomInscr" name="prenomInscr" required><br>
                <label for="prenomInscr">Prénom du responsable</label>

            </div>

            <div class="input-field col s12">
                <i class="material-icons prefix">location_on</i>
                <input type="text" id="adrInscr" name="adrInscr" required><br>
                <label for="adrInscr">Adresse</label>

            </div>

            <div class="input-field col s12">
                <i class="material-icons prefix">local_phone</i>
                <input type="tel" minlength="10" id="telInscr" name="telInscr" required><br>
                <label for="telInscr">Numéro de téléphone</label>

            </div>
            </div>
            <div class="row">
            <a href="$retour" class="btn waves-effect waves-light">Retour</a>
            <button class="btn" type="submit" name="action">Inscription
                <i class="material-icons right">send</i>
            </button>
            </div>
        </form>
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
    let tel = $('#telInscr').val();
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
    if($.isNumeric(tel)){
    }else{
end;
        $msg = "Le numéro de téléphone doit être composé de au moins 10 chiffres et doit contenir uniquement des valeurs numériques.";
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

    private function mdpPerdu()
    {
        $app = \Slim\Slim::getInstance();
        $retour = $app->urlFor("accueil");
        $postRecup = $app->urlFor("postRecuperation");
        $res = <<<end
        <h3>Récupération de mot de passe</h3>
        <p>Pour pouvoir récupérer votre mot de passe, veuillez renseigner votre adresse mail.</p>
        <div class="card-panel hoverable">
        <form method="POST" action="#">
            <div class="input-field col s12">
                <i class="material-icons prefix">account_circle</i>
                <input id="loginCo" type="email" name="loginCo" class="validate active" required><br>
                <label for="loginCo">Login</label>    
            </div>
            <a href="$retour" class="btn waves-effect waves-light">Retour</a>
            <button class="btn" type="submit" name="action">Valider
                <i class="material-icons right">send</i>
            </button>
        </form>
        </div>
end;
        return $res;
    }

    private function modification()
    {
        $app = \Slim\Slim::getInstance();
        
        $id = $app->getEncryptedCookie("user");
        $tab = User::getById($id);
        
        $retour = $app->urlFor("accueil");
        $modif = $app->urlFor("postModification");
        $login = $tab->login;
        $res = <<<end
        <h3>Modification</h3>
        <div class="card-panel hoverable">
        <form id="formModif" class="col s12" method="POST" action="$modif">
        
            <div class="input-field col s12">
                <i class="material-icons prefix">account_circle</i>
                <input disabled value="$id" type="email" id="loginModif" name="loginModif" class="validate" required><br>
                <label for="loginmodif">Login (Inchangeable)</label>
                <span class="helper-text" data-error="Il faut rentrer une adresse mail valide" ></span>
            </div>
            <input name="loginModifhide" type="hidden" value="$id">

            <label>
                <input id="cbmdp" type="checkbox" class="filled-in"/>
                <span>Cochez si vous voulez changer le mot de passe</span>
             </label>

            <div class="card-panel hoverable">
            
            <div class="input-field col s12">
                <i class="material-icons prefix">https</i>
                <input disabled type="password" minlength="6" id="mdpModif" name="mdpModif" required><br>
                <label for="mdpModif">Nouveau mot de passe</label>
            </div>
            
            <div class="input-field col s12">
                <i class="material-icons prefix">https</i>
                <input disabled type="password" id="mdpModif2" name="mdpModif2" required><br>
                <label for="mdpModif2">Répétez le mot de passe</label>
                
            </div>

            </div>
            
            <div class="input-field col s12">
                <i class="material-icons prefix">account_balance</i>
                <input value="$tab->organisme" type="text" id="orgModif" name="orgModif" required><br>
                <label for="orgModif">Nom de l'organisme</label>
                
            </div>
            
            <div class="input-field col s12">
                <i class="material-icons prefix">assignment_ind</i>
                <input value="$tab->nom" type="text" id="nomModif" name="nomModif" required><br>
                <label for="nomModif">Nom du responsable</label>
                
            </div>
            
            <div class="input-field col s12">
                <i class="material-icons prefix">assignment_ind</i>
                <input value="$tab->prenom" type="text" id="prenomModif" name="prenomModif" required><br>
                <label for="prenomModif">Prénom du responsable</label>
                
            </div>
            
            <div class="input-field col s12">
                <i class="material-icons prefix">location_on</i>
                <input value="$tab->adr" type="text" id="adrModif" name="adrModif" required><br>
                <label for="adrModif">Adresse</label>
                
            </div>
            
            <div class="input-field col s12">
                <i class="material-icons prefix">local_phone</i>
                <input value="$tab->tel" type="tel" minlength="10" id="telModif" name="telModif" required><br>
                <label for="telModif">Numéro de téléphone</label>
                
            </div>
            </div>
            <div class="row">
            <a href="$retour" class="btn waves-effect waves-light">Retour</a>
            <button class="btn" type="submit" name="action">Modification
                <i class="material-icons right">send</i>
            </button>
            </div>
        </form>
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
$('#formModif').submit(function() {
    let id1 = $('#mdpModif').val(); //if #id1 is input element change from .text() to .val()
    let id2 = $('#mdpModif2').val(); //if #id2 is input element change from .text() to .val()
    let tel = $('#telModif').val();
    if (id1 != id2) {
end;
        $msg = "Les deux mot de passe ne correspondent pas. Veuillez réessayer.";
        $res .= Modal::enclencher($msg);
        $res .= <<<end
        return false;
    }
    if($.isNumeric(tel)){
    }else{
end;
        $msg = "Le numéro de téléphone doit être composé de au moins 10 chiffres.";
        $res .= Modal::enclencher($msg);
        $res .= <<<end
        return false;
    }
    return true;
});

$("#cbmdp").click(function() {
    if($(this).is(":checked")){
        $("#mdpModif").prop('disabled', false);
        $("#mdpModif2").prop('disabled', false);
    }else{
        $("#mdpModif").prop('disabled', true);
        $("#mdpModif2").prop('disabled', true);
        $("#mdpModif").val('');
        $("#mdpModif2").val('');
    }
});
</script>
end;
        return $res;
    }
}