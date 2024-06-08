<?php
    $title="Login";
    $stylesheet = "/css/login-page.css";
    include ($_SERVER['DOCUMENT_ROOT']."/includes/navbar.php")
?>


    <div class="container">
            <style>
                .alert {
                padding: 20px;
                background-color: #f44336;
                color: white;
                
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
            <form action="<?php echo url("/utils/register.php") ?>" method="POST" class="register" enctype="multipart/form-data">
                <div class="anchor">
                    <div class="inputs">
                            <div class="input name">
                                <label class="element" for="name">Name: </label>
                                <input required class="element" placeholder="Enter your name" id="name" name="name" type="text"/>
                            </div>

                            <div class="input email">
                                <label class="element" for="email">Email: </label>
                                <input required class="element" placeholder="Enter your Email" id="email" name="email" type="email"/>
                            </div>

                            <div class="input password">
                                <label class="element" for="password">Password: </label>
                                <input required class="element" placeholder="Enter your password" id="password" name="password" type="password"/>
                            </div>

                            <div class="input resume">
                                <label class="element" for="name">Resume Link: </label>
                                <input required placeholder="Enter link to resume" id="resume" name="resume" type="text"/>
                            </div>

                            <div class="input submit">
                                <button class="submit" type="submit"> Lets Go! </button>    

                            </div>
                    </div>

                    <div class="image">

                        <div class="image_container" style="display:flex;justify-content:center;align-items:center;position:relative;top:10%" >
                            <img src="https://preview.redd.it/trying-to-come-up-with-a-new-avatar-for-my-various-social-v0-wby69l6e1lsb1.jpg?width=519&format=pjpg&auto=webp&s=61341c3ce447f8356da3146c1903395fc43d28dc"
                            style="width:70%;border-radius:50%;"
                            />
                        </div>
                        
                        <div class="file">
                            <input  type="file" name="profile" id="profile">
                        </div>

                    </div>
                </div>

            </form>

    </div>

</body>
</html>