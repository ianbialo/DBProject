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
        <p>Connexion</p>
        <form method="POST" action="$postCo">
            <div class="input-field col s12">
                <i class="material-icons prefix">account_circle</i>
                <input id="loginCo" type="text" name="loginCo" class="validate" required><br>
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
        return <<<end
        <p>Inscription</p>
        <form method="POST" action="$inscr">
            <div>
                <label>Login</label>
                <input type="text" name="loginInscr" required><br>
            </div>
            <div>
                <label>Mot de passe</label>
                <input type="password" name="mdpInscr" required><br>
            </div>
            <div>
                <label>Répétez le mot de passe</label>
                <input type="password" name="mdpInscr2" required><br>
            </div>
            <div>
                <label>Nom de l'organisme</label>
                <input type="text" name="orgInscr" required><br>
            </div>
            <div>
                <label>Nom du responsable</label>
                <input type="text" name="nomInscr" required><br>
            </div>
            <div>
                <label>Prenom du responsable</label>
                <input type="text" name="prenomInscr" required><br>
            </div>
            <div>
                <label>Adresse</label>
                <input type="text" name="adrInscr" required><br>
            </div>
            <div>
                <label>Numéro de téléphone</label>
                <input type="number" name="telInscr" required><br>
            </div>
            <a href="$retour" class="btn waves-effect waves-light">Retour</a><br>
            <button class="btn waves-effect waves-light" type="submit" name="action">Inscription
                <i class="material-icons right">send</i>
            </button>
        </form>
end;
    }
}