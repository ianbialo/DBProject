<?php

namespace dbproject\vue;

/**
 * Classe répertoriant les codes HTML liés au header et au footer du Front Office.
 * @author IBIALO
 *
 */
class VuePageHTMLFrontOffice
{


    /**
     * Méthode générant le début du code HTML du front office
     * @return string début du code HTML du front office
     */
    public static function header()
    {
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        $acc = $app->urlFor("accueil");
        $res = <<<end
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>DBProjet</title>
        <link rel="icon" type="image/png" href="$path/img/favicon.png" />
        <link href="$path/css/formulaire.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    </head>
    <body>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="$path/js/formulaire.js"></script>
        <!--<header>
            <a href="/" title="Accueil" rel="home" id="logo-img">
            <img class="site-logo" src="$path/img/db_accueil.jpg" alt="Accueil" pagespeed_no_transform="">
            </a>
        </header>-->
        <main>

end;
        return $res;
    }

    /**
     * Méthode générant la fin du code HTML du front office
     * @return string fin du code HTML du front office
     */
    public static function getFooter()
    {
        return <<<end

            </main>
    </body>
</html>
end;
    }
}
