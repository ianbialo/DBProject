<?php
namespace dbproject\modele;

/**
 * Modèle de représentant de projet
 * @author IBIALO
 *
 */
class Representant extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string Nom de la table dans la base de données
     */
    protected $table = 'representant';
    /**
     * @var string clef primaire de la table
     */
    protected $primaryKey = 'IdRep';
    public $timestamps = false;
    
    /**
     * Méthode permettant de récupérer un représentant selon un ID
     * @param int $id id du représentant
     * @return object modèle du représentant
     */
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Representant::where('IdRep', '=', $id)->first();
    }
}