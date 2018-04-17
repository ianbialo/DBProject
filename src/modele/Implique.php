<?php
namespace dbproject\modele;

class Implique extends \Illuminate\Database\Eloquent\Model
{
    
    protected $table = 'implique';
    protected $primaryKey = 'IdImpl';
    public $timestamps = false;
    
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_EMAIL);
        return User::where('IdImpl', '=', $id)->first();
    }
}