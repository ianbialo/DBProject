<?php

namespace dbproject\vue;

class VuePageHTML
{

    /**public static function getHeaders()
    {
        $content = self::headersDeb();
        if (isset($_COOKIE['enseignant'])) {
            $content .= self::headersCoEns();
        } else {
            if(isset($_COOKIE['etudiant'])){
                $content .= self::headerCoEtu();
            } else {
                $content .= self::headersPasCo();
            }
        }
        $content .= self::finHeader();
        return $content;
    }*/

    /**
     *
     * @return string HTML du Début de chaque page (header)
     */
    public static function headersDeb()
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
    </head>
    <body>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		
        <header>
            <nav>
                <div class="container">
                    <div class="nav-wrapper">
end;
    }

    /**
     *
     * @return string Appel HMTL d'affichage Header si un utilisateur est co
     */
    public static function headersCoEns()
    {
        $app = \Slim\Slim::getInstance();
        $r_profil = $app->urlFor('modificationEnseignant');
        $r_decon = $app->urlFor('deconnexionEns');
        $r_index = $app->urlFor('accueil');
        $r_quiz = $app->urlFor('listeQuizEnseignant');
        $r_salon = $app->urlFor('affSalons');

        return <<<end
		<a href="$r_index" class="brand-logo center">Accueil</a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
          <li><a href="$r_salon">Salons</a></li>
          <li><a href="$r_quiz">Quiz</a></li>
          <li><a href="$r_profil">Profil</a></li>
          <li><a href="$r_decon">Se déconnecter</a></li>
        </ul>
        <ul class="side-nav" id="mobile-demo">
          <li><a href="$r_index">Accueil</a></li>
          <li class="divider"></li>
          <li><a href="$r_salon">Salons</a></li>
          <li><a href="$r_quiz">Quiz</a></li>
          <li><a href="$r_profil">Profil</a></li>
          <li><a href="$r_decon">Se déconnecter</a></li>
        </ul>
end;
    }

    public static function headerCoEtu(){
        $app = \Slim\Slim::getInstance();
        $r_index = $app->urlFor('accueil');
        $r_decon = $app->urlFor('deconnexionEns');
        $r_salon = $app->urlFor('affsalon_etu'); // ----------------------Le pb vient de là

        return <<<end
        

          <!-- Modal Structure -->
          <div id="modal1" class="modal">
            <div class="modal-content">
              <h4 class="header">Êtes vous sur de vouloir vous déconnecter ?</h4>
              <p class="black-text">Si vous vous déconnectez vous ne pourrez plus accéder à ce  quiz, ou devrez le recommencer à zero.</p>
            </div>
            <div class="modal-footer row">
                <div class="col s6 center-align">
                    <a href="$r_decon" class="modal-action modal-close waves-effect waves-green btn-flat">Se déconnecter</a>
                </div>
                <div class="col s6 center-align">
                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Rester</a>
                </div>
            </div>
          </div>
        
        
        <a href="$r_index" class="brand-logo center">Accueil</a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
          <li><a href="$r_salon">Salon</a></li>
          <li><a href="#modal1" class="modal-trigger">Se déconnecter</a></li>
        </ul>
        <ul class="side-nav" id="mobile-demo">
          <li><a href="$r_index">Accueil</a></li>
          <li class="divider"></li>
          <li><a href="$r_salon">Salon</a></li>
          <li><a href="#modal1" class="modal-trigger">Se déconnecter</a></li>
        </ul>
end;

    }

    /**
     *
     * @return string Appel HMTL d'affichage Header si un utilisateur n'est pas co
     */
    public static function headersPasCo()
    {
        $app = \Slim\Slim::getInstance();
        $r_index = $app->urlFor('accueil');
        return <<<end
        <a href="$r_index" class="brand-logo center">Accueil</a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
            <li><a class="dropdown-button" href="#" data-activates="dropdown1">Etudiant<i class="material-icons right">arrow_drop_down</i></a></li>
            <li><a class="dropdown-button" href="#" data-activates="dropdown2">Enseignant<i class="material-icons right">arrow_drop_down</i></a></li>
          </ul>
          <ul class="side-nav" id="mobile-demo">
              <li><a href="$r_index">Accueil</a></li>
              <li class="divider"></li>
              <li><a class="dropdown-button" href="#" data-activates="dropdown3">Etudiant<i class="material-icons right">arrow_drop_down</i></a></li>
              <li><a class="dropdown-button" href="#" data-activates="dropdown4">Enseignant<i class="material-icons right">arrow_drop_down</i></a></li>
          </ul>
end;
    }

    /**
     *
     * @return string HTML pour finir le header
     */
    public static function finHeader()
    {
        $app = \Slim\Slim::getInstance();
        $content = <<<end
            </div>
        </div>
    </nav>
</header>
          <main class="light-blue lighten-5">
            <div class="container">
end;
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $content .= <<<end
            <p class="message">$message</p>
end;
        }
        $_SESSION['message'] = null;
        return $content;
    }

    /**
     *
     * @return string Affichage du footer sur chaque page
     */
    public static function getFooter()
    {
        $app = \Slim\Slim::getInstance();
        $contact = $app->urlFor('accueil');
        return <<<end
		<br>
		</div class="container">
    </main>
    <footer class="page-footer">
      <div class="container">
        <div class="row">
             <div class="col s3">BIALO Ian</div>
             <div class="col s3">Demathieu Bard</div>
        </div>
      </div>
  </footer>
</body>
</html>
end;
    }
}
