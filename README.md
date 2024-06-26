### A very basic Authentication Web Application written in PHP

*Aim of the project is to create an easy to understand and basic auth system so that newbies can practise PHP*

The Project Includes:

* *A Login, Register page, Email verfication for new users and option to reset password.*
* *Error and Success displaying mechanism.*
* *Displaying profile picture*
* *Use of Supabase as database for storing users*
* *Use of Cloudinary to save profile pictures*
* *Use of Nginx to serve the project*
  <br><br>
*Demo of Project*

[Screencast from 2024-06-26 23-46-37.webm](https://github.com/NarutoUchiha39/PhpAuthentication/assets/104666748/5355c473-99b7-4141-ae81-b09679fe6624)


### *Dependency manager used:*
To manage the dependencies in the project ```composer``` is used.To learn how to install composer visit ```https://getcomposer.org/doc/00-intro.md```. For using composer in the current project: 
* install composer
* git clone the project
* run ```composer install``` to install all the dependencies present in ```composer.json```
* run ```composer update``` to update any dependency
* To use any library installed via composer you need to require ```vendor/autoload.php```. Vendor is a folder made by composer

### *Libraries used:*
* ```vlucas/phpdotenv``` : This is a library that helps you to load contents from a .env file into the project environment.
github link: ```https://github.com/vlucas/phpdotenv```
* ```cloudinary/cloudinary_php```: This is the official sdk of cloudinary for php. github link: ```https://github.com/cloudinary/cloudinary_php```

### *Database table:*
* ```custom_auth``` is used for storing users in the database. The columns are: `username `, `email`, `password`, `resume`, `profile_link` with `username` and `email` as primary keys

### *Nginx configuration(optional beginners can use XAMPP server to serve their project)*
* For Windows users follow ```https://github.com/NarutoUchiha39/EliteSolutions```
* For Linux users :
  1. First run ```sudo apt install nginx```
  2. make sure you have ```php-fpm``` installed or run ```sudo apt install php-fpm```. To start fpm run ```sudo systemctl start php<version>-fpm```
  3. run ```sudo nginx``` to start nginx server at ```http://localhost:80```. If nginx cant bind to port 80 run ```sudo fuser -k 80/tcp``` to kill processes running at port 80 and ```sudo apache2ctl stop``` to stop apache2 server, if present running at port 80
  4. The nginx config files are present in ```/etc/nginx/nginx.conf ```. If you have ```sites-enabled``` and ```sites-available``` directories, then update the sites-enabled directory.

  5. Why is fpm needed? Servers like Apache server treat each request as a worker process and each worker process has an instance of php or any other language embeded into the process, giving the webserver ability to run code natively in the server itself and serve dynamic content. Nginx on the other hand treats each request as a thread and uses a seperate process to manage all the request that needs dynamic content. The process in our case is php fpm(fast proccess manager)<br>

  6. copy the project files in ```var/www/```
  7. Use the following config in your  ```nginx.conf``` file or default file in ```/etc/nginx/sites-enabled``` :
     ```
      server {
      listen 80;
      listen [::]:80;
      server_name _;
      root /var/www/EliteSolutions;
   
      add_header X-Frame-Options "SAMEORIGIN"; # Stop our website from being embedded in i frames
      add_header X-Content-Type-Options "nosniff"; # Stop the browser from sniffing our pages and determining the mime type.
   
      index index.php index.html;
   
      charset utf-8;
   
      location / {
          try_files $uri $uri/ /index.php?$query_string; # Try to serve our file from the loaction in uri then try to serve the file as a folder then if all fails send the uri to index file as query string
      }
   
      location = /favicon.ico { access_log off; log_not_found off; } 
      location = /robots.txt  { access_log off; log_not_found off; }
   
      error_page 404 /index.php;
   
      location ~ \.php$ { # ~ signifies regex. This regex matches all files ending with .php
          fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; # use fastcgi to connect to php-fpm socket running 
          fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name; # Pass the location of the file to be processed to fastcgi. $realpath_root gives the root of project (i.e. /var/www/EliteSolutions) and $fastcgi_script_name gives the file name. (i.e. /var/www/EliteSolutions/index.php)
          include fastcgi_params; # include all well known fastcgi parameters present in /etc/nginx/fastcgi_params
      }
   
      location ~ /\.(?!well-known).* {
          deny all;
      }
     }
     
      ```

  
  8. Run ```sudo ngnix -t``` to test your nginx configuration and then run  ```nginx -s relaod``` to restart nginx server

### *Utility fuctions included:* 
* ```cloudinaryConnection.php``` manages the connection to the cloudinary server and helps in uploading images. After image is uploaded we get the link to image from this file. One small caveat: while loading environment variable by phpdotenv do not use the ```getenv``` method to retrieve environment variable as its not thread safe. Use ```$_ENV``` instead. To upload the profile image, the temporary location of the image obtained through form, is passed to the fuction along with the name of the file.
  
* ```getURI.php``` helps in returning the url of a file located on server. It checks whether the server has https in the ```$_SERVER``` super global variable turned on and concatenates it with ```$_SERVER[HTTP_HOST]```. This makes the function flexible and enable correct url retrieval even in hosted environment. To get the url of a file just pass in the file location from root of the server and your good to go
* ```passwordEncryption.php``` has two static functions. ```getEnv``` returns the value of environment variable when its given the key. ```encrypt``` is used to encrypt the user password with the use of openssl library. The function makes use of ```aes-256-ctr``` which is a block cypher. For the encryption process, a nonce is generated using the ```openssl_random_pseudo_bytes```. The length is determined using ```openssl_cipher_iv_length``` accoring to the cypher method used. The encryption takes place using a strong key, the nonce and the message. The nonce and the message is then concatenated and ```base64 encoded```. For decrypting, first we decoade the ```base64```. we get the nonce by slicing the string as we know the nonce length. Here the ```mb_substr``` is used for slicing. After that the decoding process takes place using the nonce obtained.<br>
***Note: I know obtaining hash of password using ```password_hash``` is safer than encrypting and decrypting password. Openssl was used just to explore the library***
* ```DBConnect.php``` is used for connecting to supabase. Since supabase is a postgres database, the function ```pg_connect``` is used to connect to supabase and the connection is returned from the function.  
* ```login.php``` helps in authenticating the user. The connection is obtained from DBConnect.php. First the user is fetched from database using the email obtained from a form. For SQL queries, first a SQL query is prepared using the ```pg_prepare``` function. Then the select query is executed using the ```pg_execute``` function with array of values to be substituted in the prepared statement.The user is authenticated (password decrypted and compared using method mentioned above) and the user credentials are stored in the ```$_SESSION```. ```session_start()``` is used at the start to enable access to session variables
* ```register.php``` helps in registering a user. First the profile photo uploaded is verified. The filename,size,type,temporary location is obtained from ```$_FILES```. The file is checked for upload errors, then the ```size === 0``` comparison is made to make sure the file is not empty. After that the upload size is check to make sure its less than 5MB then the templocation, filename is passed to  ```cloudinaryConnection.php``` to upload the file too cloudinary. The name of the file is kept as the username since it is bound to be unique and so there will be no clashing of names in profile photo. After uplaoding photo, a SQL prepared statement is fired to insert the user credentials along with profile photo link into the database. Error statements are sent to the frontend according to any constraint violated. The uset details are stored in ```$_SESSION```. The user is then redirected using the ```header("Location: $url")``` to the home page with a success message

### *Displaying of success and error messages*

* Whenever a user is logged in or registered successfully we give an array of success messages in the session storage using ```$_SESSION["success"][]="Success Message"``` and then redirect the user. Same applies for error messages
* The user is redirected using the ```header("Location: $url")``` snippet
* On the HTML page we have a for loop that iterates over error messages in the session using ```foreach``` loop and at the end of the loop, we make sure that the error messages are flushed out using the ```unset``` function so that new errors are not mixed with old ones
* Make sure to have ```session_start()``` at the start of pages where session variables are used

### *Navbar:*
The Navbar checks the presence of user credentials using the ```isset``` function. If the user credentials are set in the ```$_SESSION``` then the profile photo along with logout is displayed. Else login and register is displayed

### *Login page:*
The Login page makes use of a simple HTML form to send the user credentials to ```login-page.php```. Success and Error messages are passed back and forth using the mechanism mentioned above.

### *Register page:*
The register page is used to upload the profile photo along with user credtials to ```register-page.php```

### *Logout:*
Makes use of ```session_unset``` to remove user session variables, ```session_destroy``` to remove the user session files from the server and ```session_regenerate_id(true)```to start a new session

### *Updates:*
1. Added ```.env.example``` to enable users see how the ```.env``` should contain use ```cp .env.example .env``` to copy the contents of .env.example into .env and fill in your own credentials.  
2. Added Email verification:

* Used ```php-mailer``` to send mail to people trying to register into the website. For using php-mailer you have to enable 2FA of your google account (If you plan to use gmail smtp) and enable ```App Password```

3. The work-flow is now like this:

* User tries to register
* using ```pg_prepare``` and ```pg_execute``` the database is queried to check for users with same email and also checks the validity of the file getting uploaded and displays errors accordingly.

*Note: pg_prepare has a small caveat wherein when you execute same prepared statement twice, it gives a warning stating that statement has already been prepared, indicating that prepared statements arent cached by pg_prepare. As far as i know PDO doesnt cause this issue and caches prepared statements. But inspite of warning the statemnt is executed*

* After validating user credentials, we store all the details obtained in ```$_SESSION["temp"]["username"], ...```. This is done because the user is inserted into the database only after their email is verified, so we need some way to access the credentials in ```VerifyMail.php```. We also generate a  unique id as user's code, email and the expiry time(60 seconds) in the DB. The expiry time is stored in the form of unix timestamp using php ```time()``` function. The user is sent a mail containing the code using the custom ```send_mail``` function
  
* Next the user is redirected to the email page. Here if the user enters wrong code, appropriate error message is displayed and if the user exceeds the expiry time then the user is redirected back to the register page. On entering the correct code, the user is registered and redirected back to home. Some points to note :
  1. The temporary location of the file becomes invalid when we move to the other page, so the user's profile photo is temporarily stored in the uploads folder using the ```move_uploaded_file()``` command.
  2. After the expiry or after the user successfully registers, the ```cleanUp``` function clears the users code from the database and deletes the temporary file in the uploads folder using the ```unlink``` command. After time expiry the temporary session variables are removed and the cleanUp function in run to allow the user to try to register again

* Added ability of the users to reset their passwords. First the user is asked to enter the registered mail. If the user is not registered, an error message is shown otherwise the user is sent a mail containing a link to reset the password. The link expires within a minute. On clicking the link, the user is redirected to a page where, the user can enter new password and login to the system
* The link contains a ```nonce``` key initialized with a random value generated using the php ```unique(true)``` function. On clicking the link the nonce is extracted using the ```explode``` function. The nonce is queried in the database and the corresponding mail of the user is obtained. Then a simple ```UPDATE``` query is used to update the users password and a ```DELETE``` query is used to delete the user's nonce from the database. The user is redirected to the home page with a success message



### *If you made it till here, Thank you so much for your valuable time. Hope you enjoyed and learnt something!*
