<?php

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
        $widthNew = 0;
        $heightNew = 0;

        if($width > $height){
            $widthNew = 250;
            $heightNew = 167;
        }else if($width < $height){
            $widthNew = 250;
            $heightNew = 375;
        }
        return array(
            "width" => $widthNew,
            "height" => $heightNew
        );
    }
}
if (!function_exists('recurse_copy')) {
     function recurse_copy($src,$dst)
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