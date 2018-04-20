<?php
namespace dbproject\modele;

class Implique extends \Illuminate\Database\Eloquent\Model
{
    
    protected $table = 'implique';
    protected $primaryKey = 'IdImpl';
    public $timestamps = false;
    
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Implique::where('IdImpl', '=', $id)->first();
    }
    
    public static function getImplique($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Implique::where('IdProjet', '=', $id)->get();
    }
    
    public static function getCoFinanceur($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Implique::where('IdProjet', '=', $id)->where('Role', '=', '0')->get();
    }
    
    public static function getParrain($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Implique::where('IdProjet', '=', $id)->where('Role', '=', '1')->get();
    }
}