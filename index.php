<?php
session_start();
if(!isset($_SESSION["username"])){

        header("Location: /pages/login-page.php");
        exit();
        die;
}

include $_SERVER["DOCUMENT_ROOT"] ."/utils/messages.php";
include $_SERVER["DOCUMENT_ROOT"] ."/utils/getURI.php"
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Keep In Touch</title>
        <link rel="stylesheet" href="./css/index.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 </head>
 <body>

<?php 
        
        if(isset($_SESSION["success"])){

        foreach($_SESSION["success"] as $success){
            ?>
            <div class="success">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    <?php
                            echo $success;
                    ?>
            </div>
     <?php }
        unset($_SESSION["success"]);
    }
     ?>

<?php 
        
        if(isset($_SESSION["error"])){

        foreach($_SESSION["error"] as $error){
            ?>
            <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    <?php
                            echo $error;
                    ?>
            </div>
     <?php }
        unset($_SESSION["error"]);
    }
     ?>


    <div class="container" style="display:flex;flex: 1;">
   
        
        <div class="main_content" style="flex: 1;">

                <div class="chat_conatiner" style="display:flex;flex-direction:column;border-right:1px solid gray">
                        <div class="user">
                                <div class="details">
                                        <img 
                                        style="margin-right:10px;height: 40px;width:40px;border-radius:1.1rem" 
                                        src="<?php echo $_SESSION["url"] ?>" alt="">

                                        <?php echo $_SESSION["username"] ?>
                                </div>

                                <div class="logout">
                                      <a href="<?php echo url('/pages/logout.php') ?>">
                                        <i class="fas fa-sign-out-alt" style="color:white;font-size: larger;"></i>
                                      </a>  
                                </div>
                                
                        </div>

                        <div class="chats" style="margin-top:1rem;flex:1">
                                <?php
                                        foreach($people as $row){ ?>
                                        <div  class="message"> 

                                                        <img style="margin-right:10px;
                                                        height: 40px;width:40px;border-radius:1.1rem" 
                                                        src="<?php echo $row["profile_link"] ?>" alt="">
                                                
                                                        <?php echo $row["username"]; ?>

                                                </div>
                                        <?php }
                                ?>
                        </div>

                        <div class="footer">
                                        
                                        <i onclick="show_modal()" style="cursor:pointer;color:white" class="fa fa-plus" aria-hidden="true"></i>
                                
                                        <a href="<?php echo url("/pages/friend-request.php") ?>">
                                                <i style="cursor:pointer;color:white" class="fa-solid fa-bell"></i>
                                        </a>

                                
                        </div>
                </div>
                

                <div class="display_chats" style="display:flex;flex-direction:column;margin-left:1px solid gray">

                        <div class="title_bar">
                                
                        </div>

                </div>
        </div>

        <form action="<?php echo url('/utils/checkFriend.php') ?>" method="POST" class="modal" id="modal">
                <div class="title">
                        <div class="txt">Add a friend</div>
                        <div class="close_button">
                                <div onclick="close_modal(event)">X</div>
                        </div>
                        
                </div>
                <div class="add_friend">
                        <label style="margin-bottom: 1rem;" for="username">Enter friend's username</label>
                        <input required style="margin-bottom:1rem" id="username" name="username" type="text">
                        <button class="modal_confirm">Add friend</button>
                </div>
                
        </form>

        
        
    </div>

    <script>
        function close_modal(){
                let element = document.getElementById("modal");
                element.style.display = "none";
        }
        function show_modal(event){
                let element = document.getElementById("modal");
                element.style.display = "flex";
        }
    </script>
    
</body>
</html>