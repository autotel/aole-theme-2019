<?php
// Get the event image URL with fallback default picture


function get_post_thumbnail_url_or_fallback($post_id, $image_size, $fallback){
  $ret=Array();
  $fallback_post_thumbnails_theme_mod = get_theme_mod('fallback_pictures');
  // global $fallback_post_thumbnails_theme_mod;
  $mod = $fallback_post_thumbnails_theme_mod;
  $thumb_url = "";
  $thumb_alt = "nm";
  if (has_post_thumbnail($post_id)){
    $thumb_id = get_post_thumbnail_id($post_id);
    $thumb_url = get_the_post_thumbnail_url($post_id, $image_size);
    $thumb_alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
  } else if($fallback){
    if($mod[$fallback]){
      $thumb_url = $mod[$fallback];
    }else{
     $thumb_url = get_stylesheet_directory_uri()."/assets/images/default-".$fallback.".png";
    }
  } else {
    $thumb_url = get_stylesheet_directory_uri()."/assets/images/default.png";
  }
  if($thumb_alt == ''){
    $thumb_alt = get_the_title($post_id);
  }
  $ret['src']=$thumb_url;
  $ret['url']=$thumb_url;
  $ret['alt']=$thumb_alt;
  return $ret;
}

?>
