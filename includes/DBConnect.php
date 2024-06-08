<?php 

include ($_SERVER["DOCUMENT_ROOT"]."/utils/passwordEncryption.php");

function connect(){
    $name = UnsafeCrypto::getEnv('user');
    $password = UnsafeCrypto::getEnv("password");
    $host = UnsafeCrypto::getEnv("host");
    $Dbname = UnsafeCrypto::getEnv("dbname");
    
    $conn = pg_connect("user=$name 
    password=$password 
    host=$host
    port=6543 
    dbname=$Dbname");

    return $conn;
}

?>