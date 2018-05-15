<?php
namespace dbproject\modele;

class Suivi extends \Illuminate\Database\Eloquent\Model
{
    
    protected $table = 'suivi';
    protected $primaryKey = 'IdSuivi';
    public $timestamps = false;
    
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Suivi::where('IdSuivi', '=', $id)->first();
    }
    
    public static function getAllDate(){
        return Suivi::join('projet', 'suivi.IdSuivi', '=', 'projet.IdSuivi')->select("*")->orderBy("projet.DateDep")->get();
    }
}