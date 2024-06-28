<?php
session_start();
$title = "Keep In Touch" ;

if(!isset($_SESSION["username"])){

        header("Location: /pages/login-page.php");
        exit();
        die;
}

$stylesheet='/css/index.css';
include $_SERVER["DOCUMENT_ROOT"] ."/utils/messages.php";
include 'includes/navbar.php'
 ?>

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
                <div class="chats" style="margin-top:1rem">
                        <?php
                                foreach($people as $row){ ?>
                                       <div  class="message"> 
                                                <img style="margin-right:10px;height: 40px;width:40px;border-radius:1.1rem" src="<?php echo $row["profile_link"] ?>" alt="">
                                                <?php echo $row["username"]; ?>
                        
                                        </div>
                                <?php }
                        ?>
                </div>

                <div class="display_chats" style="margin-left:1px solid gray">

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

        <div class="add_friends" onclick="show_modal(event)">
                +
        </div>
        
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