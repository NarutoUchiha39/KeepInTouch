<?php
session_start();

require ($_SERVER['DOCUMENT_ROOT']."/includes/DBConnect.php");
require ($_SERVER['DOCUMENT_ROOT']."/utils/getURI.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = $_POST['email'];
        $password_user = $_POST['password'];

        $conn = connect();
        $res = pg_query($conn, "SELECT username,password,email,profile_link FROM custom_user WHERE custom_user.email='$email'");
        if (!$res) {
            echo "Query failed: " . pg_last_error($conn);
            exit;
        }

        $row = pg_fetch_assoc($res);
        
        

        if(!$row){
            $_SESSION["error"][] = "User doesnot exist";
            header("Location: "."/pages/login-page.php");
        }else{
            $user_name = $row['username'];
            $email = $row['email'];
            $url = $row['profile_link'];
            $key = UnsafeCrypto::getEnv('key');
            $password = $row['password'];
            $key = hex2bin($key);
            $res = UnsafeCrypto::decrypt($password,$key,true);
        
        
            if($password_user == $res){
                $_SESSION["username"] = $user_name;
                $_SESSION['email'] = $email;
                $_SESSION["success"][] = "Logged in successfully";
                $_SESSION["url"] = $url ;
                header("Location: "."/");
                die();
            }else{
                
                $_SESSION["error"][] = "Wrong password";
                header("Location: "."/pages/login-page.php");
            }
        }

    
    }

   
    

