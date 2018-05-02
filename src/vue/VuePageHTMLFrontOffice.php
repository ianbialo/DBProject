<?php

namespace dbproject\vue;

class VuePageHTMLFrontOffice
{


    /**
     *
     * @return string HTML du Début de chaque page (header)
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
        <header>
            <p>Ceci est le header</p>
        </header>
        <main>

end;
        return $res;
    }

    /**
     *
     * @return string Affichage du footer sur chaque page
     */
    public static function getFooter()
    {
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $contact = $app->urlFor('accueil');
        $path = $requete->getRootUri();
        return <<<end

            </main>
        <footer>
             <p>Ceci est le footer</p>
        </footer>
    </body>
</html>
end;
    }
}
