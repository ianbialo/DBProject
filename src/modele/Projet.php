<?php
namespace dbproject\modele;

/**
 * Modèle de projet
 * @author IBIALO
 *
 */
class Projet extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string Nom de la table dans la base de données
     */
    protected $table = 'projet';
    /**
     * @var string clef primaire de la table
     */
    protected $primaryKey = 'IdProjet';
    public $timestamps = false;
    
    /**
     * Méthode permettant de récupérer un projet selon un ID
     * @param int $id id du projet
     * @return object modèle du projet
     */
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Projet::where('IdProjet', '=', $id)->first();
    }
    
    /**
     * Méthode permettant de récupérer un projet selon un ID de la structure
     * @param int $id id de la structure du projet
     * @return object modèle du projet
     */
    public static function getByStructure($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Projet::where('IdStruct', '=', $id)->first();
    }
    
    /**
     * Méthode permettant de récupérer un projet selon un ID
     * @param int $id id du projet
     * @return object modèle du projet
     */
    
    /**
     * Méthode permettant de récupérer les projets selon l'indicatif $validate
     * @param int $validate indicatif déterminant les projets à récupérer (s'il existe un numéro chrono ou non) trié par le nom de la structure.
     * Par défaut il récupère tout les projets.
     * @return object modèles des projets
     */
    public static function getAll($validate = null){
        switch($validate){
            case 1 :
                return Projet::join('structure', 'projet.IdStruct', '=', 'structure.IdStruct')->join('suivi','projet.IdSuivi','=','suivi.IdSuivi')->select("projet.*")->where("suivi.Chrono","!=","0")->orderBy("structure.Nom")->get();
                break;
            case 2 :
                return Projet::join('structure', 'projet.IdStruct', '=', 'structure.IdStruct')->join('suivi','projet.IdSuivi','=','suivi.IdSuivi')->select("projet.*")->where("suivi.Chrono","=","0")->orderBy("structure.Nom")->get();
                break;
            default :
                return Projet::join('structure', 'projet.IdStruct', '=', 'structure.IdStruct')->select("projet.*")->orderBy("structure.Nom")->get();
        }
    }
    
    public static function getAllDate($validate = null){
        switch($validate){
            case 1 :
                return Projet::join('suivi','projet.IdSuivi','=','suivi.IdSuivi')->select("projet.*")->where("suivi.Chrono","!=","0")->orderBy("projet.dateDep")->get();
                break;
            case 2 :
                return Projet::join('suivi','projet.IdSuivi','=','suivi.IdSuivi')->select("projet.*")->where("suivi.Chrono","=","0")->orderBy("projet.dateDep")->get();
                break;
            default :
                return Projet::orderBy("dateDep")->get();
        }
    }
}