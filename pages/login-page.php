<?php
    $title="Login";
    $stylesheet = "/css/login-page.css";
    include ($_SERVER['DOCUMENT_ROOT']."/includes/navbar.php");
?>

<style>
        .alert {
        margin:auto;
        padding: 20px;
        background-color: #f44336;
        color: white;
        width: 45%;
        
        }

        .success{
        margin: auto;
        padding: 20px;
        background-color: green;
        color: white;
        width:45%;
        
        }

        .closebtn {
        margin-left: 15px;
        color: white;
        font-weight: bold;
        float: right;
        font-size: 22px;
        line-height: 20px;
        cursor: pointer;
        transition: 0.3s;
        }

        .closebtn:hover {
        color: black;
        }
    </style>

    <div class="container" style="flex-direction:column">
    <?php 
        
        if(isset($_SESSION["error"])){

        foreach($_SESSION["error"] as $error){
            ?>
            <div class="alert" style="position:relative;">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    <?php
                            echo $error;
                    ?>
            </div>
     <?php }
        unset($_SESSION["error"]);
    }elseif(isset($_SESSION["success"])){
        foreach($_SESSION["success"] as $success){
     ?>

        <div class="success" style="position:relative;">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                            <?php
                                    echo $success;
                            ?>
        </div>

     <?php } }
     unset($_SESSION["success"])
     
     ?>
            
    
        

                <form action="<?php echo url("/utils/login.php") ?>" method="POST" class="register">
                <div class="anchor">
                    <div class="inputs" style="display:flex;justify-content:center;flex-direction:column">
                       
                            <div style="font-size:30px;font-weight:bold;position:relative;margin-top:-10px;margin-bottom:50px">
                                Login
                            </div>
                            <div style="margin-top:-2rem;margin-bottom:0.7rem" class="forgot_password">
                                <a style="color:blue" href="<?php echo url("/pages/forgotPassword.php") ?>">Forgot password</a>
                            </div>
                            <div class="input email">
                                <label class="element" for="email">Email: </label>
                                <input required class="element" placeholder="Enter your Email" id="email" name="email" type="email"/>
                            </div>

                            <div class="input password">
                                <label class="element" for="password">Password: </label>
                                <input required class="element" placeholder="Enter your password" id="password" name="password" type="password"/>
                            </div>

                           
                            <div class="input submit">
                                <button class="submit" type="submit"> Lets Go! </button>    

                            </div>
                    </div>

                    <div class="image" style="margin-left: 20px;">
                                <img src="https://images.ctfassets.net/nnyjccrtyeph/7x1jJfY0gFYBsBq6TFWi6h/c4e8a0a0c0885e586bd2e78b92db9e64/project-management-career.png?w=1250&h=1250&q=50&fm=png" style="width: 110%;height:100%"/>
                    </div>

                   
                    </div>

                    
                </div>

            </form>

    </div>

</body>
</html>