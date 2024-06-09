<?php

require ($_SERVER['DOCUMENT_ROOT']."/includes/DBConnect.php");
require ($_SERVER['DOCUMENT_ROOT']."/utils/getURI.php");
require ($_SERVER['DOCUMENT_ROOT']."/utils/cloudinaryConnection.php");
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
                            $_SESSION["error"][] = "File less than {$fileSize} MB is allowed";
                            header("Location: "."/pages/register-page.php");
                            die();
                        }else{
                            
                                    $res = upload_image($fileTempLocation,$name,$fileActualExt);

                                    $conn = connect();
                                    $stmt = pg_prepare($conn, "insert_user", "INSERT INTO custom_user(username, password, email, resume_link,profile_link) VALUES ($1, $2, $3, $4, $5)");

                                    $result = pg_execute($conn, "insert_user", array($name, $encrypted_password, $email, $resume_link,$res["secure_url"]));

                                    if($result){
                                    
                                        $_SESSION["success"][] = "Registered Successfully";
                                        $_SESSION['username'] = $name;
                                        $_SESSION['email'] = $email;
                                        $_SESSION["url"] =  $res["secure_url"];
                                        
                                        pg_free_result($stmt);
                                        header("Location: "."/");
                                        die();

                                    }else{
                                        $_SESSION["error"] []= "Username or Email already exists";
                                        header("Location: "."/pages/register-page.php");
                                        die();
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
