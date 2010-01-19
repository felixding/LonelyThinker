<?php 
/**
 * IE6 PngHack Helper
 * @author Jay Salvat
 * @version 1.0
 *
 * Use this helper instead of the original Html->image() 
 * to get Png pictures with alpha transparency working properly on
 * Internet Explorer 6 without any client-side javascript fix.
 * Works on img tags, not on backgrounds properties.
 */ 
class ImageHelper extends AppHelper {
    var $helpers = array('Html'); 

    function alpha($src, $options = array()) {
        // Get some info about the image
        $info    = getimagesize(WWW_ROOT.'img'.DS.$src);
        $width   = $info[0];
        $height  = $info[1];
        $type    = $info['mime'];
        $src     = '../img/'.$src;
        // Check if the user's browser is lower than Internet Explorer 7
        preg_match('~MSIE (.*?);~', $_SERVER['HTTP_USER_AGENT'], $out);
        $needHack = (isset($out[1]) && $out[1] < 7 && $type='image/png') ? true : false;
        // If yes...
        if ($needHack) {                                
            // Apply AlphaImageLoader filter
            $style = "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='".$src."', sizingMethod='scale');";
            if (isset($options['style'])) {
                $style = $style.$options['style'];
            }
            // Compute width if no $options['width'] has been setted
            if (isset($options['width'])) {
                if (!isset($options['height'])) {
                    $height = $height * ($options['width'] / $width);
                } else{
                    $height = $options['height'];
                }
                $width = $options['width'];
            } 
            // Compute height if no $options['height'] has been setted
            if (isset($options['height'])) {
                if (!isset($options['width'])) {
                    $width = $width * ($options['height'] / $height);
                } else {
                    $width = $options['width'];
                }
                $height = $options['height'];
            }
            $src               = '../img/pixel.png';        
            $options['width']  = $width;
            $options['height'] = $height;
            $options['style']  = $style;
        } 
        // Call the actual Image Helper with new options
        return $this->Html->image($src, $options);
    }
}
?>