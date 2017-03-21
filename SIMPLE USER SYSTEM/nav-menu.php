	<!-- top navigation -->
	<ul class='topnav' role='navigation'><?php
		// top navigation consists of homepage and its visible children
		foreach($homepage->and($homepage->children) as $item) {

$reg = $pages->get('/register/');
$log = $pages->get('/login/');
		// Hide Register Page if User Login
if (($user->isLoggedin() && $item == $reg ) || ($user->isLoggedin() && $item == $log) ) {
	 // Do not do anything
} else {

	// Show Menu Without Register Page
	if($item->id == $page->rootParent->id) {

		echo "<li class='current' aria-current='true'><span class='visually-hidden'>Current page: </span>";
	} else {
		echo "<li>";
	}
	echo "<a href='$item->url'>$item->title</a></li>";
	}

}
		// IF Is Super User => Show Edit Link
		if ($user->isSuperuser()) {
			if($page->editable()) echo "<li class='edit'><a href='$page->editUrl'>Edit</a></li>";
		}

// If User Is Login Show Profile And Logout Page
if ($user->isLoggedin()) {

// Get Profile Page and Logout Page
						$prof_page = $pages->get('/profile/');
						$logout_page = $pages->get('/logout/');

$current = '';

if ($prof_page->id == $page->rootParent->id ) {
	 $current = "class='current' aria-current='true'";
}

// Profile Page
			echo "<li $current>
				    <a href='$prof_page->url'>$prof_page->title</a></li>
			</li>";

// Logout Page
			echo "<li>
						<a href='$logout_page->url'>$logout_page->title</a></li>
			</li>";
} // End isLoggedin()
	?></ul>