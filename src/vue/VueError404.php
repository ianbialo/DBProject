<?php

namespace dbproject\vue;

class VueError404
{

    private $objet;

    public function __construct($array = null)
    {
        $this->objet = $array;
    }

    /**
     * @param $num
     * @return string
     * 
     * Méthode pour afficher la page 404
     */
    public function render($num = null)
    {
    	$app = \Slim\Slim::getInstance();
    	$accueil = $app->urlFor("accueil");
    	$content="<h1>Il semble que la page soit inexistente</h1>";
    	$content.="<a href='$accueil'>Retour à l'accueil</a>";
    	return $content;
    }
}