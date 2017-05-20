<?php
if(isset($_POST)){
    // Stockage des valeurs du POST
    $serveur = $_POST["serveur"];
    $bdd = $_POST["bdd"];
    $user = $_POST["user"];
    if(isset($_POST["mdp"])){$mdp = $_POST["mdp"];}else{$mdp = "";}
    $port = $_POST["port"];

    // Emplacement du fichier de configuration et de la BDD
    $conf = "../app/config/config.php";
    $scriptSQL = "../app/database/virtualhosts.sql";


    // Vérifier si le fichier de configuration existe, le supprimer pour le remplacer
    if(file_exists($conf)){
        unlink($conf);
    }


    // Créer le fichier avec les valeurs souhaitées
    fopen($conf, "w");
    $contenu ="<?php
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => '$serveur',
        'username'    => '$user',
        'password'    => '$mdp',
        'dbname'      => '$bdd',
        'charset'     => 'utf8',
        'port'        => '$port',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'testsDir'       => APP_PATH . '/tests/',
        'baseUri'        => '/virtualhosts/',
    ]
]);";


    // Enregistrer le contenu dans le fichier
    file_put_contents($conf, $contenu);


    // Connexion à la base
    $mysqli = new mysqli($serveur, $user, $mdp, null, $port);


    // Vérifier si la connexion c'est bien passée
    if($mysqli->connect_errno){
        echo "Impossible de se connecter à la base, merci de vérifier les informations du formulaire.";
        exit();
    }


    // Vérifier si la base existe, sinon la créer
    if($mysqli->select_db("$bdd") === true){
        $mysqli->query("DROP DATABASE $bdd");
    }

    $mysqli->query("CREATE DATABASE $bdd");
    $mysqli->select_db("$bdd");

    $ligneTemp = '';
    $lignes = file($scriptSQL);

    foreach ($lignes as $ligne) {
        if (substr($ligne, 0, 2) == '--' || $ligne == '')
            continue;
        $ligneTemp .= $ligne;
        if (substr(trim($ligne), -1, 1) == ';') {
            $mysqli->query($ligneTemp) or print($mysqli->error);
            $ligneTemp = '';
        }
    }


    // Après import, vérifier si la base n'est pas vide afin de lancer l'application
    $test = $mysqli->query("SELECT * FROM user");
    if($test->num_rows >= 2){
        rename("install.php","install_done.php");
        echo "<script>document.location.replace('index.php');</script>";
    }
}