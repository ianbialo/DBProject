<?php

namespace dbproject\controleur;
use dbproject\vue\VueError404;

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