<?php
namespace dbproject\conf;

require_once "vendor/autoload.php";
require_once 'src/modele/User.php';

use dbproject\modele\User;


class Authentication{

	public static function createUser( $surName, $firstName, $mail, $password, $questionSeq, $repSeq){
		$password = password_hash($password, PASSWORD_DEFAULT, Array('cost' => 12));
		$u = new User();
		$u->email = $mail;
		$u->nom = $surName;
		$u->prenom = $firstName;
		$u->mdp = $password;
		$u->idQuestionSecrete = $q->idQuestionSecrete;
		$u->save();
	}

	public static function authenticateEns($id, $password){
		$user = User::getById($id);
		if($user != null) {
		    if (password_verify($password,$user->mdp)) {
		        $app =  \Slim\Slim::getInstance();
		        $app->setEncryptedCookie("user", $id, time()+60*60*24*30, "/");
		        unset($_COOKIE['user']);
		        setcookie('user', '', time() - 60*60*24, '/');
		    } else {
		        echo "<script>alert('Mauvais mot de passe');</script>";
		        $app->redirect($app->urlFor("accueil"));
		    }
		}else{
		    echo "<script>alert('Utilisateur est introuvable');</script>";
		    $app->redirect($app->urlFor("accueil"));
		}
	}

	public static function disconnect(){
	}

	
	public static function updateUser( $u, $surName, $firstName, $mail, $password ){
	    
	}
}
