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
    
    public static function getAll(){
        $query = Projet::all();
        return $query->sortBy("IdProjet");
    }
}