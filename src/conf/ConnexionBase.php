<?php
namespace dbproject\conf;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Classe permettant la connexion à la base de données MySQL
 * @author IBIALO
 *
 */
class ConnexionBase
{
    /**
     * Méthode de connexion à la base de donnée MySQL
     * @param string $file chemin vers le fichier de configuration
     */
    public static function initialisation($file){
        $connection = parse_ini_file($file);
        $db = new DB();
        $db->addConnection($connection);
        $db->setAsGlobal();
        $db->bootEloquent();
    }
}