<?php
namespace dbproject\conf;

use dbproject\modele\Projet;
use dbproject\modele\Suivi;

class Uploads
{

    public function ajoutFichierFormulaire($id)
    {
        if ($_POST['nbFile'] > 0) {
            $aremplacer = array(
                " ",
                "'",
                "."
            );
            $str = str_replace($aremplacer, "_", $_POST['nomstruct']);
            $str = strtolower($str);
            
            $date = new \DateTime();
            $dir = Variable::$dossierFichier . "/" . $id . "_" . $str . "_" . $date->format('d_m_Y_H_i_s') . "/";
            
            $oldmask = umask(0);
            mkdir($dir, 0777);
            umask($oldmask);
            
            foreach (Variable::$dossierSpecifique as $ds) {
                $dir = Variable::$dossierFichier . "/" . $id . "_" . $str . "_" . $date->format('d_m_Y_H_i_s') . "/" . $ds . "/";
                $oldmask = umask(0);
                mkdir($dir, 0777);
                umask($oldmask);
            }
            
            $dir = Variable::$dossierFichier . "/" . $id . "_" . $str . "_" . $date->format('d_m_Y_H_i_s') . "/";
            
            $x = $_POST['nbFile'];
            $y = 0;
            $liste = array();
            while ($x > 0) {
                if (isset($_FILES['fileToUpload' . $y])) {
                    if (! ($val = self::ajoutFichier($dir, Variable::$dossierSpecifique[0], 'fileToUpload' . $y)))
                        return false;
                    array_push($liste, $val);
                    $x --;
                }
                
                $y ++;
            }
            
            if (! self::creationZip($dir, Variable::$dossierSpecifique[0], $liste))
                return false;
        }
        return true;
    }

    public function creationZip($dir, $dossierSpecifique, $liste)
    {
        $zip = new \ZipArchive();
        $filename = $dir . $dossierSpecifique . "/" . $_POST['nomstruct'] . "_projet.zip";
        
        if ($zip->open($filename, \ZipArchive::CREATE) !== TRUE) {
            exit("Impossible d'ouvrir le fichier <$filename>\n");
        }
        
        foreach ($liste as $l)
            $zip->addFile($dir . $dossierSpecifique . "/" . $l, $l);
        echo "Nombre de fichiers : " . $zip->numFiles . "\n";
        echo "Statut :" . $zip->status . "\n";
        return $zip->close();
    }

    public function ajoutFichier($dir, $dossierSpecifique, $fileName)
    {
        $target_dir = $dir . $dossierSpecifique . "/";
        $target_file = $target_dir . basename($_FILES[$fileName]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        /**
         * if(isset($_POST["submit"])) {
         * $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
         * if($check !== false) {
         * echo "File is an image - " .
         * $check["mime"] . ".";
         * $uploadOk = 1;
         * } else {
         * echo "File is not an image.";
         * $uploadOk = 0;
         * }
         * }
         */
        // Check if file already exists
        if (file_exists($target_file)) {
            // echo "Désolé, le fichier existe déjà.";
            $uploadOk = 0;
        }
        
        // Check file size
        if ($_FILES[$fileName]["size"] > 2000000) {
            // echo "Désolé, le fichier est trop large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if (/**
         * $imageFileType != "odt" && $imageFileType != "docx" && $imageFileType != "pdf"
         */
        ! in_array($imageFileType, Variable::$formatAutorise)) {
            // echo "Désolé, seuls les fichiers ODT, DOCX & PDF sont autorisés.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Désolé, votre fichier n'a pas été uploadé.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES[$fileName]["tmp_name"], $target_file)) {
                // echo "Le fichier " . basename($_FILES[$fileName]["name"]) . " a été uploadé.";
                return basename($_FILES[$fileName]["name"]);
            } else {
                // echo "Désolé, il y a eu une erreur avec l'upload du fichier.";
            }
        }
        return null;
    }

    public function ajoutFichierMultiples($dir, $dossierSpecifique, $fileName)
    {
        $target_dir = $dir . $dossierSpecifique . "/";
        for ($i = 0; $i < sizeof(($_FILES[$fileName]['name'])); $i ++) {
            $target_file = $target_dir . basename($_FILES[$fileName]['name'][$i]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            /**
             * if(isset($_POST["submit"])) {
             * $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
             * if($check !== false) {
             * echo "File is an image - " .
             * $check["mime"] . ".";
             * $uploadOk = 1;
             * } else {
             * echo "File is not an image.";
             * $uploadOk = 0;
             * }
             * }
             */
            // Check if file already exists
            if (file_exists($target_file)) {
                $_SESSION['message'] = "Désolé, parmis le(s) fichier(s) uploadé(s), un existe déjà.";
                $uploadOk = 0;
            }
            
            // Check file size
            if ($_FILES[$fileName]["size"][$i] > 2000000) {
                $uploadOk = 0;
            }
            // Allow certain file formats
            if (! in_array($imageFileType, Variable::$formatAutorise)) {
                $_SESSION['message'] = "Désolé, seuls les fichiers ODT, DOCX & PDF sont autorisés. Parmis le(s) fichier(s) uploadé(s), un ne respecte pas cette condition";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                return null;
            } else {
                if (move_uploaded_file($_FILES[$fileName]["tmp_name"][$i], $target_file)) {
                    echo "Le fichier " . basename($_FILES[$fileName]["name"][$i]) . " a été uploadé.";
                    $_SESSION['message'] = "L'ajout de un ou des fichier(s) a été effectué avec succès.";
                } else {
                    $_SESSION['message'] = "Désolé, il y a eu une erreur avec l'upload de un ou des fichier(s).";
                }
            }
        }
        return null;
    }

    public function supprimerFichier($id, $nom)
    {
        // Dossier avec tout les dossiers
        $list = scandir(Variable::$path . "\\" . Variable::$dossierFichier);
        
        foreach ($list as $l) {
            $no = explode("_", $l)[0];
            if ($no == $id) {
                
                // Dossier avec tout les fichiers recherchés
                $list2 = scandir(Variable::$path . "\\" . Variable::$dossierFichier . "\\" . $l . "\\" . Variable::$dossierSpecifique[1]);
                
                foreach ($list2 as $i) {
                    if ($i == $nom) {
                        unlink(Variable::$path . "\\" . Variable::$dossierFichier . "\\" . $l . "\\" . Variable::$dossierSpecifique[1] . "\\" . $i);
                        $_SESSION['message'] = "Le fichier '$nom' a été supprimé avec succès.";
                        $folder = scandir(Variable::$path . "\\" . Variable::$dossierFichier . "\\" . $l . "\\" . Variable::$dossierSpecifique[1]);
                        $i = 0;
                        foreach ($folder as $f)
                            if ($f != "." && $f != "..")
                                $i ++;
                        $projet = Projet::getById($id);
                        $suivi = Suivi::getById($projet->IdSuivi);
                        $suivi->Document = $i;
                        $suivi->save();
                    }
                }
                if ($_SESSION['message'] == null)
                    $_SESSION['message'] = "Le fichier '$nom' n'a pas été trouvé. La suppression n'a pas été effectuée.";
            }
        }
    }

    public function supprimerFichierFormulaire($id)
    {
        
        // Dossier avec tout les dossiers
        $list = scandir(Variable::$path . "\\" . Variable::$dossierFichier);
        $fichiers = array();
        
        foreach ($list as $l) {
            $no = explode("_", $l)[0];
            if ($no == $id) {
                
                foreach (Variable::$dossierSpecifique as $ds) {
                    // Dossier avec tout les fichiers recherchés
                    $list2 = scandir(Variable::$path . "\\" . Variable::$dossierFichier . "\\" . $l . "\\" . $ds);
                    
                    $zip = null;
                    foreach ($list2 as $i) {
                        if ($i != "." && $i != "..") {
                            unlink(Variable::$path . "\\" . Variable::$dossierFichier . "\\" . $l . "\\" . $ds . "\\" . $i);
                        }
                    }
                    rmdir(Variable::$path . "\\" . Variable::$dossierFichier . "\\" . $l . "\\" . $ds);
                }
                
                rmdir(Variable::$path . "\\" . Variable::$dossierFichier . "\\" . $l);
            }
        }
        
        return true;
    }
}