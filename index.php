<?php
$title = "Elite Solutions" ;


include 'includes/navbar.php' ?>
<style>
        .alert {
        padding: 20px;
        background-color:green;
        color: white;
        width:max-content;
        margin: auto;
        margin-top: 1%;
        margin-bottom: 2%;
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
        
        if(isset($_SESSION["success"])){

        foreach($_SESSION["success"] as $success){
            ?>
            <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    <?php
                            echo $success;
                    ?>
            </div>
     <?php }
        unset($_SESSION["success"]);
    }
     echo $_SESSION['username'];   
     ?>
    
</body>
</html>