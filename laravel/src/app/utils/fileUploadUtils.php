<?php
namespace App\utils;

use App\utils\GeneralUtils;

class fileUploadUtils extends GeneralUtils {

    function upload_file($tempFile,$targetFile){
        return move_uploaded_file($tempFile,$targetFile); 
    }
}