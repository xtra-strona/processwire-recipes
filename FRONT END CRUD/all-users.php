<?php namespace ProcessWire; 
    $show_items = $page->children('limit=6');
    $count = count($page->children());
    $root = $pages->urls->root;
    $message = ''; 
    
echo "<div id='content'>";
 echo "<h2>All Users: " . $count . "</h2>";

    $ur_w = wire('page')->url;
    $ur_p = $_SERVER['REQUEST_URI'];
    
    if(count($show_items) == 0 && $ur_w != $ur_p) {
        
        $session->redirect($page->url);
        
    }
     
//USER MUST BE LOGIN    
 if($user->isLoggedin() && $user->isSuperuser() ) {

if(isset($input->post->del)) {
    
$thisone = $pages->get($input->deleting);
$pages->delete($thisone);

$session->redirect($_SERVER['REQUEST_URI']);

}   
    
// front-end form example with multiple images upload 
// add new page created on the fly and adding images
if(isset($_POST['submit_form'])){
        $name = $input->post->title;
        $last_n = $input->post->last_name;
        
 //SOMATONIC UPLOAD IMAGES => https://gist.github.com/somatonic/4150974

     // tmp upload folder for additional security
    // possibly restrict access to this folder using htaccess:
    // # RewriteRule ^.tmp_uploads(.*) - [F]
        
    $upload_path = $config->paths->root . "tmp_uploads/";
    // new wire upload
    $u = new WireUpload('uploads');
    $u->setMaxFiles(2);
    $u->setMaxFileSize(200*1024);
    $u->setOverwrite(false);
    $u->setDestinationPath($upload_path);
    $u->setValidExtensions(array('jpg', 'jpeg', 'gif', 'png'));
    // execute upload and check for errors
    $files = $u->execute();
    if(!$u->getErrors()){
        
        //create-page-via-api => https://processwire-recipes.com/recipes/create-page-via-api/
        // create the new page to add the images
        
        $p = new Page();
        $p->template = "single-user";
//        $p->parent = wire('pages')->get('/users/'); // example parent
        $p->parent = $pages->get("/users/");
        $p->title = $sanitizer->text($name);
        $p->name = $sanitizer->pageName(time() . $name);
        $p->last_name = $sanitizer->text($last_n);
        $p->save();
        // add images upload
        foreach($files as $filename) {
            $p->images = $upload_path . $filename;
        }
        // save page
        $p->save();
        // remove all tmp files uploaded
        foreach($files as $filename) unlink($upload_path . $filename);
        $message .= "<p class='message'>Files uploaded!</p>";
    } else {
        // remove files
        foreach($files as $filename) unlink($upload_path . $filename);
        // get the errors
        foreach($u->getErrors() as $error) $message .= "<p class='error'>$error</p>";
    }
    
//    $session->redirect($root . "users/"); 
  $session->redirect($_SERVER['REQUEST_URI']);
}
?>

        <table class="table">
          <thead>
            <tr>
                
              <th>#</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Add Image</th>
              <th>Delete</th>
              
            </tr>
          </thead>
        <tbody>
            
<?php
$i = 0;
foreach ($show_items as $value) { 
$i++;
$id = $value->id; ?>
            
 <tr>
    
   <th scope='row'><?=$i?></th>
  
        <?php if(!$value->title) { ?>
            <td><?="<edit $id.title>";?><b>Add Title</b></edit></td>
        <?php } else { ?>
            <td><?="<edit $id.title>";?><?=$value->title;?></edit></td>
        <?php } ?> 
            
            <?php if(!$value->last_name) { ?>
              <td><?="<edit $id.last_name>";?><b>Add Value</b></edit></td>
           <?php } else { ?>
              <td><?="<edit $id.last_name>";?> <?=$value->last_name;?> </edit></td>
           <?php } ?>
              <?php if($value->images != '') { ?>
              <td>
                      <?="<edit $id.images>";?><img src='<?php echo $value->images->first()->url;?>' width='50'></edit>
              </td>
              <?php } else { ?>
                    <td>
                      <?="<edit $id.images>";?><h5>Add Image ('Double Click)</h5></edit>
                    </td>
             <?php } ?> 

                <td>
                   <!--<form method='post' action='./'>-->
                   <form method='post' action='<?=$_SERVER['REQUEST_URI'];?>'>
                     <input type='hidden' value='<?=$value->path?>' name='deleting'>
                     <input type='submit' name='del' value='Delete'>
                   </form> 
                </td>
   
</tr>

 <?php } // Endforeach ?>

        </tbody> 
</table> 
                      
          <h4><?=$message?></h4>
    <!--USE BOOTSTRAP 4 ALPHA https://v4-alpha.getbootstrap.com/components/forms/ -->
    <form class="form-inline" method="post" action='<?=$_SERVER['REQUEST_URI'];?>' enctype="multipart/form-data">
    
             <div class="input-group mb-2 mr-sm-2 mb-sm-0">
               <input type='text' name='title'>
             </div>
        
            <div class="input-group mb-2 mr-sm-2 mb-sm-0">
               <input type='text' name='last_name'>
            </div> 
 
             <input type="file" name="uploads[]" multiple="multiple"  size="40" accept="image/jpg,image/jpeg,image/gif,image/png"/>
    
        <input type='submit' class='btn btn-default' name='submit_form' value="Add User" />
    </form> 

    <hr>
    
    <!--USE BOOTSTRAP 4 ALPHA https://v4-alpha.getbootstrap.com/components/pagination/ -->
    
        <div id='pagination' class='d-flex justify-content-center'>
        <?php //Pagination
            echo $show_items->renderPager(array(
              // 'nextItemLabel' => __('Next') . ' &raquo;',
              // 'previousItemLabel' => '&laquo; ' . __('Prev'),
              'nextItemLabel' => '&raquo;',
              'previousItemLabel' => '&laquo;',
//              'numPageLinks' => 2,
              'listMarkup' => "<ul class='pagination pagination-lg d-flex flex-wrap'>{out}</ul>",
              'itemMarkup' => "<li class='page-item'>{out}</li>",
              'linkMarkup' => "<a class='page-link' href='{url}'><span>{out}</span></a>",
              'currentLinkMarkup' => "<a class='btn btn-lg btn-primary' href='{url}'>{out}</a>",
              'separatorItemClass' => 'sep-class',
              // 'separatorItemLabel' => '&nbsp &raquo; ' . __('Click Next'),
              'separatorItemMarkup' => "<li class='page-item'> <a class='btn btn-lg active btn-secondary'>[ ... ] </a> </li>",
        )); ?>
    </div>
    
 <?php } else {
     echo '<h1>Login To Show Your Users</h1>';
 } ?>  
    
</div>
