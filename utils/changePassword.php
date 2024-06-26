<?php
    session_start();
    include($_SERVER["DOCUMENT_ROOT"]."/includes/DBConnect.php");
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_SESSION["temp"]["email"];
        $password = $_POST["password"];
        $key = UnsafeCrypto::getEnv('key');
        $key = hex2bin($key);
        $encrypted_password = UnsafeCrypto::encrypt($password,$key,true);
        $conn = connect();
        $stmt = pg_prepare($conn,"change_password","UPDATE custom_user SET password=$1 WHERE email=$2");
        $stmt2 = pg_prepare($conn,"delete_nonce","DELETE FROM register_user WHERE email=$1");

        $res = pg_execute($conn,"change_password",array($encrypted_password,$email));
        $res2 = pg_execute($conn,"delete_nonce",array($email));
        if($res && $res2){
            $_SESSION["success"][] = "Password changed successfully. Login to continue";
            header("Location: /pages/login-page.php");
            die;
        }else{
            $_SESSION["error"][] = "Something went wrong :( .Try again later";
            header("Location: /pages/login-page.php");
            die;
        }

    }
?>