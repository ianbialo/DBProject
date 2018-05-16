<?php
namespace dbproject\modele;

class Projet extends \Illuminate\Database\Eloquent\Model
{
    
    protected $table = 'projet';
    protected $primaryKey = 'IdProjet';
    public $timestamps = false;
    
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Projet::where('IdProjet', '=', $id)->first();
    }
    
    public static function getByStructure($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Projet::where('IdStruct', '=', $id)->first();
    }
    
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