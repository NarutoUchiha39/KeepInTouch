<?php
    require $_SERVER["DOCUMENT_ROOT"]. '/vendor/autoload.php';
    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Api\Upload\UploadApi;

    function upload_image($path,$imageName,$ext,$type="image"){
        $dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
        $dotenv->load();

        
        $cloudinary_key = $_ENV["cloudinary_key"];
        $access_token = $_ENV["access_token"];
        $project_id = $_ENV["cloudinary_project_id"];

        
        Configuration::instance("cloudinary://{$cloudinary_key}:{$access_token}@{$project_id}?secure=true");
        $upload = new UploadApi();

        $res =  $upload->upload(
            "{$path}",
            [
                'resource_type'=> "$type",
                'public_id' => "{$imageName}",
                'use_filename' => true,
                'overwrite' => false
            ]
        );

        return $res;

    }
    


?>