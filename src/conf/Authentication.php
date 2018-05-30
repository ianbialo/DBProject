<?php
namespace dbproject\conf;

use dbproject\modele\User;

class Authentication
{

    /**
     * Méthode d'authentification LDAP à l'Active Directory de l'entreprise
     * @param int $id id de l'utilisateur (ici son adresse mail)
     * @param string $password mot de passe de l'utilisateur
     */
    public static function authenticate($id, $password)
    {
        $app = \Slim\Slim::getInstance();
        
        // Mémorisation des informations dans de nouvelles variables
        $ldap_dn = $id;
        $ldap_password = $password;
        
        // Connexion au serveur LDAP
        $ldap_con = ldap_connect("ldap://srvadmont1.ad.demathieu-bard.fr", 389) or die("Impossible de se connecter au serveur LDAP.");
        ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
        
        // Si la vérification de l'utilisateur est correcte
        if (@ldap_bind($ldap_con, $ldap_dn, $ldap_password)) {
            
            //Création du cookie de connexion
            $app->setEncryptedCookie("user", $id, time() + 60 * 60 * 24 * 30, "/");
            unset($_COOKIE['user']);
            setcookie('user', '', time() - 60 * 60 * 24, '/');
            
            //Deconnexion du serveur
            ldap_unbind($ldap_con);
            
            //Redirection vers la page des formulaires
            $app->redirect($app->urlFor("listeFormulaires"));
            
        } else {
            
            $_SESSION['message'] = "Les renseignements fournis ne sont pas corrects. Veuillez réessayer.";
            $app->redirect($app->urlFor("connexionAdmin"));
        }
    }

    /**
     * Méthode de déconnexion de l'utilisateur au back office
     */
    public static function disconnect()
    {
        if (isset($_COOKIE['user'])) {
            unset($_COOKIE['user']);
            setcookie('user', '', time() - 60 * 60 * 24, '/'); // valeur vide et temps dans le passé
        }
    }
}
