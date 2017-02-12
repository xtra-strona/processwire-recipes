<?php namespace ProcessWire;
if ($input->urlSegment(1)) {
      if(is_numeric($input->urlSegment(1)) && is_numeric($input->urlSegment(2))){
  } else {
    throw new Wire404Exception();
  }
}
include('./_head.php'); // include header markup

// CREATE DATE TIME FIELD => date_time with this date and slash /important/ => 2016/06/03
// In your template like archive.php, you must select Allow URL Segments?

//Get the name of the blog. Where in the site menu Blog ...  Settings => Name => /blog/
$blog = $pages->get("/blog/");
$startYear = date("Y"); // this year
$endYear = 2014; // or whenever you want it to end
$now = time();

echo "<h3>Select the archives</h3>";
echo "<select size='5' name='form' onchange='location = this.options[this.selectedIndex].value;'>";

//CODE FROM => https://processwire.com/talk/topic/263-creating-archives-for-newsblogs-sections/
for($year = $startYear; $year >= $endYear; $year--) {
   for($month = 12; $month > 0; $month--) {
       $startTime = strtotime("$year-$month-01"); // 2011-12-01 example
       if($startTime > $now) continue; // don't bother with future dates
       if($month == 12) $endTime = strtotime(($year+1) . "-01-01");
           else $endTime = strtotime("$year-" . ($month+1) . "-01");
       $entries = $blog->children("date_time>=$startTime, date_time<$endTime"); // or substitute your own date field
       $date = date("Y-m",$startTime);
       $url = $page->url . date("Y",$startTime) . "/" . date("m",$startTime);
       $count = count($entries);
       if($count > 0)
        echo "<option value='$url'>$date - ($count)</option>";
       // 	echo "<li><a href='$url'>$date <b>" . ' Entries - ' . $count . "</b></a></li>"; // output the month and count
   }
}
   echo "</select>";
//GET URL SEGMENT
     $y = $input->urlSegment(1);
     $m = $input->urlSegment(2);

$date_s = "$y/$m/01";
$date_e = "$y/$m/31";

$page_f = $pages->find("template=single-blog, date_time>=$date_s, date_time<=$date_e, sort=-date_time, limit=15");

if($y !=''){
  echo "<h3>Your archives From -- $date_s -- To -- $date_e</h3>";
}

foreach ($page_f as $key) {
  $bd = strip_tags($key->body);
  $body = substr($bd,0,180);
   echo "<h4><a href='$key->url'>$key->title</a> -- $key->date_time</h4>";
   if ($key->image) {
     echo "<a href='$key->url'><img src='{$key->image->url}' width='150'></a>";
     }
    echo "<p>$body ... <a href='$key->url'>Read More &raquo;</a></p>";
}

echo $page_f->renderPager(array(
    'nextItemLabel' => "Next",
    'previousItemLabel' => "Prev",
    'numPageLinks' => 10,
    'listMarkup' => "<ul class='MarkupPagerNav'>{out}</ul>",
    'itemMarkup' => "<li class='{class}'>{out}</li>",
    'linkMarkup' => "<a href='{url}'><span>{out}</span></a>"
));

include('./_foot.php'); // include footer markup ?>
