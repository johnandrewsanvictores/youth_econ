<?php

require __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$db_server = $_ENV['DATABASE_HOSTNAME'];
$db_user = $_ENV['DATABASE_USERNAME'];
$db_pass = $_ENV['DATABASE_PASSWORD'];
$db_name = $_ENV['DATABASE_NAME'];


define('USER', $_ENV['DATABASE_USERNAME']);
define('PASSWORD', $_ENV['DATABASE_PASSWORD']);
define('HOST', $_ENV['DATABASE_HOSTNAME']);
define('DATABASE', $_ENV['DATABASE_NAME']);
try {

    $connection = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
} catch (PDOException $e) {

    exit("Error: " . $e->getMessage());
}
