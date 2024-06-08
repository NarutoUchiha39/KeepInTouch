<?php
    require __DIR__. '/vendor/autoload.php';
    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Api\Upload\UploadApi;

    function upload_image($path,$imageName,$ext){
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        
        $cloudinary_key = $_ENV["cloudinary_key"];
        $access_token = $_ENV["access_token"];
        $project_id = $_ENV["cloudinary_project_id"];

        echo $cloudinary_key;
        echo $access_token;
        echo $project_id;
        echo $path;

        Configuration::instance("cloudinary://$cloudinary_key:$access_token@$project_id?secure=true");
        $upload = new UploadApi();

        $res =  $upload->upload(
            "{$path}",
            [
                'public_id' => "{$imageName}",
                'use_filename' => true,
                'overwrite' => false
            ]
        );

    }
    
    upload_image(__DIR__ ."/uploads/s.png","s","png");

?>