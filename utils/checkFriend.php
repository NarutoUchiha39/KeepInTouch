<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/DBConnect.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];
        $conn = connect();

        $stmt = pg_prepare($conn,"check_user","SELECT COUNT(*) FROM custom_user WHERE username=$1");    
        $res = pg_execute($conn,"check_user",array($username));
        if($res){

            $assoc = pg_fetch_assoc($res);
            if($assoc["count"] == 0){
                $_SESSION["error"][] = "User doesnot exists :(";
                header("Location: /");
                exit();
                die;
            }else{
               $stmt2 = pg_prepare($conn,"friend_request","INSERT INTO friend_requests(sent_by,sent_to) VALUES($1,$2)");
               $cur_username = $_SESSION["username"];
               $res2 = pg_execute($conn,"friend_request",array($cur_username,$username));
               if($res2){
                    $_SESSION["success"][] = "Request sent successfully :D";
                    header("Location: /");
                    exit();
                    die;

               }else{
                    $_SESSION["error"][] = "something went wrong :(. Try again later";
                    header("Location: /");
                    exit();
                    die;
               }
               
            }

        }else{
            $_SESSION["error"][] = "something went wrong :(. Try again later";
            header("Location: /");
            exit();
            die;
        }
    }
?>