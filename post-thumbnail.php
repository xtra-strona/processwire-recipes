<?php namespace ProcessWire;

function thumb($key, $args = false) {
  $args == array('w','q','c-img','c-a','lazy','url-img','img','quantity','loop');  


  if(isset($args['w'])) {
      $width = $args['w'];
  } else {
      $width = 640;
  }
  
  if(isset($args['q'])) {
      $quality = $args['w'];
  } else {
      $quality = 90;
  }
  
  if(isset($args['c-img'])) {
      $class_img = $args['c-img'];
  } else {
      $class_img = 'img-fluid mx-auto d-block img-thumbnail';
  }
  
  if(isset($args['c-a'])) {
      $class_a = $args['c-a'];
  } else {
      $class_a = 'custom-class';
  }
  
  if(isset($args['lazy'])) {
      $lazy = $args['lazy'];
  } else {
      $lazy = false;
  }
  
    if(isset($args['img'])) {
      $img = $args['img'];
  } else {
      $img = 'images';
  }
  
   // Default Loop Image
 if(isset($args['loop']) && $args['loop'] == 'true') {
      
    $blog_img = array(
    'quality' => $quality,
    'upscaling' => true,
    'cropping' => 'southeast'
  );
 
 $img_bl = $key->size($width, 'auto', $blog_img);
     
  $img_url = $img_bl->url;
  $img_w = $img_bl->width;
  $img_h = $img_bl->height;
  $img_desc = $img_bl->description;
  if ($img_desc == '') {
    $img_desc = 'img-' . $key->name;
  }
  
  } else {
   
// Image TO Blog or Single $page->image
  //Show Images if exist
  if ($key->$img !='') {
    $blog_img = array(
    'quality' => $quality,
    'upscaling' => true,
    'cropping' => 'southeast'
  );
    
  
  // IF Single Image  
  if(isset($args['quantity']) && $args['quantity'] == 'single') {
      $img_bl = $key->$img->size($width, 'auto', $blog_img);
   // IF Random Image      
  } 
  
  if(isset($args['quantity']) && $args['quantity'] == 'random') {
      $img_bl = $key->$img->getRandom()->size($width, 'auto', $blog_img);
  // IF First Image    
  } 
  
//  if(isset($args['quantity']) && $args['quantity'] == 'first') {
//      $img_bl = $key->$img->first()->size($width, 'auto', $blog_img);
//  }
   // Default first
    if(!isset($args['quantity'])) {
       $img_bl = $key->$img->first()->size($width, 'auto', $blog_img); 
  // IF First Image    
  } 

  $img_url = $img_bl->url;
  $img_w = $img_bl->width;
  $img_h = $img_bl->height;
  $img_desc = $img_bl->description;
  if ($img_desc == '') {
    $img_desc = 'img-' . $key->name;
  }
} else {
  $img_url = 'http://placehold.it/640x420';
  $img_w = $width;
  $img_h = 'auto';
  $img_desc = 'img-' . $key->name;
} 

}
// URL TO IMAGES
   if(isset($args['url-img']) == 'on') {
      $a_s = "<a class='$class_a' href='$img_url'>";
      $a_e = "</a>";
    } else {
        $a_s = "<a class='$class_a' href='$key->url'>";
        $a_e = "</a>";
     } 

 if(isset($args['lazy']) && ($args['lazy'] == 'on')) {
    return "$a_s
              <img class='$class_img lazy-load' script-src='$img_url' alt='$img_desc' height='$img_h' width='$img_w'>
           $a_e";
  } else {
return "$a_s
           <img class='$class_img' src='$img_url' alt='$img_desc' height='$img_h' width='$img_w'>
        $a_e";
  }


} ?>

<div id='content'>
     <h1>1 - SHOW DEFAULT FIRST IMAGE BLOG </h1>
<?php
    // LOOP WITH ITEMS LIKE BLOG
    $show_first_items = $page->children("limit=12");

  foreach ($show_first_items as $first_item) :
      
      // SHOW DEFAULT FIRST IMAGE BLOG     
 echo thumb($first_item,array(
//            'w' => 560, // Width => default = 640
//            'q' => 100, //Quality => default = 90
//            'c-img' => 'custom-class-img', // Add Your Class to <img class='your class'
//            'c-a' => 'Class href', // Add Your Class to <a class='your-class' href=''
//            'lazy' => 'on', // Add Lazy Load to function()
//            'url-img' => 'on', // Url img like $page->images->url;
//            'img' =>'img_test', // Custom name Image Field not default images 
//          'quantity'=>'random' // If Random
 )); 
  
     // End Foreach Loop
                endforeach;
  ?>
     
  <h1>2 - SHOW RANDOM IMAGE BLOG </h1>   
 <?php   
    // LOOP WITH ITEMS LIKE BLOG
    $show_blog_items = $page->children("limit=12");
    
      foreach ($show_blog_items as $random_item) : 
      
 // SHOW RANDOM IMAGE 
 echo thumb($random_item,array(
//            'w' => 560, // Width => default = 640
//            'q' => 100, //Quality => default = 90
//            'c-img' => 'custom-class-img', // Add Your Class to <img class='your class'
//            'c-a' => 'Class href', // Add Your Class to <a class='your class' href=''
//            'lazy' => 'on', // Add Lazy Load to function()
//            'url-img' => 'on', // Url img like $page->images->url;
//            'img' =>'img_test', // Custom name Image Field not default images 
            'quantity'=>'random', // RANDOM IMAGE
 )); 
  
  
   // End Foreach Loop
                endforeach;
   ?>
  
    <h1>3 - SINGLE IMAGE</h1>
  <?php 
  // Single Image $page->image ($page)
  echo thumb($page,array(
//            'w' => 560, // Width => default = 640
//            'q' => 100, //Quality => default = 90
//            'c-img' => 'img-fluid Class Img', // Add Your Class to <img class='your class'
//            'c-a' => 'Class href', // Add Your Class to <a class='your class' href=''
////          'lazy' => 'on', // Add Lazy Load to function()
            'url-img' => 'on', // Url img like $page->images->url;
            'img' =>'single_img', // Custom name Image not default images 
            'quantity'=>'single', // IMPORTANT If Single 
   )); ?>
    
<h1>4 - LOOP IMAGE</h1>
  <?php 
// Default Loop Image
$images = $page->images;
  foreach ($images as $img) {
    echo thumb($img, array(
////              'w' => 670, // Width => default = 640
////              'q' => 100, //Quality => default = 90
////              'c-img' => 'img-fluid class-img', // Add Your Class to <img class='your class'
////              'c-a' => 'class-a-href', // Add Your Class to <a class='your class' href=''
////            'lazy' => 'on', // Add Lazy Load to function()
              'loop'=>'true' // IMPORTANT LOOP IMAGE
     ));
//    
  } // End Loop
 ?>
    
</div><!-- END/#content -->
