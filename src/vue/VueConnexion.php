<?php
namespace dbproject\vue;

class VueConnexion{
    const AFF_INDEX = 0;
    const AFF_CONNEXION = 1;
    const AFF_INSCRIPTION = 2;
    
    public function render($selecteur, $tab = null)
    {
        switch ($selecteur) {
            case VueConnexion::AFF_INDEX :
                $content = $this->index();
                break;
            case VueConnexion::AFF_CONNEXION :
                $content = $this->connexion();
                break;
            case VueConnexion::AFF_INSCRIPTION :
                $content = $this->inscription();
                break;
        }
        return VuePageHTML::header().$content.VuePageHTML::getFooter();
    }
    
    private function index(){
        $app = \Slim\Slim::getInstance();
        $deco = $app->urlFor("postDeconnexion");
        return <<<end
        <p>Ceci est le menu et vous êtes connecté</p>
        <a href="$deco">Se déconnecter</a>
end;
    }
    
    private function connexion(){
        $app = \Slim\Slim::getInstance();
        $postCo = $app->urlFor("postConnexion");
        $inscr = $app->urlFor("inscription");
        return <<<end
        <div class="card-panel hoverable">
        <h3>Connexion</h3>
        <form method="POST" action="$postCo">
            <div class="input-field col s12">
                <i class="material-icons prefix">account_circle</i>
                <input id="loginCo" type="email" name="loginCo" class="validate" required><br>
                <label for="loginCo">Login</label>    
            </div>
            <div class="input-field col s12">
                <i class="material-icons prefix">https</i>
                <input id="mdpCo" type="password" name="mdpCo" class="validate" required><br>
                <label for="mdpCo">Mot de passe</label>      
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="action">Valider
                <i class="material-icons right">send</i>
            </button>
        </form>
        <a href="#">Mot de passe oublié</a><br>
        <a href="$inscr">Pas de compte? Inscrivez-vous</a>
        </div>
end;
    }
    
    private function inscription(){
        $app = \Slim\Slim::getInstance();
        $retour = $app->urlFor("accueil");
        $inscr = $app->urlFor("postInscription");
        if(isset($_SESSION['message'])) $msg = $_SESSION['message'];
        else $msg = "";
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
                <label for="prenomInscr">Prenom du responsable</label>

            </div>

            <div class="input-field col s12">
                <i class="material-icons prefix">location_on</i>
                <input type="text" id="adrInscr" name="adrInscr" required><br>
                <label for="adrInscr">Adresse</label>

            </div>

            <div class="input-field col s12">
                <i class="material-icons prefix">local_phone</i>
                <input type="number" minlength="10" id="telInscr" name="telInscr" required><br>
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


<div id="modal1" class="modal">
    <div class="modal-content">
      <h4>Attention</h4>
      <p>$msg</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">D'accord</a>
    </div>
  </div>

<script>
end;
        if(isset($_SESSION['message'])){
            $msg .= "var elem = document.querySelector('#modal1');
        var instance = M.Modal.getInstance(elem);
        instance.open();";
        }
        $res .= <<<end
$('#formInscr').submit(function() {
    let id1 = $('#mdpInscr').val(); //if #id1 is input element change from .text() to .val() 
    let id2 = $('#mdpInscr2').val(); //if #id2 is input element change from .text() to .val()
    let tel = $('#telInscr').val();
    if (id1 != id2) {
end;
        $msg = "Les deux mot de passe ne correspondent pas. Veuillez réessayer.";
        $res .= <<<end
        var elem = document.querySelector('#modal1');
        var instance = M.Modal.getInstance(elem);
        instance.open();
        return false;
    }
    if(tel.length<10){
end;
        $msg = "Le numéro de téléphone doit être composé de au moins 10 chiffres.";
        $res .= <<<end
        var elem = document.querySelector('#modal1');
        var instance = M.Modal.getInstance(elem);
        instance.open();
        return false;
    }
    return true;
});
</script>
end;
        return $res;
    }
}