<?php

namespace dbproject\vue;

class VuePageHTML
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
        return <<<end
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>DBProjet</title>

        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="$path/css/materialize.min.css"  media="screen,projection"/>
        <link href="$path/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    </head>
    <body>
        <script type="text/javascript" src="$path/js/materialize.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		
end;
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
    <footer>
      <div>
        <div>
             <div>BIALO Ian</div>
             <div>Demathieu Bard</div>
        </div>
      </div>
  </footer>
  <script src="$path/js/materialize.min.js"></script>
  <script src="$path/js/init.js"></script>  
</body>
</html>
end;
    }
}
