<?php
namespace dbproject\conf;

class Modal{
    public static function genereModal(){
        return <<<end
        
        <div id="modal1" class="modal">
            <div class="modal-content">
            <h4>Attention</h4>
            <p></p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">D'accord</a>
            </div>
        </div>

end;
    }
        
    public static function enclencher($msg){
        return <<<end
        
        $(".modal div p:first").text("$msg");
            var elem = document.querySelector('#modal1');
            var instance = M.Modal.getInstance(elem);
            instance.open();

end;
    }
}