<?php

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

if (!function_exists('create_thumbnail')) {
    function create_thumbnail($path, $output_file_compress, $width, $height)
    {
        $newPath = Image::make($path)->resize($width, $height)->save($output_file_compress);
        return $newPath;
    }
}
if (!function_exists('return_dimension')) {
    function return_dimension($image)
    {
        $imageSize = getimagesize($image);
        $width = $imageSize[0];
        $height = $imageSize[1];
        $widthNew = 250;
        $heightNew = 250;

        if($width > $height){
            $widthNew = 250;
            $heightNew = 167;
        }else if($width < $height){
            $widthNew = 185;
            $heightNew = 300;
        }
        return array(
            "width" => $widthNew,
            "height" => $heightNew
        );
    }
}
if (!function_exists('recurse_copy')) {
    function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
if (!function_exists('create_dir_comp')) {
     function create_dir_comp($user_id)
     {
         if (!file_exists("uploads/profiles/{$user_id}/compress")) {
             File::makeDirectory("uploads/profiles/{$user_id}/compress", 0775, true);
         }
     }
}