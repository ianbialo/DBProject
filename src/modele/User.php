<?php
namespace dbproject\modele;

class User extends \Illuminate\Database\Eloquent\Model
{
    
    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_EMAIL);
        return User::where('login', '=', $id)->first();
    }
}