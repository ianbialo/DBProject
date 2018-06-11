<?php
require_once 'src/conf/Variable.php';
use dbproject\conf\Variable;

//Script permettant la création de la base de données, de l'utilisateur associé et des données ainsi que des dossiers de fichiers.

print "Création du dossier allant contenir les fichiers des projets...\n";
$dir = Variable::$dossierFichier;
if(file_exists($dir)){
    print "Le dossier ".$dir." existe déja. Suppression du contenu en cours...\n";
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir")
                        rrmdir($dir."/".$object);
                        else unlink   ($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
    rrmdir($dir);
    print "Le dossier ".$dir." et son contenu ont été supprimés. Recréation du dossier iminente.\n";
}
print "Création du dossier ".$dir."...\n";
$old = umask(0);
mkdir($dir,0777);
umask($old); 
print "Le dossier ".$dir." a été créé.\n";


print "Création du fichier de configuration de la BDD, renseignez les différents paramètres:\n";
$port = readline("Entrez le port (validez directement si '3306'): ");
if($port == null) $port = "3006";
$host = readline("Entrez l'host (validez directement si 'localhost'): ");
if($host == null) $host = "localhost";
do $bdd = readline("Entrez le nom de la BDD : ");
while($bdd == null);
do $bdd_user = readline("Entrez le nom d'utilisateur de la BDD : ");
while($bdd_user == null);
do $bdd_mdp = readline("Entrez le mot de passe de l'utilisateur de la BDD : ");
while($bdd_mdp == null);
print "Génération du fichier de configuration ...\n";

$conf_ini = "[connection]\ndriver = mysql\nport = $port\nhost = $host\ndatabase = $bdd\nusername = $bdd_user\npassword = $bdd_mdp\ncharset = utf8\ncollation = utf8_general_ci";
$crea_conf = fopen("./src/conf/conf.ini", "w+");
if($crea_conf==false)
    print("La création du fichier a échoué, vérifiez vos droits\n");
    fwrite($crea_conf, $conf_ini);
    fclose($crea_conf);
    
    print "Accès à mysql.\n";
    $mysql_login = readline("Entrez le login de connexion mysql (validez directement si 'root'): ");
    if($mysql_login == null) $mysql_login = "root";
    do $mysql_pass = readline("Entrez le mot de passe mysql : ");
    while($mysql_pass == null);
    
    print "Suppression des données parasites\n";
    $commande1 = <<<end
mysql -u $mysql_login -p$mysql_pass -Bse "DROP DATABASE IF EXISTS $bdd;GRANT USAGE ON *.* TO '$bdd_user'@'$host';DROP USER '$bdd_user'@'$host';"
end;
    exec($commande1,$test);
    
    print "Creation de la base de données et de du compte associé à celui-ci...\n";
    $commande2 = <<<end
mysql -u $mysql_login -p$mysql_pass -Bse "CREATE DATABASE dbdatabase;USE dbdatabase;CREATE USER '$bdd_user'@'$host' IDENTIFIED BY '$bdd_mdp';GRANT ALL ON $bdd.* TO $bdd_user@$host;FLUSH PRIVILEGES;"
end;
    exec($commande2);
    
    print "Création des données et des dépendances...\n";
    exec('mysql -u '.$mysql_login.' -p '.$bdd.' --password='.$mysql_pass.' < ./doc/SQL/dbdatabase.sql');
    
    print "Fin du déploiement.\n";