<?php
namespace dbproject\conf;

/**
 * Classe définissant les variables statiques de l'application
 * @author IBIALO
 *
 */
class Variable
{

    // ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Il s'agit du fichier PHP contenant tout les variables qui peuvent être modifiées selon les envies.//
    // ////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * @var string Chemin où se situe le projet sur le disque.
     * Il doit être modifié pour correspondre au chemin du projet dans la machine où il se situe.
     * Le nom peut être changé seulement si l'application n'a pas été deployé.
     */
    public static $path = "C:\\xampp\htdocs\DBProject\DBProject";

    /**
     * @var string Dossier dans le projet (à partir de la racine) où se situent les dossiers de fichiers.
     * Le nom peut être changé seulement si l'application n'a pas été deployé.
     */
    public static $dossierFichier = "uploads";

    /**
     * Ne pas supprimer les deux premières valeurs et leurs positions !
     * Ne pas changer le nom des deux premières valeurs si des fichiers ont déjà été uploadés.
     * @var array Liste des dossiers permettant de différencier les différents types de fichier
     */
    public static $dossierSpecifique = array(
        "client",
        "perso"
    );

    /**
     * @var array Format de fichiers autorisé
     */
    public static $formatAutorise = array(
        "jpeg",
        "odt",
        "doc",
        "docx",
        "pdf",
        "xls",
        "xlsx"
    );

    /**
     * @var array Annuaire des personnes à informer en cas de création d'un nouveau formulaire
     */
    public static $annuaire = array(
        "ian.bialo@demathieu-bard.fr" => "Destinataire un",
        "ian.bialo9@etu.univ-lorraine.fr" => "Destinaire deux"
    );

}
