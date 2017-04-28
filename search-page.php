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
    // Create select options field ( film_genre ) with option like (Horror, Sci-Fi, Action)
         $film_genre = $fields->get('film_genre');
         $all_options = $film_genre->type->getOptions($film_genre);

// Creatre Directors, Director Template (directors.php, director.php) and page name Directors
// Create Page Reference Field ( reference_directors ) with Reference to directors.php 
 $all_directors = $pages->get("/directors/")->children;

  //https://processwire.com/api/variables/sanitizer/
       $search = $sanitizer->text($input->get->search);
       $dir =  $sanitizer->pageName($input->get->directors);
       $genre = $sanitizer->int($input->get->film_genre);

 // https://processwire.com/api/selectors/
        if($dir != '') {
           $dir = "reference_directors.name=$dir";
       }

        if($genre != '') {
           $genre = "film_genre=$genre";
       }

//Search Results
 // https://processwire.com/api/selectors/
 // $children_movie = $page->children("template=single-movie, body|title%=$search, $dir, $genre, limit=4");
 // $children_movie = $pages->find("template=single-movie, body|title%=$search, $dir, $genre, limit=3");
        $children_movie = $page->children("body|title%=$search, $dir, $genre, limit=1");


   if(count($children_movie) == 0) {
        echo '<h1>No Movie</h1>';
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
                                     <?php  $genre = $movies->film_genre;

                                          foreach ($genre as $value) {

                                             echo '<li>' . $value->title . '</li>';

                                          }
                                     ?>
                            </ul>
                    </div>

               <div class='uk-margin-left'>
                    <ul class='uk-subnav'>
                      <li><b>Directors:</b></li>
                          <?php
                            if ($movies->reference_directors) {
                               echo $movies->reference_directors("<a href='{url}'>{title}</a>");
                             }
                          ?>
                    </ul>
                </div>

     </div>

           <div>
               <div class="uk-card-body">
                   <h3 class="uk-card-title">
                     <?=$movies->title;?>
                     <?php if($movies->year) { ?> <small>(<?=$movies->year;?>)</small> <?php } ?>
                   </h3>
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
            <input name='search' class="uk-input" type="text" placeholder="Search Movie">
        </div>

    <div class="uk-margin">
        <select name='film_genre' class="uk-select">
            <option value=''>All Genres</option>
        <?php // https://processwire.com/api/modules/select-options-fieldtype/
                foreach ($all_options as $value) {
                   echo "<option value='$value'>$value->title</option>";
                } ?>
        </select>
    </div>

    <div class="uk-margin">
        <select name='directors' class="uk-select">
            <option value=''>Select Directors</option>
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
// PAGINATION
       if($dir != '') {
           $my_dir =  $sanitizer->pageName($input->get->directors);
       } else {
           $my_dir = '';
       }

        if($genre != '') {
           $my_gat = $sanitizer->int($input->get->gatunek);
       } else {
           $my_gat = '';
       }

      if($search != '') {
           $my_search = $sanitizer->text($input->get->search);
       } else {
           $my_search = '';
       }

echo ukPagination($children_movie, array('previous'=>'Previous','next'=>'Next','getVars' => array('gatunek' => $my_gat,'directors' => $my_dir, 'search' => $my_search)));?>

</main>
