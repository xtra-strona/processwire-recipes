<?php namespace ProcessWire;
// https://github.com/ryancramerdesign/regular
// https://github.com/ryancramerdesign/AdminThemeUikit

// https://processwire.com/api/variables/input/

// https://getuikit.com/docs/introduction

//https://www.w3schools.com/php/php_forms.asp
//http://phpkurs.pl/przekazywanie-danych/
?>

<main id='main' class="uk-container uk-container-medium">  
<?php 
//Reset Search
 $search = '';
 
    // https://processwire.com/api/modules/select-options-fieldtype/
         $field = $fields->get('gatunek');
         $all_options = $field->type->getOptions($field);                  
 $all_directors = $pages->get("/rezyserzy/")->children;   

  //https://processwire.com/api/variables/sanitizer/    
       $search = $sanitizer->text($input->get->search);
       $dir =  $sanitizer->pageName($input->get->directors);
       $gat = $sanitizer->int($input->get->gatunek);
    
 // https://processwire.com/api/selectors/	
        if($dir != '') {
           $dir = "reference_directors.name=$dir";
       }
       
        if($gat != '') {
           $gat = "gatunek=$gat";
       }
       
 // https://processwire.com/api/selectors/
           $children_movie = $page->children("template=single-movie, body|title%=$search, $dir, $gat, limit=4");
// $children_movie = $pages->find("template=single-movie, body|title%=$search, $dir, $gat, limit=3");

   if(count($children_movie) == 0) {
        echo '<h1>Brak Podanego Filmu</h1>';
   }
 ?> 
    
    <div uk-grid>

    <div class="movie-cont uk-width-2-3@m">  
          <?php foreach ($children_movie as $movies) { ?>

        <div class="uk-card uk-card-default uk-grid-collapse uk-child-width-1-2@s uk-margin" uk-grid>

           <div class="uk-card-media-left uk-cover-container uk-margin-top uk-text-center">
             <?php if($movies->images != '') { ?>  
               <img src="<?=$movies->images->first->url;?>"  width='250' height='120' alt="">
             <?php } ?>
               <canvas width="300" height="0"></canvas>
               
                    <div class='uk-margin-left'>
                        
                             <ul class='uk-subnav uk-subnav-divider'>
                                 <li><b>Gatunek:</b></li>
                                     <?php  $gat = $movies->gatunek;

                                          foreach ($gat as $value) {

                                             echo '<li>' . $value->title . '</li>';

                                          }
                                     ?> 
                            </ul>
                    </div>
               
               <div class='uk-margin-left'>
                        
                             <ul class='uk-subnav'>
                                 <li><b>Reżyseria:</b></li>
                    <?php 
                            if($movies->reference_directors) {
                       echo $movies->reference_directors("<a href='{url}'>{title}</a>");
                     }
                    ?> 
                            </ul>
                </div>
               
     </div>

           <div>
               <div class="uk-card-body">
                   <h3 class="uk-card-title"><?=$movies->title;?> <small>(<?=$movies->year;?>)</small></h3>
                   <p><?=$movies->body;?></p>
               </div>
           </div>

         </div>
          <?php } // END FOREACH MOVIES ?>
    </div>  
    
    
<aside id='sidebar' class='uk-width-1-3@m'>
      
 <form method='get' action='./'>
     
    <fieldset class="uk-fieldset">

        <div class="uk-margin">
            <input name='search' class="uk-input" type="text" placeholder="Znajdź Film">
        </div>

    <div class="uk-margin">
        <select name='gatunek' class="uk-select">
            <option value=''>Wszystkie Gatunki</option>
        <?php // https://processwire.com/api/modules/select-options-fieldtype/
                foreach ($all_options as $value) {
                   echo "<option value='$value'>Gat $value->title</option>";
                } ?>
        </select>
    </div>

        <div class="uk-margin">
        <select name='directors' class="uk-select">
            <option value=''>Wybierz Reżysera</option>
        <?php  
            foreach($all_directors as $item) {
               echo "<option value='{$item->name}'>{$item->title}</option>";
            }
        ?>
        </select>
    </div>    
        
    </fieldset>
        
        <input type='submit' class="uk-button uk-button-default" value='Submit' name='sub'>
        
</form>
    
	<?=page()->sidebar?>
	
</aside>
  
    </div>
    
   <?php 
   
       if($dir != '') {
           $my_dir =  $sanitizer->pageName($input->get->directors);
       } else {
           $my_dir = '';
       }
       
        if($gat != '') {
           $my_gat = $sanitizer->int($input->get->gatunek);
       } else {
           $my_gat = '';
       }
          
      if($search != '') {
           $my_search = $sanitizer->text($input->get->search);
       } else {
           $my_search = '';
       }
   
echo ukPagination($children_movie, array('previous'=>'Poprzedni','next'=>'Następny','getVars' => array('gatunek' => $my_gat,'directors' => $my_dir, 'search' => $my_search)));?> 

</main>