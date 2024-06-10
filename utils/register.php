<?php

require ($_SERVER['DOCUMENT_ROOT']."/includes/DBConnect.php");
require ($_SERVER['DOCUMENT_ROOT']."/utils/getURI.php");
require $_SERVER["DOCUMENT_ROOT"] . "/utils/mail.php";


session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $resume_link = $_POST['resume'];
    $key = UnsafeCrypto::getEnv('key');
    $key = hex2bin($key);
    $encrypted_password  = UnsafeCrypto::encrypt($password, $key, true);
    $allowed_types = array("jpg","jpeg","png");
    
            $fileName = $_FILES['profile']['name'];
            $fileType = $_FILES['profile']['type'];
            $errors = $_FILES['profile']['error'];
            $fileTempLocation = $_FILES['profile']['tmp_name'];
            $fileSize = $_FILES['profile']['size'];


        
            if($errors != 0){
                $_SESSION["error"][] = "There were some errors uploading files";
                header("Location: /pages/register-page.php");
                die();
            }else{
                if($fileSize === 0 ){
                    
                    $_SESSION["error"][] = "Empty file not allowed $fileName $fileType $errors $fileSize";
                    header("Location: "."/pages/register-page.php");
                    die();
                }else{
                    $file_details = explode(".",$fileName);
                    $fileActualExt  = strtolower(end($file_details));
                    if(in_array($fileActualExt,$allowed_types)){
                        
                        if($fileSize > 5000000){
                            $_SESSION["error"][] = "File less than 50MB is allowed";
                            header("Location: "."/pages/register-page.php");
                            die();
                        }else{

                                    $conn = connect();
                                    try{
                                        $check_user = pg_prepare($conn,"check_user5",
                                        "select COUNT (*) from custom_user where email=$1"
                                        );

                                        
                                            $res = pg_execute($conn,"check_user5",array($email));
                                            
                                            while($row = pg_fetch_assoc($res)){
                                                if($row["count"] != 0){
                                                    pg_free_result($res);
                                                    $_SESSION["error"] []= "Username or Email already exists";
                                                    header("Location: "."/pages/register-page.php");
                                                    die();
                                                }
                                            }

                                            pg_free_result($res);

                                            
                                            $time = time();
                                            $exp = $time+60;
                                            $unique = uniqid(true);
                                            $verify = pg_prepare($conn,"verify_user4","INSERT INTO register_user(email,request_at,expiry,code) VALUES($1,$2,$3,$4)");

                                            $res2 = pg_execute($conn,"verify_user4",array($email,$time,$exp,$unique));
                                            if($res2){
                                                move_uploaded_file($fileTempLocation,$_SERVER["DOCUMENT_ROOT"] . "/uploads/$email.$fileActualExt");
                                                $_SESSION["temp"]["username"] = $name;
                                                $_SESSION["temp"]["email"] = $email;
                                                $_SESSION["temp"]["password"] = $encrypted_password;
                                                $_SESSION["temp"]["fileName"] = $fileName;
                                                $_SESSION["temp"]["tempLocation"] = $_SERVER["DOCUMENT_ROOT"] ."/uploads/$email.$fileActualExt";
                                                $_SESSION["temp"]["fileActualExt"] = $fileActualExt;
                                                send_mail($email,$unique);
                                                header("Location: /pages/email-verification.php");
                                                die();
                                            }else{
                                                $_SESSION["error"][]= "Something went wrong Try refreshing the page :(";
                                                header("Location: /pages/register-page.php");

                                            }
                                    }catch(Exception $e){
                                        $_SESSION["error"][]= "Something went wrong Try refreshing the page :(";
                                        header("Location: /pages/register-page.php");
                                        
                                    }
                                    
                                  
                                
                        }

                    }else{
                        $_SESSION["error"][] = "File type should be png or jpg/jpeg only";
                        header("Location: "."/pages/register-page.php");
                        die();
                    }

                }
            }
           
        
}

?>
