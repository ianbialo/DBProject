<?php
namespace dbproject\modele;

/**
 * Modèle d'utilisateur de projet
 * @author IBIALO
 *
 */
class User extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string Nom de la table dans la base de données
     */
    protected $table = 'user';
    /**
     * @var string clef primaire de la table
     */
    protected $primaryKey = 'login';
    public $timestamps = false;
    public $incrementing = false;
    
    /**
     * Méthode permettant de récupérer un utilisateur selon un ID
     * @param int $id id de l'utilisateur
     * @return object modèle de l'utilisateur
     */
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_EMAIL);
        return User::where('login', '=', $id)->first();
    }
    
    public static function getAll(){
        return User::all();
    }
}