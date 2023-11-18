<?php
define('DB_SERVER','mysql:host=localhost; dbname=minecraft-blog; charset=utf8');
define('DB_USER', 'root');
define('DB_PASS', '');

try{
    $db = new PDO(DB_SERVER, DB_USER, DB_PASS);
} catch (PDOException $e){
    print "Error: " . $e->getMessage();

    //Force execution to stop on errors
    die(); 
}
session_start();
?>