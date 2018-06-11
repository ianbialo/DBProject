<?php
namespace dbproject\conf;

require_once 'vendor/ircmaxell/password-compat/lib/password.php';

use dbproject\modele\User;

/**
 * Classe permettant à un utilisateur de se connecter au BackOffice
 * @author IBIALO
 *
 */
class Authentication
{
    /**
     * Méthode de création d'un nouveau compte utilisateur
     * @param string $id login du nouvel utilisateur
     * @param string $mdp mot de passe du nouvel utilisateur
     * @param number $droit droit accordé au nouvel utilisateur
     */
    public static function createUser($id, $mdp, $droit=0)
    {
        $app = \Slim\Slim::getInstance();
        $userTest = User::getById($id);
        
        //Si l'utilisateur n'existe pas
        if ($userTest == null) {
            $mdp = password_hash($mdp, PASSWORD_DEFAULT, Array(
                'cost' => 12
                ));
            $u = new User();
            $u->login = filter_var($id, FILTER_SANITIZE_EMAIL);
            $u->mdp = filter_var($mdp, FILTER_SANITIZE_STRING);
            $u->droit = $droit;
            $u->dateCreation = date("Y-m-d");
            $u->save();
        } else {
            $_SESSION['message'] = "L'utilisateur existe déjà. Veuillez utiliser un autre login.";
            $app->redirect($app->urlFor("creationCompte"));
        }
    }
    
    /**
     * Méthode d'authentification d'un utilisateur au BackOffice
     * @param string $id login de l'utilisateur
     * @param string $password mot de passe de l'utilisateur
     */
    public static function authenticate($id, $password){
        $app = \Slim\Slim::getInstance();
        $id = filter_var($id, FILTER_SANITIZE_EMAIL);
        $user = User::getById($id);
        
        //Si l'utilisateur existe
        if ($user != null) {
            //Si le mot de passe est le bon
            if (password_verify($password, $user->mdp)) {
                $app->setEncryptedCookie("user", $id, time() + 60 * 60 * 24 * 30, "/");
                unset($_COOKIE['user']);
                setcookie('user', '', time() - 60 * 60 * 24, '/');
            } else {
                $_SESSION['message'] = "Les renseignements fournis ne sont pas corrects. Veuillez réessayer";
                $app->redirect($app->urlFor("connexionAdmin"));
            }
        } else {
            $_SESSION['message'] = "Les renseignements fournis ne sont pas corrects. Veuillez réessayer";
            $app->redirect($app->urlFor("connexionAdmin"));
        }
    }

    /**
     * Méthode de déconnexion de l'utilisateur au back office
     */
    public static function disconnect()
    {
        //Si le cookie existe
        if (isset($_COOKIE['user'])) {
            unset($_COOKIE['user']);
            setcookie('user', '', time() - 60 * 60 * 24, '/'); // valeur vide et temps dans le passé
        }
    }
    
    /**
     * Méthode de suppression d'utilisateur de la base de données
     * @param string $id
     */
    public static function delete($id){
        $user = User::getById($id);
        $user->delete();
    }
}
