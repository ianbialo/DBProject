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
    
    public static function getAll(){
        return $test = Projet::join('structure', 'projet.IdStruct', '=', 'structure.IdStruct')->select("projet.*")->orderBy("structure.Nom")->get();
    }
}