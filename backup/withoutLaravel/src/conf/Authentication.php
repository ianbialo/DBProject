<?php
namespace dbproject\conf;

use dbproject\modele\User;

class Authentication
{

    public static function createUser($id, $mdp, $orga, $nom, $prenom, $adr, $tel)
    {
        $app = \Slim\Slim::getInstance();
        $userTest = User::getById($id);
        if ($userTest == null) {
            $mdp = password_hash($mdp, PASSWORD_DEFAULT, Array(
                'cost' => 12
            ));
            $u = new User();
            $u->login = $id;
            $u->mdp = $mdp;
            $u->organisme = $orga;
            $u->nom = $nom;
            $u->prenom = $prenom;
            $u->adr = $adr;
            $u->tel = $tel;
            $u->save();
        } else {
            $_SESSION['message'] = "L'utilisateur existe déjà. Veuillez utiliser un autre login.";
            $app->redirect($app->urlFor("inscription"));
        }
    }

    public static function authenticateEns($id, $password)
    {
        $app = \Slim\Slim::getInstance();
        $user = User::getById($id);
        if ($user != null) {
            if (password_verify($password, $user->mdp)) {
                $app->setEncryptedCookie("user", $id, time() + 60 * 60 * 24 * 30, "/");
                unset($_COOKIE['user']);
                setcookie('user', '', time() - 60 * 60 * 24, '/');
            } else {
                $_SESSION['message'] = "Les renseignements fournis ne sont pas corrects. Veuillez réessayer";
                $app->redirect($app->urlFor("accueil"));
            }
        } else {
            $_SESSION['message'] = "Les renseignements fournis ne sont pas corrects. Veuillez réessayer";
            $app->redirect($app->urlFor("accueil"));
        }
    }

    public static function disconnect()
    {
        $app = \Slim\Slim::getInstance();
        if (isset($_COOKIE['user'])) {
            unset($_COOKIE['user']);
            setcookie('user', '', time() - 60 * 60 * 24, '/'); // valeur vide et temps dans le passé
        }
        $app->redirect($app->urlFor("accueil"));
    }

    public static function updateUser($id, $orga, $nom, $prenom, $adr, $tel, $mdp = null)
    {
        $u = User::getById($id);
        $u->organisme = $orga;
        $u->nom = $nom;
        $u->prenom = $prenom;
        $u->adr = $adr;
        $u->tel = $tel;
        if(isset($mdp)){
            $mdp = password_hash($mdp, PASSWORD_DEFAULT, Array(
                'cost' => 12
                ));
            $u->mdp = $mdp;
        }
        $u->save();
    }
}
