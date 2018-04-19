<?php
namespace dbproject\modele;

class Responsable extends \Illuminate\Database\Eloquent\Model
{
    
    protected $table = 'responsable';
    protected $primaryKey = 'IdRes';
    public $timestamps = false;
    
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Responsable::where('IdRes', '=', $id)->first();
    }
}