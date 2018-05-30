<?php
namespace dbproject\modele;

/**
 * Modèle de structure de projet
 * @author IBIALO
 *
 */
class Structure extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string Nom de la table dans la base de données
     */
    protected $table = 'structure';
    /**
     * @var string clef primaire de la table
     */
    protected $primaryKey = 'IdStruct';
    public $timestamps = false;
    
    /**
     * Méthode permettant de récupérer une structure selon un ID
     * @param int $id id de la structure
     * @return object modèle de la structure
     */
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Structure::where('IdStruct', '=', $id)->first();
    }
    
    /**
     * Méthode permettant de récupérer un/des structure(s) selon un nom
     * @param string $nom nom de la structure
     * @return object modèle(s) de(s) structure(s)
     */
    public static function getByName($nom){
        $id = filter_var($nom, FILTER_SANITIZE_STRING);
        return Structure::where('Nom', '=', $nom)->get();
    }
    
    /**
     * Méthode permettant de récupérer toutes les structures
     * @return object modèle(s) de(s) structure
     */
    public static function getAll(){
        return Structure::orderBy("Nom")->get();
    }
}