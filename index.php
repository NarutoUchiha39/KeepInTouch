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

        <div class="success" >
                <div id="success" class="success_msg"></div>
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 

        </div>

        <div class="alert" >
            <div id="alert" class="error_msg"></div>
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 

        </div>


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
                                        <div  class="message" 
                                        onclick="show_chats(`<?php echo $row['username'] ?>`,
                                        `<?php echo $row['profile_link']?>`)"> 

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
                

                <div  class="display_chats" style="margin-left:1px solid gray">
                        
                        <div id="title" class="meessage_title"></div>

                        <div class="all_messages" id="all_messages" style="height:77.5vh;padding-top:1rem;padding-left:0.7rem;overflow:scroll"></div>

                        <div class="send_text"  id="form">
                                <textarea  id="send" type="text" style="display:none" name="reciever"></textarea>
                                <div class="textarea">
                                        <textarea required class="sendText" name="message" id="sendtext"></textarea>
                                </div>
                                <div class="send" style="display:flex;justify-content:space-evenly;align-items:center">
                                        
                                        <button id="ok" onclick="send_attachment()" style="background-color:cadetblue;border:1px solid cadetblue;border-radius:50%;padding:0.5rem;cursor:pointer">  
                                        <i style="color:white;font-size:25px" class="fa fa-plus" aria-hidden="true"></i>

                                        <input name="attachment" onchange="checkExtension(event)" style="display:none;" type="file" id="send_attachment"></input>
                                        </button>

                                      <button onclick="sendMessage()" style="background-color:cadetblue;border:1px solid cadetblue;border-radius:50%;padding:0.5rem;cursor:pointer">  
                                        <i style="color:white;font-size:25px" class="fa fa-paper-plane" aria-hidden="true"></i>
                                      </button>
                                </div>
                                
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
        let username = null;
        let cur_user = `<?php echo $_SESSION["username"] ?>`
        let files = null

        function checkExtension(event){
                files = event.target.files
                let filename = files[0].name
                let extension = files[0].type
                let element = document.getElementById("ok");
                let actual_ext = extension.split("/")[1]
                
                if(actual_ext == "jpeg" || actual_ext == "png" || actual_ext == "jpg" ){
                element.innerHTML = `<i style="color:white;font-size:25px" class="fa-solid fa-image" aria-hidden="true"></i>`
                }else if(actual_ext == "pdf"){
                        element.innerHTML = `<i style="color:white;font-size:25px" class="fa-solid fa-file-pdf" aria-hidden="true"></i>`
                }else{
                        element.innerHTML = `<i style="color:white;font-size:25px" class="fa-solid fa-file" aria-hidden="true"></i>`
                }
        }

        function send_attachment(){
                let element = document.getElementById("send_attachment")
                element.click()
        }

        function messageHTML(src,name,description,direction,user,type,img_src,file_name=null){
                console.log(type)
                let message = null;
                if(type == "text"){
                        if(!user){
                        message = `
                        <div class="row" style='width:95%;display:flex;justify-content:${direction}'>
                                <div class='user_message' style="width:30%;display:grid;grid-template-columns:0.2fr 0.8fr">
                                        <div class="messageProfile">
                                                <img style="height:50px;width:50px;border-radius:50%" src="${src}" alt="">
                                        </div>
                                        <div class="messageBody">
                                                <div style="color:darkgreen" class="sender">
                                                        ${name}
                                                </div>

                                                <div class="messageContents">
                                                        ${description}
                                                </div>
                                        </div>
                                </div>
                        </div>
                        `
                        }else{
                        message = `
                        <div class="row" style='width:95%;display:flex;justify-content:${direction}'>
                                        <div class="messageBody">
                                                <div style="color:darkblue" class="sender">
                                                        ${name}
                                                </div>

                                                <div class="messageContents">
                                                        ${description}
                                                </div>
                                        </div>
                                </div>
                        </div>
                        `       
                        }
                }else if(type == "jpeg" || type == "png" || type == "jpg" || type == "webp"){

                        if(!user){
                        message = `
                        <div class="row" style='width:95%;display:flex;justify-content:${direction}'>
                                <div class='user_message' style="width:30%;display:grid;grid-template-columns:0.2fr 0.8fr">
                                        <div class="messageProfile">
                                                <img style="height:50px;width:50px;border-radius:50%" src="${src}" alt="">
                                        </div>
                                        <div class="messageBody">
                                                <div style="color:darkgreen" class="sender">
                                                        ${name}
                                                </div>

                                                <div class="messageContents">
                                                        <img style='width:341px;height:192px' src='${img_src}'/>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        `
                        }else{
                        message = `
                        <div class="row" style='width:95%;display:flex;justify-content:${direction}'>
                                        <div class="messageBody">
                                                <div style="color:darkblue" class="sender">
                                                        ${name}
                                                </div>

                                                <div class="messageContents">
                                                        <img style='width:341px;height:192px' src='${img_src}'/>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        `       
                        }

                }else if(type == "pdf"){
                        if(!user){
                        message = `
                        <div class="row" style='width:95%;display:flex;justify-content:${direction}'>
                                        <div class="messageProfile">
                                                <img style="height:50px;width:50px;border-radius:50%" src="${src}" alt="">
                                        </div>
                                        <div class="messageBody">
                                        
                                                <div style="color:darkgreen" class="sender">
                                                        ${name}
                                                </div>

                                                <div class="messageContents">
                                                        
                                                <div class="messageContents">
                                                        <div class="document" >
                                                                <div style='margin-right:1rem'>
                                                                        <i style='color:cadetblue;font-size:1.4rem' class="fa-solid fa-file"></i>
                                                                </div>

                                                                <div class="documentContent" style='margin-right:10px'>
                                                                        <div style='font-size:15px' class='document_name'>
                                                                                ${file_name}
                                                                        </div>

                                                                        <div style='margin-top:5px;color:gray;font-size:12px'  class='document_name'>
                                                                                ${type}
                                                                        </div>
                                                                </div>

                                                                <div style='cursor:pointer'">  
                                                                   <a download href='${description}'>     
                                                                        <i style='color:cadetblue;font-size:20px' class="fa fa-download" aria-hidden="true"></i>
                                                                   </a>
                                                                </div>


                                                        </div>
                                                </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        `
                        }else{
                        message = `
                        <div class="row" style='width:95%;display:flex;justify-content:${direction}'>
                                        <div class="messageBody">
                                                <div style="color:darkblue" class="sender">
                                                        ${name}
                                                </div>

                                                <div class="messageContents">
                                                        
                                                <div class="messageContents">
                                                        <div class="document" >
                                                                <div style='margin-right:1rem'>
                                                                        <i style='color:cadetblue;font-size:1.4rem' class="fa-solid fa-file"></i>
                                                                </div>

                                                                <div class="documentContent" style='margin-right:10px'>
                                                                        <div style='font-size:15px' class='document_name'>
                                                                                ${file_name}
                                                                        </div>

                                                                        <div style='margin-top:5px;color:gray;font-size:12px'  class='document_name'>
                                                                                ${type}
                                                                        </div>
                                                                </div>

                                                                <div style='cursor:pointer'">  
                                                                   <a download href='${description}'>     
                                                                        <i style='color:cadetblue;font-size:20px' class="fa fa-download" aria-hidden="true"></i>
                                                                   </a>
                                                                </div>


                                                        </div>
                                                </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        `       
                        }
                }

                return message
        }
        async function fetchChats(reciever,imageUrl){
                let res = await fetch("http://localhost:8000/utils/sendRetrieveMessage.php",{
                        method:"POST",
                        header:{"Content-type":"application/x-www-form-urlencoded"},
                        body:new URLSearchParams({
                                option:2,
                                reciever:reciever
                        })
                }).then(async(data)=>await data.json()).catch((err)=>{console.log(err)});

                if(res["status"] == "success"){
                        let all_messages = document.getElementById("all_messages");
                        let HTML = ``
                        
                       res["messages"].forEach((value,index)=>{
                                let messageHtml1 = "";
                                let fileName = "";
                                if(value["type"] == "pdf"){
                                        let split_text = value["message"].split("/");
                                        let temp_list = split_text[split_text.length-1].split("_")
                                        fileName = temp_list[temp_list.length-1]
                                }
                                if(value["sender"] != cur_user){
                                        if(value["type"] == "text"){
                                                messageHTML1 = messageHTML(imageUrl,value["sender"],value["message"],"flex-start",false,"text","","");
                                        }else{
                                                messageHTML1 = messageHTML(imageUrl,value["sender"],value["message"],"flex-start",false,value["type"],value["message"],fileName);
                                        }
                                }else{
                                        if(value["type"] == "text"){
                                                messageHTML1 = messageHTML(imageUrl,"You",value["message"],"flex-end",true,"text","","");
                                        }else{
                                                messageHTML1 = messageHTML(imageUrl,"You",value["message"],"flex-end",true,value["type"],value["message"],fileName);
                                        }
                                        
                                }

                                HTML += messageHTML1
                       })
                        
                       all_messages.innerHTML = HTML

                }else{
                        let element = document.getElementById("alert");
                        element.parentElement.style.display = "flex"
                        element.innerHTML = "Something went wrong :("
                }
                
        }
        async function sendMessage(message){

                let sendText = document.getElementById("sendtext")
                let formData = new FormData();
                formData.append("sender",cur_user);
                formData.append("reciever",username);
                if(files){
                        formData.append("attachment",files[0])
                }
                formData.append("message",sendText.value);
                formData.append("option",1);
                
                let res = await fetch("http://localhost:8000/utils/sendRetrieveMessage.php",{
                        method:"POST",
                        body:formData
                }).then(async(data)=>await data.json()).catch((err)=>{console.log(err)})

                if(res.status=="success"){
                        let element = document.getElementById("success");
                        element.parentElement.style.display = "flex"
                        element.innerHTML = "Message Sent"
                }else{
                         let element = document.getElementById("alert");
                        element.parentElement.style.display = "flex"
                        element.innerHTML = res["message"];
                }
        }

        function show_chats(userName,imageURL){

                
                const element = document.getElementById("title");
                const send = document.getElementById("send");
                const form = document.getElementById("form");
        

                username = userName

                form.style.width="90%";
                form.style.margin= "auto";
                form.style.marginBottom= "10px";
                form.style.display= "grid";
                form.style.gridTemplateColumns = "0.8fr 0.2fr";


                let HTML = `
                <div class="title_bar" id="title_bar">
                                
                        
                        <div style='display:flex;justify-content:flex-start;align-items:center'>
                                <img style="margin-right:10px;
                                height: 40px;width:40px;border-radius:1.1rem" 
                                src='${imageURL}' alt="">
                                                        
                                <div class='name' style='font-size:20px'>${userName}</div>
                        </div>
                </div>
                `

                element.innerHTML = HTML;
                fetchChats(userName,imageURL);

        }

       

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