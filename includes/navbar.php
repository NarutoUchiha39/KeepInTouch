<?php
    session_start();
 
?>

<?php 
      $utils = $_SERVER['DOCUMENT_ROOT']."/utils/getURI.php";
?>

<?php include($utils) ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    
    <link rel="stylesheet" type="text/css" href="<?php echo url('/css/navbar.css')?>"/>
    <?php if(isset($stylesheet)){ ?>
        
        <link rel="stylesheet" type="text/css" href="<?php echo url($stylesheet) ?>"/>

    <?php } ?>

</head>
<body>
    <nav>
        <div class="NavBar">
            <div class="Logo">
                    Keep In Touch
            </div>
            <div class="Login_Logout">
            <?php if(!isset($_SESSION['username'])){ ?>
                        <a href="<?php echo url('/pages/login-page.php') ?>">Login </a>
            <?php } ?>        
            
            <?php if(isset($_SESSION['username'])){ ?>
             
                        <div style="display: flex;position:relative;right:4rem">
                            <a href="<?php echo url('/pages/friend-request.php') ?>" style="display:flex;justify-content:center;align-items:center;">Requests </a>

                            <a href="<?php echo url('/pages/logout.php') ?>" style="display:flex;justify-content:center;align-items:center;margin-left:1rem">Logout </a>
                            
                            <img src="<?php echo $_SESSION["url"] ?>" style="width:50px;border-radius:50%;height:50px;margin-left:1rem"  />
                        </div>
            <?php }else{ ?>
                        <a href="<?php echo url('/pages/register-page.php') ?>">Register </a>
            <?php } ?>
                     
            </div>
        </div>
    </nav>

