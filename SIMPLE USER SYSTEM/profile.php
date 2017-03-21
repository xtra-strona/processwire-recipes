<?php namespace ProcessWire;
// https://processwire.com/talk/topic/1716-integrating-a-member-visitor-login-form/#comment-15919
// <a href="http://processwire.com/api/variables/user/">VARIABLES USER</a>

// if user isn't logged in, then we pretend this page doesn't exist
if(!$user->isLoggedin()) throw new Wire404Exception();
$info = '';

// check if they submitted a password change
$pass = $input->post->pass;
if($pass) {
 if(strlen($pass) < 6) {
   $info .= "<p>New password must be 6+ characters</p>";
 } else if($pass !== $input->post->pass_confirm) {
   $info .= "<p>Passwords do not match</p>";
 } else {

   // $info .= "<p>Your password has been changed to</p>" . "<h2>" . $input->post->pass . "</h2>";
   $message = $mail->new();
   $message->subject('Hello world')
     ->to($user->email)
     ->from($user->email)
     ->body('Your password has been changed to' . $input->post->pass)
     ->bodyHTML('<h2>Your password has been changed to' . $input->post->pass . '</h2>');
   $numSent = $message->send();

   $user->of(false);
   $user->pass = $pass;
   $user->save();
   $user->of(true);

 $info .= "<p>Your password has been changed</p>";

 }
} ?>

<div id="main">
  <h2>Change password</h2>
  <h3><?php echo $info; ?></h3>
  <form action='./' method='post'>
    <fieldset>
              <legend>Confirm password</legend>
        <label>New Password <input type='password' name='pass'></label>
        <label>New Password (confirm) <input type='password' name='pass_confirm'></label>
</fieldset>
  <input type='submit'>
  </form>
  <p><a href='<?=$pages->get('/logout/')->url?>'>Logout</a></p>
</div>
