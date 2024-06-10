<?php 

    $title = "Verify Email";
    include $_SERVER["DOCUMENT_ROOT"] . "/includes/navbar.php";
?>


<div class="box-container" style="width:100%;height:calc(100vh - 100px);display:flex;justify-content:center;align-items:center">
    <div class="anchror" style="width:50%;height:50vh;box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);display:flex;justify-content:center;align-items:flex-start;flex-direction:column">
        <form method="POST" action="<?php echo url("/utils/VerifyMail.php") ?>" class="wrapper" style="margin:auto">
            <div class="message" style="margin-bottom:2rem">
                Please Enter the Verification code sent on your e-mail
            </div>

            <div class="text-area" style="width:100%">
                <input style="width:100%" id="email_code" name="email_code" type="text">
            </div>

            <div class="verify" style="justify-content:flex-start;margin-top:20px">
                <button type="submit" style="background-color: #04AA6D;
                    border-radius: 0.5rem;
                    color: white;padding:0.5rem">
                    Verify
                </button>
            </div>
        </form>

    </div>
</div>

</body>
</html>

