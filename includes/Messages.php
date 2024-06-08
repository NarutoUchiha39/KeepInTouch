<?php

    $success = array();
    $error = array();

   
    function error($message){
        global $error;
        $error[] = $message;
    }


    function success($message){
        global $success;
        $success[] = $message;
    }

?>