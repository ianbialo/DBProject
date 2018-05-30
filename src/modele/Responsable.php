<?php
namespace dbproject\modele;

/**
 * Modèle de responsable de projet
 * @author IBIALO
 *
 */
class Responsable extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string Nom de la table dans la base de données
     */
    protected $table = 'responsable';
    /**
     * @var string clef primaire de la table
     */
    protected $primaryKey = 'IdRes';
    public $timestamps = false;
    
    /**
     * Méthode permettant de récupérer un responsable selon un ID
     * @param int $id id du responsable
     * @return object modèle du responsable
     */
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Responsable::where('IdRes', '=', $id)->first();
    }
}