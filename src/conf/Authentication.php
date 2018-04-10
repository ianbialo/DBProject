<?php
namespace dbproject\conf;

use dbproject\modele\User;

class Authentication
{

    public static function createUser($id, $mdp, $orga, $nom, $prenom, $adr, $tel)
    {
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
            echo "<script>alert('L utilisateur existe déjà');</script>";
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
                $app->redirect($app->urlFor("accueil"));
            }
        } else {
            $app->redirect($app->urlFor("accueil"));
        }
    }

    public static function disconnect()
    {
        $app =  \Slim\Slim::getInstance();
        if (isset($_COOKIE['user'])) {
            unset($_COOKIE['user']);
            setcookie('user', '', time() - 60*60*24, '/'); // valeur vide et temps dans le passé
        }
        $app->redirect($app->urlFor("accueil"));
    }

    public static function updateUser($u, $surName, $firstName, $mail, $password)
    {}
}
