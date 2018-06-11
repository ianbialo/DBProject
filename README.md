# DBProject

This is a french project with no translation. All of the Readme and other files content are in French.

DBProject est un projet réalisé dans le cadre d'un stage de dix semaines en entreprise.
Il a pour but de répondre aux besoins du département de la communication de l'entreprise au travers d'une application web.

DBProject est une plateforme permettant à un utilisateur de remplir un formulaire vis-à-vis d'une demande de partenariat, sponsoring, mécénat.
Ces informations sont ensuite traitées pour la mise en place d'un suivi de projet.

## Prérequis

* Un serveur web (apache par exemple)
* PHP 5.4 ou plus
* MySQL 5.5.40 ou plus

## Installation

Pour pouvoir mettre en place l'application web, il suffit de :

* Cloner le dépôt dans le répertoire de votre serveur web
* Rendez-vous dans le fichier Variable.php situé dans le dossier à l'adresse src/conf/ et modifier les informations nécessaires.
  * $path : chemin vers le dossier du projet
  * $dossierFichier : chemin depuis la racine vers le dossier contenant le dossier allant contenir les différents fichiers uploadés dans l'application.
  * $annuaire : destinataires des mails envoyés par l'application.

  Le reste des informations sont modifiables de manière optionnel.
* Depuis une invite de commande, placez vous dans la racine du projet et lancez la commande :
    
    **php deploy.php**

* Votre application est maintenant prête à être utilisée !

## Attention

Il est important de ne pas oublier d'accorder les droits nécessaires au dossier où se situe l'application pour le bon fonctionnement de celui-ci.
