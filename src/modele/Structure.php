<?php
namespace dbproject\modele;

class Structure extends \Illuminate\Database\Eloquent\Model
{
    
    protected $table = 'structure';
    protected $primaryKey = 'IdStruct';
    public $timestamps = false;
    
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Structure::where('IdStruct', '=', $id)->first();
    }
}