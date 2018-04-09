<?php
namespace dbproject\conf;
use Illuminate\Database\Capsule\Manager as DB;

class ConnexionBase
{
    public static function initialisation($file){
        $connection = parse_ini_file($file);
        $db = new DB();
        $db->addConnection($connection);
        $db->setAsGlobal();
        $db->bootEloquent();
    }
}