<?php

namespace dbproject\vue;

use dbproject\modele\Structure;

/**
 * Classe répertoriant les codes HTML liés au header et au footer du Back Office. 
 * @author IBIALO
 *
 */
class VuePageHTMLBackOffice
{


    /**
     * Méthode générant le début du code HTML du back office
     * @return string début du code HTML du back office
     */
    public static function header()
    {
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        $acc = $app->urlFor("accueil");
        
        $structure = Structure::getAll();
        
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
        <link href="$path/css/suivi.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    </head>
    <body>
        <script type="text/javascript" src="$path/js/materialize.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <!--<script type="text/javascript" src="$path/js/r.js"></script>-->
        <script type="text/javascript" src="$path/js/suivi.js"></script>
        <script>
        $(document).ready(function() {
            $('.tabs').tabs();
            $('select').formSelect();
            $('.modal').modal({dismissible: false});

            $('.datepicker').datepicker({
                format : 'dd mmmm yyyy',
                i18n: {
                    months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                    monthsShort: ['Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juill', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
                    weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                    weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                    weekdaysAbbrev:	['D','L','Ma','Me','J','V','S'],
                    cancel : 'retour',
                    clear: 'effacer',
                },
                showClearBtn : true,
                //minDate: new Date(),
});

            $(function() {
                M.updateTextFields();
            });
            $('input.autocomplete').autocomplete({
            data: {
                
end;
        foreach($structure as $s) $res .= '"'.$s->Nom.'": null,
                ';
        $res .= <<<end
      },
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
    
    /**
     * Méthode complétant la méthode header() utilisée dans le cas où l'utilisateur est connecté
     * @return string code HTML
     */
    private static function getHeaderPasCo(){
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        $acc = $app->urlFor("connexionAdmin");
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
        
    /**
     * Méthode complétant la méthode header() utilisée dans le cas où l'utilisateur n'est pas connecté
     * @return string code HTML
     */
    private static function getHeaderCo(){
        $app = \Slim\Slim::getInstance();
        $requete = $app->request();
        $path = $requete->getRootUri();
        $acc = $app->urlFor("connexionAdmin");
        $disconnect = $app->urlFor("deconnexion");
        return <<<end

            <header>
            <nav class="white" role="navigation">
            <div class="nav-wrapper container">
                <a id="logo-container" href="$acc" class="brand-logo">DBProject</a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="$disconnect">Deconnexion</a></li>
                </ul>
                <ul id="nav-mobile" class="sidenav">
                    <li><a href="$disconnect">Deconnexion</a></li>
                </ul>
                <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            </div>
        </nav>
        </header>
end;
    }

    /**
     * Méthode générant la fin du code HTML du back office
     * @return string fin du code HTML du back office
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
          <p class="grey-text text-lighten-4">Réalisation du back office dans le cadre de la gestion des dépots de demande de partenariat / sponsoring / mécénat par Ian Bialo, étudiant en deuxième année en DUT Informatique (Nancy-Charlemange), 2018.</p>
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
      Réalisé à l'aide de <a class="brown-text text-lighten-3" href="http://materializecss.com">Materialize</a>
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
