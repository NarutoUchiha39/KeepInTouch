<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"] ."/includes/DBConnect.php";
    include $_SERVER["DOCUMENT_ROOT"] ."/utils/cloudinaryConnection.php";
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $conn = connect();
        
        if($_POST["option"] == 1){

            $username = $_SESSION["username"];
            $message = $_POST["message"];
            $receiver = $_POST["reciever"];
            
            if(strlen($_POST["message"]) == 0  && count($_FILES) == 0){
                header("application/json");
                echo json_encode(array("status"=>"error","message"=>"Empty message not allowed"));
            }
            if(count($_FILES) != 0){

                $fileName= $_FILES["attachment"]["name"];
                $extension = $_FILES["attachment"]["type"];
                $temp_location = $_FILES["attachment"]["tmp_name"];
                $fileSize = $_FILES["attachment"]['size'];
                $errors = $_FILES["attachment"]['error'];
                $allowed_types = array("jpg","jpeg","png","pdf");


                if($errors != 0){
                    header("application/json");
                    echo json_encode(array("errors"=>$fileSize,"status"=>"error","message"=>"There were some errors uploading the file"));
                    die();
                }else{

                            $file_details = explode(".",$fileName);
                            $fileActualExt  = strtolower(end($file_details));
                            if(in_array($fileActualExt,$allowed_types)){

                                if($fileSize > 8388608){
                                    header("application/json");
                                    echo json_encode(array("status"=>"error","message"=>"files less than 50MB are allowed"));
                                }else{
                                    $time = time();
                                    $fileNameActual = $username."_".$time;
                                    
                                    $res = upload_image($temp_location,$fileNameActual,$fileActualExt);
                                    if($res){
                                        $stmt2 = pg_prepare($conn,"insert_media","INSERT INTO messages(sender,reciever,message,type) 
                                        VALUES($1,$2,$3,$4)");

                                        $res = pg_execute($conn,"insert_media",array($username,$receiver,$res["secure_url"],$fileActualExt));
                                        if($res){
                                            if(strlen($_POST["message"]) == 0){
                                                header("application/json");
                                                echo json_encode(array("status"=>"success","message"=>"file sent"));
                                            }
                                        }else{
                                            header("application/json");
                                            echo json_encode(array("status"=>"error","message"=>"There was issue uploading file"));
                                        }

                                    }else{
                                        $_SESSION["error"][] = "There was some issue uploading the file :(";
                                        header("Location: "."/");
                                        die();
                                    }                                        
                                }

                        }else{
                            header("application/json");
                            echo json_encode(array("status"=>"error","message"=>$fileActualExt));
                        }
                    
                }
            }
            
            if(strlen($_POST["message"]) != 0){
            
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