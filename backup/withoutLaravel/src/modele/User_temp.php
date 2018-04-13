<?php
namespace dbproject\modele;

class User_temp extends \Illuminate\Database\Eloquent\Model
{
    
    protected $table = 'user_temp';
    protected $primaryKey = 'login';
    public $timestamps = false;
    public $incrementing = false;
    
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_EMAIL);
        return User::where('login', '=', $id)->first();
    }
}