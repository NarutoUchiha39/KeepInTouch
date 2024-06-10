<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/DBConnect.php";
    require ($_SERVER['DOCUMENT_ROOT']."/utils/cloudinaryConnection.php");
    require($_SERVER["DOCUMENT_ROOT"]. "/utils/cleanUp.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        

    $conn = connect();
    $name = $_SESSION["temp"]["username"] ;
    $email= $_SESSION["temp"]["email"] ;
    $password = $_SESSION["temp"]["password"];
    $fileName = $_SESSION["temp"]["fileName"] ;
    $fileTempLocation = $_SESSION["temp"]["tempLocation"];
    $fileActualExt = $_SESSION["temp"]["fileActualExt"];
    

    $prep = pg_prepare($conn,"verify_user","select code,expiry from register_user where email=$1");
    $res = pg_execute($conn,"verify_user",array($email));
    $code = null;
    $exp=null;
    while($row = pg_fetch_assoc($res)){
                $code = $row["code"];
                $exp = $row["expiry"];
    }

    if($code != null){

        $num = intval($exp);
        
            if( time() > $num){
                session_unset();
                $_SESSION["error"][] = "Time expired :(";
                cleanUp($email,$fileActualExt,$conn);
                header("Location: /pages/register-page.php");
                die();
            }else{

                if($_POST["email_code"] == $code){
                        
                    $res = upload_image($fileTempLocation,$name,$fileActualExt);                                  
                    $stmt = pg_prepare($conn, "insert_user", "INSERT INTO custom_user(username, password, email, resume_link,profile_link) VALUES ($1, $2, $3, $4, $5)");
                    $result = pg_execute($conn, "insert_user", array($name, $password, $email, $resume_link,$res["secure_url"]));
                    if($result){
                                                        
                                    $_SESSION["success"][] = "Registered Successfully";
                                    $_SESSION['username'] = $name;
                                    $_SESSION['email'] = $email;
                                    $_SESSION["url"] =  $res["secure_url"];
                                    $_SERVER["success"][] = "User registered";
                                                            
                                    pg_free_result($result);
                                    cleanUp($email,$fileActualExt,$conn);
                                    header("Location: /");
                                    die();

                                    }
                }else{
                    
                    $_SESSION["error"][] = "Wrong code entered try again!";
                    header("Location: "."/pages/email-verification.php");
                    die();
                }

            }
    }else{

    }

}
?>