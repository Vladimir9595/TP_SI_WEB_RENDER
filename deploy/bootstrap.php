<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

date_default_timezone_set('America/Lima');
require_once "vendor/autoload.php";
$isDevMode = true;
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/config/yaml"), $isDevMode);
$conn = array(
    'host' => 'dpg-cm23hmvqd2ns73d8e1cg-a.frankfurt-postgres.render.com',
    'driver' => 'pdo_pgsql',
    'user' => 'si_web_sql_user',
    'password' => 'kmGK6TiocRkqoD5SgTn2p2muD2YFJeCE',
    'dbname' => 'si_web_sql',
    'port' => '5432'
);


try {
    $entityManager = EntityManager::create($conn, $config);
} catch (\Exception $e) {
    echo "Une erreur s'est produite : " . $e->getMessage();
}