<?php namespace ProcessWire;
// LOOK THIS PAGE https://processwire.com/talk/topic/1716-integrating-a-member-visitor-login-form/#comment-15919
if($user->isLoggedin()) $session->redirect($pages->get('/profile/')->url);
if($input->post->username && $input->post->pass) {
 $username = $sanitizer->username($input->post->username);
 $pass = $input->post->pass;
 $u = $users->get($username);
 if($u->id && $u->tmp_pass && $u->tmp_pass === $pass) {
   // user logging in with tmp_pass, so change it to be their real pass
   $u->of(false);
   $u->pass = $u->tmp_pass;
   $u->save();
   $u->of(true);
 }
 $u = $session->login($username, $pass);
 if($u) {
   // user is logged in, get rid of tmp_pass
   $u->of(false);
   $u->tmp_pass = '';
   $u->save();
   // now redirect to the profile edit page
   $session->redirect($pages->get('/profile/')->url);
 }
}

// present the login form
$headline = $input->post->username ? "Login failed" : "Please login"; ?>

<div id="main">

  <h2><?=$headline?></h2>
  <form action='./' method='post'>

    <fieldset>
            <legend>Login To Your Profile Page</legend>
      <p>
        <label>Username <input type='text' name='username'></label>
        <label>Password <input type='password' name='pass'></label>
      </p>
  </fieldset>

  <input type='submit' value='Submit'>

  </form>
  <p><a href='<?=$pages->get("/reset-password/")->url;?>'>Forgot your password?</a></p>

</div>
