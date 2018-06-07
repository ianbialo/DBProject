<?php

namespace dbproject\controleur;
use dbproject\vue\VueError404;

/**
 * Classe définissant la méthode de controleur d'une erreur 404
 * @author IBIALO
 *
 */
class ControleurError404 {

	public function __construct() {}

	/**
     * Affichage de l'erreur 404 (page not found)
     */
	public function affichageErreur() {
		$vue = new VueError404();
		echo $vue->render();
	}
}