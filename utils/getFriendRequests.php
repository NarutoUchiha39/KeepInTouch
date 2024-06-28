<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/DBConnect.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $option = $_POST['update'];
        $username = $_SESSION["username"];
        $conn = connect();

        if($option == "0"){

            $cache = array();
           

            function sentRequest($conn,$username)
            {
                
                    $stmt = pg_prepare($conn,"select_friends2","SELECT sent_by,sent_to,profile_link,status 
                    FROM friend_requests 
                    left join custom_user 
                    on friend_requests.sent_to = custom_user.username 
                    where friend_requests.sent_by = $1");

                    $res = pg_execute($conn,"select_friends2",array($username));

                    if($res){

                        $assoc = pg_fetch_all($res);                
                    }else{
                        $_SESSION["error"][] = "Something went wrong";
                        header("Location: /");
                        exit();
                        die;
                    }
                
                return $assoc;
            }

            function recievedRequest($conn,$username){

                $stmt = pg_prepare($conn,"select_friends4","SELECT 
                sent_by,sent_to,profile_link,status 
                FROM friend_requests 
                left join custom_user 
                on friend_requests.sent_by = custom_user.username 
                where friend_requests.sent_to = $1");

                    $res = pg_execute($conn,"select_friends4",array($username));

                    if($res){

                        $assoc = pg_fetch_all($res);
                        
                    }else{
                        $_SESSION["error"][] = "Something went wrong";
                        header("Location: /");
                        exit();
                        die;
                    }
                
                return $assoc;
            }
            $sent = sentRequest($conn,$username);
            $recieved = recievedRequest($conn,$username);
            $cache["sent"] = $sent;
            $cache["recieved"] = $recieved;
            header('Content-type: application/json');
            echo json_encode($cache);

        } elseif($option == "1") {
            $sender = $_POST["sender"];
            $stmt2  = pg_prepare($conn,"delete_from_friend_request","DELETE FROM friend_requests WHERE sent_to=$1 AND sent_by=$2"); 
            $stmt3 = pg_prepare($conn,"insert_into_friends","INSERT INTO friends(username,friend) VALUES( $1,$2 )");

            $res2 = pg_execute($conn,"delete_from_friend_request",array($username,$sender));
            $res3 = pg_execute($conn,"insert_into_friends",array($username,$sender));

            if($res2 && $res3){
                header('Content-type: application/json');
                echo json_encode(array("status"=>"success"));
            }else{
                header('Content-type: application/json');
                echo json_encode(array("status"=>"error"));
            }
        }elseif($option == "2"){

            $sender = $_POST["sender"];
            $stmt3 = pg_prepare($conn,"friend_request_reject","UPDATE friend_requests SET status=$1 WHERE sent_to=$2 AND sent_by=$3");
            $res = pg_execute($conn,"friend_request_reject",array("rejected",$sender,$username));

            if($res){
                header('Content-type: application/json');
                echo json_encode(array("status"=>"success"));
            }else{
                header('Content-type: application/json');
                echo json_encode(array("status"=>"error"));
            }

        }
    }

?>