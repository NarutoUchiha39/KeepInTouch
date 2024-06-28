<?php
    include $_SERVER["DOCUMENT_ROOT"] . "/includes/DBConnect.php";
    $conn = connect();

    $stmt = pg_prepare($conn,"retrieve_all_messages3",
    "SELECT 
        CASE
            WHEN f.username = $1 THEN f.friend
            ELSE f.username
        END AS username,
        profile_link

        FROM (

            SELECT username,friend
            FROM friends
            WHERE username = $1

            UNION

            SELECT username,friend
            FROM friends
            WHERE friend = $1

        ) AS f 
        LEFT JOIN custom_user cu ON (
            CASE 
                WHEN f.username = $1 THEN f.friend
                ELSE f.username
            END = cu.username
        )
    ");

    $res =  pg_execute($conn,"retrieve_all_messages3",array($_SESSION["username"]));

    if($res){
        $people = pg_fetch_all($res);

    }else{
        $_SESSION["error"][] = "Something went wrong :(";
        header("Location: /");
        exit();
        die;
    }

?>