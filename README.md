### A very basic Authentication Web Application written in PHP

The Project Includes:

* *A Login, Register page.*
* *Error and Success displaying mechanism.*
* *Displaying profile picture*
* *Use of Supabase as database for storing users*
* *Use of Cloudinary to save profile pictures*
* *Use of Nginx to serve the project*
  
### *Utility fuctions included:* 

### *Displaying of success and error messages*

* Whenever a user is logged in or registered successfully we give an array of success messages in the session storage using ```$_SESSION["success"][]="Success Message"``` and then redirect the user. Same applies for error messages
* The user is redirected using the ```header("Location: $url")``` snippet
* On the HTML page we have a for loop that iterates over error messages in the session using ```for``` loop and at the end of the loop, we make sure that the error messages are flushed out so that new errors are not mixed with old ones
* Make sure to have ```session_start()``` at the start of pages where session variables are used

### *Login page:*
The Login page makes use of a simple HTML form  
