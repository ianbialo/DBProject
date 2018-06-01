<?php
namespace dbproject\conf;

class Modal{
    
    /**
     * Méthode de génération de code HTML d'un modal (boîte de dialogue)
     * @param string $titre titre du modal
     * @return string code HTML du modal
     */
    public static function genereModal($titre = "Attention"){
        return <<<end
        
        <div id="modal1" class="modal">
            <div class="modal-content">
            <h4>$titre</h4>
            <p></p>
            </div>
            <div class="modal-footer">
                <a href="" class="modal-action modal-close waves-effect waves-green btn-flat">D'accord</a>
            </div>
        </div>

end;
    }
      
    /**
     * Méthode permettant le déclenchement d'un modal avec un message personnalisé
     * @param string $msg message à insérer au modal
     * @return string code HTML du modal
     */
    public static function enclencher($msg){
        return <<<end
        
        $("#modal1 div p:first").text("$msg");
            var elem = document.querySelector('#modal1');
            var instance = M.Modal.getInstance(elem);
            instance.open();

end;
    }
}