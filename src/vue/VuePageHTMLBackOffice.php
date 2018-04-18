<?php

namespace dbproject\vue;

class VuePageHTMLBackOffice
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

        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="$path/css/materialize.css"  media="screen,projection"/>
        <link href="$path/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    </head>
    <body>
        <script type="text/javascript" src="$path/js/materialize.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script>
        $(document).ready(function() {
            $('.tabs').tabs();
            $('.modal').modal();
            $(function() {
                M.updateTextFields();
            });
        });
        </script>
end;
        if(isset($_COOKIE['user'])) $res .= self::getHeaderCo();
        else $res .= self::getHeaderPasCo();
        $res .= "
                <main>";
		
        return $res;
    }
        
    private static function getHeaderPasCo(){
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        $acc = $app->urlFor("accueil");
        return <<<end
            <header>
            <nav class="white" role="navigation">
            <div class="nav-wrapper container">
                <a id="logo-container" href="$acc" class="brand-logo">DBProject</a>
            </div>
        </nav>
        </header>
end;
    }
        
    private static function getHeaderCo(){
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        $modif = $app->urlFor("modification");
        return <<<end
            <header>
            <nav class="white" role="navigation">
            <div class="nav-wrapper container">
                <a id="logo-container" href="$modif" class="brand-logo">DBProject</a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="$modif">Modifier profil</a></li>
                </ul>
                <ul id="nav-mobile" class="sidenav">
                    <li><a href="$modif">Modifier profil</a></li>
                </ul>
                <a href="$modif" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            </div>
        </nav>
        </header>
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
    </main>
    <footer class="page-footer orange darken-3">
      <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Demathieu Bard</h5>
          <p class="grey-text text-lighten-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris hendrerit velit vel eros finibus, et fringilla massa molestie. Curabitur volutpat mi a cursus suscipit. Praesent commodo at mauris sed semper.</p>


        </div>
        <div class="col l3 s12">
          <h5 class="white-text">A Propos</h5>
          <ul>
            <li><a class="white-text" href="http://www.demathieu-bard.fr/">Site DB</a></li>
            <li><a class="white-text" href="http://intradb/Pages/Homepage.aspx">IntraDB</a></li>
          </ul>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Contact</h5>
          <ul>
            <li><a class="white-text" href="#!">GEORGE Pierre</a></li>
            <li><a class="white-text" href="mailto:ian.bialo@demathieu-bard.fr">BIALO Ian</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      Made by <a class="brown-text text-lighten-3" href="http://materializecss.com">Materialize</a>
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
