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
    
    public static function getCoFinanceur($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Implique::where('IdProjet', '=', $id)->orWhere('Role', '=', "0")->first();
    }
}