<?php
namespace dbproject\modele;

class Projet extends \Illuminate\Database\Eloquent\Model
{
    
    protected $table = 'projet';
    protected $primaryKey = 'IdProjet';
    public $timestamps = false;
    
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_EMAIL);
        return User::where('IdProjet', '=', $id)->first();
    }
}