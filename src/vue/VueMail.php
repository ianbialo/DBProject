<?php
namespace dbproject\vue;

use dbproject\conf\Modal;

class VueMail{
    const AFF_MDP = 0;
    const AFF_INSCR = 1;
    const AFF_MODIFICATION = 2;
    
    public function render($selecteur, $tab = null)
    {
        switch ($selecteur) {
            case VueMail::AFF_MDP :
                $content = $this->affMdp();
                break;
            case VueMail::AFF_INSCR :
                $content = $this->affInscr();
                break;
            case VueMail::AFF_MODIFICATION :
                $content = $this->affModification();
                break;
        }
        return VuePageHTML::header().$content.VuePageHTML::getFooter();
    }
    
    private function affMdp(){
        $app = \Slim\Slim::getInstance();
        $retour = $app->urlFor("accueil");
        return <<<end
        <h3>Récupération de mot de passe</h3>
        <p>Un email vous a été envoyé. Veuillez vérifier votre boîte mail.</p><br>
        <a href="$retour" class="btn waves-effect waves-light">Retour</a>
end;
    }
        
    private function affInscr(){
        $app = \Slim\Slim::getInstance();
        $retour = $app->urlFor("accueil");
        return <<<end
        <h3>Inscription</h3>
        <p>Un email vous a été envoyé. Veuillez vérifier votre boîte mail.</p><br>
        <a href="$retour" class="btn waves-effect waves-light">Retour</a>
end;
    }
        
    private function affModification(){
        $app = \Slim\Slim::getInstance();
        $retour = $app->urlFor("accueil");
        return <<<end
        <h3>Modification</h3>
        <p>Un email vous a été envoyé. Veuillez vérifier votre boîte mail.</p><br>
        <a href="$retour" class="btn waves-effect waves-light">Retour</a>
end;
    }
}