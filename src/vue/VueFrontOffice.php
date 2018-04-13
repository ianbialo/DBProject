<?php
namespace dbproject\vue;

class VueFrontOffice
{

    const AFF_INDEX = 0;

    public function render($selecteur, $tab = null)
    {
        switch ($selecteur) {
            case VueFrontOffice::AFF_INDEX:
                $content = $this->index();
                break;
        }
        return VuePageHTMLFrontOffice::header() . $content . VuePageHTMLFrontOffice::getFooter();
    }

    private function index()
    {
        $app = \Slim\Slim::getInstance();
        $val = $app->urlFor("postFormulaire");
        return <<<end
        <h1>Formulaire à remplir</h1>
            <form method="POST" action="$val" enctype="multipart/form-data">
                <div class="info">
                    <label>Nom du représentant légal *</label>
                    <input type="text" id="rpzlegal" name="rpzlegal" autofocus required>
                </div>
                <div class="info">
                    <label>Qualité *</label>
                    <input type="text" id="qualite" name="qualite" value="chercher les qualités" required>
                </div>
                <div class="info">
                    <label>Nom du représentant du projet *</label>
                    <input type="text" id="rpzprojet" name="rpzprojet" required>
                </div>
                <div class="info">
                    <label>Position dans la structure *</label>
                    <input type="text" id="position" name="position" value="chercher les positions" required>
                </div>
                <div class="info">
                    <label>Adresse *</label>
                    <input type="text" id="adr" name="adr" required>
                    <label>Code Postal *</label>
                    <input type="text" id="cpostal" name="cpostal" required>
                    <label>Ville *</label>
                    <input type="text" id="cpostal" name="cpostal" required>
                </div>
                <div class="info">
                    <label>Téléphone *</label>
                    <input type="text" id="tel" name="tel" required>
                </div>
                <div class="info">
                    <label>Courriel *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <br>
                <div class="info">
                    <label>Exposé synthétique du projet ou des actions à soutenir *</label>
                    <textarea rows="4" cols="50" style="resize:none" required></textarea>
                </div>
                <div class="info">
                    <input type="file" name="fileToUpload" id="fileToUpload" required>
                </div>
                <input type="submit" value="Valider" name="submit">
            </form>
end;
    }
}