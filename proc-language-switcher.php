<?php
    foreach($languages as $language) {
      if(!$page->viewable($language)) continue; // is page viewable in this language?
      if($language->id == $user->language->id) {
        echo "<li class='current'>";
      } else {
        echo "<li>";
      }
      $url = $page->localUrl($language);
      $hreflang = $homepage->getLanguageValue($language, 'name');
      // IMPORTANT => UNCOMENT TO USE IMAGE SWITCHER
      // if($hreflang == 'home') {
      // 	// echo "<a hreflang='$hreflang' href='$url'><img src='{$options->img_def->url}' alt='{$options->img_def->description}' width='50'></a></li>";
      // 	echo "<h1>Default Language</h1>";
      // }
      // if($hreflang == 'pl') {
      // 	// echo "<a hreflang='$hreflang' href='$url'><img src='{$options->img_pl->url}' alt='{$options->img_pl->description}' width='50'></a></li>";
      // 	echo "<h1>Polish Language</h1>";
      // }
      // if($hreflang == 'en') {
      // 	// echo "<a hreflang='$hreflang' href='$url'><img src='{$options->img_en->url}' alt='{$options->img_en->description}' width='50'></a></li>";
      // 	echo "<h1>English Language</h1>";
      // }
      echo "<a hreflang='$hreflang' href='$url'>$language->title</a></li>";
    }
