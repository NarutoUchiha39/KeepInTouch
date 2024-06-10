<?php

    $prepared = [
        "check_user" => "select COUNT (*) from custom_user where email=$1",
        "verify_user1"=> "verify_user1","INSERT INTO register_user(email,request_at,expiry,code) VALUES($1,$2,$3,$4)"
    ]

?>