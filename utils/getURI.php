<?php 

    function getFilePath($parameter){

        return $_SERVER['DOCUMENT_ROOT'].$parameter;
    }

    function url($parameter){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }else{
            $protocol = 'http';
        }

        return $protocol . "://" . $_SERVER['HTTP_HOST']. $parameter;
    }
?>