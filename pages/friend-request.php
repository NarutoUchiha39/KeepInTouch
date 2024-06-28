<?php 
    session_start();
    $title = "friend requests";
    $stylesheet='/css/friendRequest.css';
    $username = $_SESSION["username"];
    include $_SERVER["DOCUMENT_ROOT"]."/includes/navbar.php";
?>

<div class="main_content" style="flex: 1;">
    <div class="sidebar">

        <div class="filter">
            <div class="category" style="cursor:pointer" onclick="showSent()">Sent</div>
            <div class="accepted" style="cursor:pointer" onclick="showRecieved()">Recieved</div>
        </div>

    </div>
    <div class="requests" style="display:flex;flex-direction:column">
        <div class="container" style="flex:1;display:flex;flex-direction:column">
            
            <div class="incoming_requests" style="flex: 1;"  id="request"></div>
        </div>

    </div>
</div>
<script>

    let key = "sent"
    let HTML_SENT = "";
    let HTML_RECIEVED = "";
    let requestElement = null;
    let username1 = `<?php echo $username ?>`;
    

    window.addEventListener("DOMContentLoaded", async (event) => {
        requestElement = document.getElementById("request");
        
        if (!requestElement) {
            console.error("Element with id 'request' not found");
            return;
        }

            let res = await fetch("http://localhost:8000/utils/getFriendRequests.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    update: 0
                })
            });
            
            res = await res.json()
            
            let sent = res["sent"];
            let recieved = res["recieved"];

            sent.forEach((value, index) => {
                HTML_SENT += `<div class="request" style="width: 50%;margin:auto;margin-bottom:1rem">
                <div class="request_content">
                    <img
                    class="request_profile"
                    src="${value['profile_link']}"
                    alt="profile image">
                    ${value["sent_to"]}
                </div>
                ${value["status"]}
                </div>
                </div>
                `;
            });

            requestElement.innerHTML = HTML_SENT;
            if(HTML_SENT.length == 0){
                requestElement.innerHTML = "<div style='display:flex;justify-content:center;align-items:center'>No request sent yet!</div>"
            }

            recieved.forEach((value, index) => {
                HTML_RECIEVED += `<div class="request" style="width: 50%;margin:auto;margin-bottom:1rem">
                    <div class="request_content">
                        <img
                        class="request_profile"
                        src="${value['profile_link']}"
                        alt="profile image">
                        ${value["sent_by"]}
                    </div>
                    <div class="accept_reject" style="display:flex;">`
                    console.log(value["status"])
                    if(value["status"] != "accepted"){
                    HTML_RECIEVED+=
                        `<div style="font-size:20px;margin-right:1rem"
                        onclick="acceptRequest('${value["sent_by"]}')" class="accept">
                            &#x2705;
                        </div>
                        <div style="font-size:20px" class="reject">
                            &#x274C;
                        </div>
                    </div>
                    </div>
                    `;
                }else{
                    HTML_RECIEVED+=
                        `Request ${value["status"]}
                    </div>
                    </div>
                    `;
                }
            });

            
        
    });

    function showSent(){

        HTML_SENT.length != 0?requestElement.innerHTML = HTML_SENT:requestElement.innerHTML="<div style='display:flex;justify-content:center;align-items:center'>No request sent yet!</div>";
        
    }
    function showRecieved(){
       HTML_RECIEVED.length != 0? requestElement.innerHTML = HTML_RECIEVED:requestElement.innerHTML="<div style='display:flex;justify-content:center;align-items:center'>No request recieved yet!</div>";
    }

    async function rejectRequest(event){
        let res = await fetch("http://localhost:8000/utils/getFriendRequests.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            update: 2,
            sender: event
        })
        });
        let data = await res.json();

        if(data.status == "success"){
                alert("Request rejected");
        }else{
                alert("There was some error :( Try again later");
        }
    }

    async function acceptRequest(event){
        let res = await fetch("http://localhost:8000/utils/getFriendRequests.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                update: 1,
                sender: event
            })
        });
        let data = await res.json();
        if(data.status == "success"){
            alert("Request accepted successfully");
        }else{
            alert("There was some error :( Try again later");
        }
    }
</script>

</body>
</html>