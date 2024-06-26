<?php 
$title = "change password";
$stylesheet='/css/forgotPassword.css';
include($_SERVER["DOCUMENT_ROOT"]."/includes/navbar.php") ?>
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
            <div class="alert" style="position:relative;top:6rem">
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

        <div class="success" style="position:relative;top:6rem">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                            <?php
                                    echo $success;
                            ?>
        </div>

     <?php } }
     unset($_SESSION["success"])
     ?>

<form action="<?php echo url('/utils/changePassword.php') ?>" method="post">
    
    <div class="forgot_password">
        
            <div class="anchor">
                <label for="password" style="margin-bottom:0.5rem">New password</label>
                <input required name="password" placeholder="Enter password" id="password" type="password">
                <button  type="submit">Submit</button>
            </div>
                
    </div>

</form>

</body>
</html>