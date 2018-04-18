<?php
namespace dbproject\vue;

class VueBackOffice
{

    const AFF_INDEX = 0;
    const AFF_FORMULAIRE = 1;

    public function render($selecteur, $tab = null)
    {
        switch ($selecteur) {
            case VueBackOffice::AFF_INDEX:
                $content = $this->index();
                break;
            case VueBackOffice::AFF_FORMULAIRE:
                $content = $this->formulaire();
                break;
        }
        return VuePageHTMLBackOffice::header() . $content . VuePageHTMLBackOffice::getFooter();
    }

    private function index()
    {
        $app = \Slim\Slim::getInstance();
        return <<<end
        <h1>Dépôt d’une demande de partenariat / sponsoring / mécénat</h1>
end;
    }

    private function formulaire()
    {
        return <<<end
            <div class="container">
                <p>Labeloui</p>
            </div>
end;
    }
        
    private function projet(){
        return <<<end
        <div class="container row">
                <div class="card hoverable">
                  <div class="card-content">
                    <div class="col s6">
                        <p>Nom du quiz : PremierQuizTest</p>
                    </div>
                    <div class="col s6">
                        <p>Date de création : 2018-03-12</p>
                    </div>
                  </div>
                  <div class="card-tabs">
                    <ul class="tabs tabs-fixed-width">
                      <li class="tab"><a class="active" href="#14c1">Projet</a></li>
                      <li class="tab"><a href="#14c2" class="">Structure</a></li>
                      <li class="tab"><a href="#14c3" class="">Représentant</a></li>
                      <li class="tab"><a href="#14c4" class="">Responsable</a></li>
                      <li class="tab"><a href="#14c4" class="">Impliqué(s)</a></li>
                    <div class="indicator"></div></ul>
                  </div>
                  <div class="card-content grey lighten-4">
                    <div id="14c1" class="active" style="display: block;">
                    
                    </div>
                    <div id="14c2" class="" style="display: none;">
                    
                    </div>
                    <div id="14c3" class="" style="display: none;">
                    
                    </div>
                    <div id="14c4" class="" style="display: none;">
                    
                    </div>
                  </div>
                </div>
            </div>
end;
    }
}