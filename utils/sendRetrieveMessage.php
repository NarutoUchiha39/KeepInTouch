<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"] ."/includes/DBConnect.php";
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $conn = connect();
        
        if($_POST["option"] == 1){

            $username = $_SESSION["username"];
            $message = $_POST["message"];
            $receiver = $_POST["reciever"];

            $stmt1 = pg_prepare($conn,"insert_message1","INSERT INTO messages(sender,reciever,message) VALUES(
                    $1,$2,$3
            )");

            $res = pg_execute($conn,"insert_message1",array($username,$receiver,$message));
            if($res){
                
                header("application/json");
                echo json_encode(array("status"=>"success"));
            }else{
                header("application/json");
                echo json_encode(array("status"=>"error"));
            }
        }elseif($_POST["option"] == 2){

            $username = $_SESSION["username"];
            $reciever = $_POST["reciever"];

            $query = pg_prepare($conn,"getMessages3",
            "SELECT * 
            FROM (
                SELECT *
                FROM messages m 
                WHERE m.sender = $1 AND m.reciever = $2

                UNION

                SELECT *
                FROM messages m 
                WHERE m.sender = $2 AND m.reciever = $1

            ) AS all_messages 
            ORDER BY all_messages.id 

            ");

            $res = pg_execute($conn,"getMessages3",array($username,$reciever));
            if($res){
                    $messages = pg_fetch_all($res);
                    header("application/json");
                    echo json_encode(array("status"=>"success","messages"=>$messages));
            }else{

                header("application/json");
                echo json_encode(array("status"=>"error"));
            }

        }
    }


?>