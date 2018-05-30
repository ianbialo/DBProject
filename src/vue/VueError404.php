<?php

namespace dbproject\vue;

/**
 * Classe répertoriant la méthode liée à l'erreur 404
 * @author IBIALO
 *
 */
class VueError404
{

    private $objet;

    public function __construct($array = null)
    {
        $this->objet = $array;
    }

    /**   
     * Méthode générant le code HTML de la page 404
     * @return string code HTML de la page 404
     * 
     */
    public function render()
    {
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        $accueil = $app->urlFor("accueil");
        return <<<end
        
        <div class="content">
        <div class="panel" style="height: 800px;">
        
        <div class="header">
            <a href="https://www.demathieu-bard.fr/" title="Accueil" rel="home" id="logo-img">
                <img class="site-logo" src="$path/img/db_accueil.jpg" alt="Accueil" pagespeed_no_transform="">
            </a>
        </div>
        <div class="picture">
            <img src="https://www.demathieu-bard.fr/sites/default/files/styles/bandeau/public/images-illustrations/contact.png?itok=AExohBay">
        </div>
        
        <div class="title">
        <h1><img src="$path/img/puce-db.png" alt=""> Dépôt d’une demande de partenariat / sponsoring / mécénat</h1>
        </div>
        <h3>Il semble que la page soit inexistente.</h3>
        <a id="retour" href='$accueil'>Retour à l'accueil</a>
        </div>
        <div class="footer">
            <ul>
                <li><a href="https://www.demathieu-bard.fr/cr%C3%A9dits">Crédits</a></li>
                <li><a href="https://www.demathieu-bard.fr/mentions-l%C3%A9gales">Mentions légales</a></li>
            </ul>
        </div>
        </div>
        
end;
        
    	$app = \Slim\Slim::getInstance();
    	$accueil = $app->urlFor("accueil");
    	$content="<h1></h1>";
    	$content.="<a href='$accueil'>Retour à l'accueil</a>";
    	return $content;
    }
}