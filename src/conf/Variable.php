<?php
namespace dbproject\conf;

class Variable
{

    //Chemin où se situe le projet dans le disque dur
    public static $path = "C:\Users\ibialo\Documents\GIT\DBProject";

    //Dossier dans le projet (à partir de la racine) où se situent les dossiers de fichiers
    public static $dossierFichier = "uploads";
    
    //Format de fichiers autorisé
    public static $formatAutorise = array("jpeg","odt","doc","docx","pdf","xls","xlsx");

    //Annuaire des personnes à informer en cas de création de nouveau formulaire
    public static $annuaire = array(
        "ian.bialo@demathieu-bard.fr" => "Destinataire un",
        "ian.bialo9@etu.univ-lorraine.fr" => "Destinaire deux"
    );
}
