<?php
namespace dbproject\modele;

class Representant extends \Illuminate\Database\Eloquent\Model
{
    
    protected $table = 'representant';
    protected $primaryKey = 'IdRep';
    public $timestamps = false;
    
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Representant::where('IdRep', '=', $id)->first();
    }
}