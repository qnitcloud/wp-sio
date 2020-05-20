<?php 
/*
Plugin Name: WordPress Simple Image Optimizer
Plugin URI: https://www.qnitcloud.com/
Description: For better quality images.
Version: 1.0
Author: Qnitcloud
Author URI: https://www.qnitcloud.com/
*/
function wp_simple_image_optimize($resized_file) {
   $image = new Imagick($resized_file); 
   $size = @getimagesize($resized_file);
   if (!$size)
   return new WP_Error('invalid_image', __('Could not read image size.'), $file);
   list($orig_w,$orig_h,$orig_type) = $size;
   switch($orig_type) {
   case IMAGETYPE_JPEG:
      $image->stripImage();
      $image->unsharpMaskImage("1","0.83","1.83","0");        
      $image->setImageFormat("jpg");
      $image->SetColorspace(Imagick::COLORSPACE_RGB);
      $image->setImageCompression(Imagick::COMPRESSION_JPEG);
      $image->setImageCompressionQuality("82");
      $image->writeImage($resized_file); 			
   break;
   default:
   return $resized_file;
   }	
   $image->destroy();	
   return $resized_file;
}
add_filter('jpeg_quality', function($arg){return 100;});
add_filter('image_make_intermediate_size','wp_simple_image_optimize',900);
