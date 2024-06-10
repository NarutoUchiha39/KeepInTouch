<?php


    function cleanUp($email,$ext,$conn){

        unlink(realpath($_SERVER["DOCUMENT_ROOT"] . "/uploads/$email.$ext"));
        $statement = pg_prepare($conn,"delete_token","DELETE from register_user where email=$1");
        $res = pg_execute($conn,"delete_token",array($email));
        if($res){
            return 1;
        }else{
            return -1;
        }
    }

?>