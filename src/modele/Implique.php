<?php
namespace dbproject\modele;

/**
 * Modèle de personne impliquée dans le projet
 * @author IBIALO
 *
 */
class Implique extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string Nom de la table dans la base de données
     */
    protected $table = 'implique';
    /**
     * @var string clef primaire de la table
     */
    protected $primaryKey = 'IdImpl';
    public $timestamps = false;
    
    /**
     * Méthode permettant de récupérer une personne impliquée selon un ID
     * @param int $id id de la personne impliquée
     * @return object modèle de la personne impliquée au projet
     */
    public static function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Implique::where('IdImpl', '=', $id)->first();
    }
    
    /**
     * Méthode permettant de récupérer une personne impliquée selon un ID de projet
     * @param int $id id du projet de la personne impliquée
     * @return object modèle(s) de(s) personne(s) impliquée(s) au projet
     */
    public static function getImplique($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Implique::where('IdProjet', '=', $id)->get();
    }
    
    /**
     * Méthode permettant de récupérer un/des co-financeur(s) selon un ID de projet
     * @param int $id id du projet du/des co-financeur(s)
     * @return object modèle(s) de(s) co-financeur(s) au projet
     */
    public static function getCoFinanceur($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Implique::where('IdProjet', '=', $id)->where('Role', '=', '0')->get();
    }
    
    /**
     * Méthode permettant de récupérer un/des parrains(s) selon un ID de projet
     * @param int $id id du projet du/des parrain(s)
     * @return object modèle(s) de(s) parrain(s) au projet
     */
    public static function getParrain($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return Implique::where('IdProjet', '=', $id)->where('Role', '=', '1')->get();
    }
}