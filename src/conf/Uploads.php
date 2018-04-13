<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
/**if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}*/
// Check if file already exists
if (file_exists($target_file)) {
    echo "Désolé, le fichier existe déjà.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 2000000) {
    echo "Désolé, le fichier est trop large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "odt" && $imageFileType != "docx" && $imageFileType != "pdf") {
        echo "Désolé, seuls les fichiers ODT, DOCX & PDF sont autorisés.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Désolé, votre fichier n'a pas été uploadé.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "Le fichier ". basename( $_FILES["fileToUpload"]["name"]). " a été uploadé.";
        } else {
            echo "Désolé, il y a eu une erreur avec l'upload du fichier.";
        }
    }
?>