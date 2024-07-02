### Keep In Touch: A Basic chat Applicaton made using php 
*💭 Bro I want to keep in touch with some of my old friends as well as make new friends could you help me with that?* 🤔
  <br>
Oh boy you have in at the right time. I have the just the thing for you. Keep In Touch helps you to well keep in touch with your old friends and make some new ones too

Keep In Touch includes the following features:

1. Basic Authentication system including ability to upload your own avatar
2. Email verfication for new registering users as well as forgot password feature
3. Expiry of email verification and password reset links in 60 seconds
4. Ability to send friend requests to people using their username
5. Ability to accept or reject friend request as well as view incoming and sent friend request
6. View your friends and chat with them

*Feeling excited to know more about the website? Lets checkout a small demo video!*

*Demo of Project*

<video controls="" width="800" height="500" muted="" loop="" autoplay="">
<source src="ReadMeAssets/KeepInTouch.mp4" type="video/mp4">
</video


### <p align='center'> Made with ❤️ using </p>

<p align='center'>
    <img with=120px height=120px style="margin-right:30px" src='https://github.com/NarutoUchiha39/KeepInTouch/assets/104666748/bb313e1a-8d63-4626-8c4c-87241f0a401b'/>
    <img with=120px height=120px style="margin-right:30px" src='https://github.com/NarutoUchiha39/KeepInTouch/assets/104666748/1dd75dfc-c7be-4aed-8d30-5cceeefa0793'/>
    <img with=120px height=120px style="margin-right:30px" src='https://github.com/NarutoUchiha39/KeepInTouch/assets/104666748/76d209bc-3549-40d9-8732-2c8edca23a62'/>


</p>

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
* ```phpmailer/phpmailer```: A library to help you send mail securely and fast. To use this you need to use Gmail password generated using ```App password```

### *Database Schemas:*
*For database I have made use of postgresql database hosted at supabase. For querying datanbase i have used php-pgsql*
<br>
![Class diagram](https://github.com/NarutoUchiha39/KeepInTouch/assets/104666748/df61eb3c-63e1-4e04-b3fe-426457f9c9ce)


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

### *Special Utility fuctions included:* 
* ```cloudinaryConnection.php``` manages the connection to the cloudinary server and helps in uploading images. After image is uploaded we get the link to image from this file. One small caveat: while loading environment variable by phpdotenv do not use the ```getenv``` method to retrieve environment variable as its not thread safe. Use ```$_ENV``` instead. To upload the profile image, the temporary location of the image obtained through form, is passed to the fuction along with the name of the file.
* ```getURI.php``` helps in returning the url of a file located on server. It checks whether the server has https in the ```$_SERVER``` super global variable turned on and concatenates it with ```$_SERVER[HTTP_HOST]```. This makes the function flexible and enable correct url retrieval even in hosted environment. To get the url of a file just pass in the file location from root of the server and your good to go
* ```passwordEncryption.php``` has two static functions. ```getEnv``` returns the value of environment variable when its given the key. ```encrypt``` is used to encrypt the user password with the use of openssl library. The function makes use of ```aes-256-ctr``` which is a block cypher. For the encryption process, a nonce is generated using the ```openssl_random_pseudo_bytes```. The length is determined using ```openssl_cipher_iv_length``` accoring to the cypher method used. The encryption takes place using a strong key, the nonce and the message. The nonce and the message is then concatenated and ```base64 encoded```. For decrypting, first we decoade the ```base64```. we get the nonce by slicing the string as we know the nonce length. Here the ```mb_substr``` is used for slicing. After that the decoding process takes place using the nonce obtained.<br>
***Note: I know obtaining hash of password using ```password_hash``` is safer than encrypting and decrypting password. Openssl was used just to explore the library***
* ```DBConnect.php``` is used for connecting to supabase. Since supabase is a postgres database, the function ```pg_connect``` is used to connect to supabase and the connection is returned from the function.  
* Displaying of success and error messages

  * Whenever a user is logged in or registered successfully we give an array of success messages in the session storage using ```$_SESSION["success"][]="Success Message"``` and then redirect the user. Same applies for error messages
  * The user is redirected using the ```header("Location: $url")``` snippet
  * On the HTML page we have a for loop that iterates over error messages in the session using ```foreach``` loop and at the end of the loop, we make sure that the error messages are flushed out using the ```unset``` function so that new errors are not mixed with old ones
  * Make sure to have ```session_start()``` at the start of pages where session variables are used

### How the website works:

* User tries to register
* using ```pg_prepare``` and ```pg_execute``` the database is queried to check for users with same email and also checks the validity of the file getting uploaded and displays errors accordingly.

*Note: pg_prepare has a small caveat wherein when you execute same prepared statement twice, it gives a warning stating that statement has already been prepared, indicating that prepared statements arent cached by pg_prepare. As far as i know PDO doesnt cause this issue and caches prepared statements. But inspite of warning the statemnt is executed*

* After validating user credentials, we store all the details obtained in ```$_SESSION["temp"]["username"], ...```. This is done because the user is inserted into the database only after their email is verified, so we need some way to access the credentials in ```VerifyMail.php```. We also generate a  unique id as user's code, email and the expiry time(60 seconds) in the DB. The expiry time is stored in the form of unix timestamp using php ```time()``` function. The user is sent a mail containing the code using the custom ```send_mail``` function
  
* Next the user is redirected to the email page. Here if the user enters wrong code, appropriate error message is displayed and if the user exceeds the expiry time then the user is redirected back to the register page. On entering the correct code, the user is registered and redirected back to home. Some points to note :
  1. The temporary location of the file becomes invalid when we move to the other page, so the user's profile photo is temporarily stored in the uploads folder using the ```move_uploaded_file()``` command.
  2. After the expiry or after the user successfully registers, the ```cleanUp``` function clears the users code from the database and deletes the temporary file in the uploads folder using the ```unlink``` command. After time expiry the temporary session variables are removed and the cleanUp function in run to allow the user to try to register again

* user can reset their passwords from login page. First the user is asked to enter the registered mail. If the user is not registered, an error message is shown otherwise the user is sent a mail containing a link to reset the password. The link expires within a minute. On clicking the link, the user is redirected to a page where, the user can enter new password and login to the system
* The link contains a ```nonce``` key initialized with a random value generated using the php ```unique(true)``` function. On clicking the link the nonce is extracted using the ```explode``` function. The nonce is queried in the database and the corresponding mail of the user is obtained. Then a simple ```UPDATE``` query is used to update the users password and a ```DELETE``` query is used to delete the user's nonce from the database. The user is redirected to the home page with a success message
  
* After successful login/register the user is redirected to the home page where the user can can add new friends. The username entered by the user is checked to see if the name exists. If the name doesnt exists user is shown an error else a friend request is sent
* A user has tehe option to accept/reject friend request and can view all the requests from the bell icon in the home page
* At the home page the user can see all the friends. The records are retrieved from db using and SQL query involving ```union``` and ```join```
* Finally the user can send and recieve messages. Users messages are to the left and the recieved messages are to the right. For this ```JS fetch API``` is used to asynchronously fetch data and populate it in the apropriate place using the ```DOM Manipulation Techniques``` 
* The user has the ability to send media. The uset can upload a ```pdf```(max size: 1MB), ```jpeg,png,jpg```(file under 8MB). The files are stored and retrieved using cloudinary. For storing files other than images, the ```resource type``` in the coloudinary api is changed to raw.
* For downlaoding a file we simply put ```download``` attribute in the anchor tag and put the link of the PDF in the src attribute
* For storing the multimedia, we introduce a new column called ```type``` in the DB with default value of text. The type attribute is changed according to the content of the message. If we send media then the ```message``` attribute stores the link generated by cloudinary, when we upload the file


### *If you made it till here, Thank you so much for your valuable time. Hope you enjoyed and learnt something!*
