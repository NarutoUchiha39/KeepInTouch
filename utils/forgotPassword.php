<?php
    session_start();
    include($_SERVER["DOCUMENT_ROOT"]."/includes/DBConnect.php");
    include($_SERVER["DOCUMENT_ROOT"]."/utils/mail.php");
    include($_SERVER["DOCUMENT_ROOT"]."/utils/getURI.php");

    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST["email"];
        $conn = connect();
        $nonce = uniqid(true);

        $link = url("/utils/forgotPassword.php?nonce=$nonce");
        
        $stmt = pg_prepare($conn,"insert_var_dump($res1);
            die;nonce","insert into register_user(email,request_at,expiry,code) values($1,$2,$3,$4)");
        $cur_time = time();
        $expiry_time = $cur_time + 60;    

        $check_count = pg_prepare($conn,"check_user","SELECT COUNT(*) FROM custom_user WHERE email=$1");
        $count = pg_execute($conn,"check_user",array($email));

        if($count){

            $res1 = pg_fetch_assoc($count);
            $count1 = $res1["count"];
            if($count1 != "0"){
                $res = pg_execute($conn,"insert_nonce",array($email,$cur_time,$expiry_time,$nonce));
                if($res){
                    $body = array("description"=>"We have received request to reset password for your account","link_code"=>$link,"body"=>"Click this link to reset your password. This link will expire in 1 minute");
                    
                    send_mail($email,$nonce,$body);
                    $_SESSION["success"][] = "Password recovery mail has been sent. Check your mailbox";
                    header("Location: /pages/forgotPassword.php");
                    exit();
                }
            }else{

                $_SESSION["error"][] = "User doesnot exist. Register to make an account";
                header("Location: /pages/register-page.php");
                exit();

            }
        }else{
            $_SESSION["error"][] = "Something went wrong try again later :(";
            echo $error;
            header("Location: /pages/forgotPassword.php");
            exit();
        }

        
    }else{
        
        $query = explode("?",$_SERVER["REQUEST_URI"]);
        $key_value = null;
        $request_nonce = null;
        if(count($query) != 0){
            $key_value = explode("=",$query[1]);
            if($key_value[0] == "nonce"){
                $request_nonce = $key_value[1];
                
            }else{
                header("Location: /pages/pageNotFound.php");
                exit();
            }
        }
        
        $conn = connect();
    }

?>