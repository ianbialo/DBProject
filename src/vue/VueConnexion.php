<?php
namespace dbproject\vue;


require_once "vendor/autoload.php";
require_once 'vendor/slim/slim/Slim/Slim.php';
require_once 'src/vue/VuePageHTML.php';

class VueConnexion{
    const AFF_CONNEXION = 1;
    const AFF_INSCRIPTION = 2;
    
    public function render($selecteur, $tab = null)
    {
        switch ($selecteur) {
            case VueConnexion::AFF_CONNEXION :
                $content = $this->connexion();
                break;
            case VueConnexion::AFF_INSCRIPTION :
                $content = $this->inscription();
                break;
        }
        return VuePageHTML::header().$content.VuePageHTML::getFooter();
    }
    
    private function connexion(){
        $app = \Slim\Slim::getInstance();
        $postCo = $app->urlFor("postConnexion");
        $inscr = $app->urlFor("inscription");
        return <<<end
        <p>Connexion</p>
        <form method="POST" action="$postCo">
            <div>
                <label>Login</label>
                <input type="text" name="loginCo" required><br>             
            </div>
            <div>
                <label>Mot de passe</label>
                <input type="password" name="mdpCo" required><br>             
            </div>
            <button>Valider</button>
        </form>
        <a href="#">Mot de passe oublié</a><br>
        <a href="$inscr">Pas de compte? Inscrivez-vous</a>
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
            <button>Inscription</button>
        </form>
        <a href="$retour">Retour</a><br>
end;
    }
}