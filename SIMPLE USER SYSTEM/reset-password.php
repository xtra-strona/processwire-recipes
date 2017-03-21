<?php namespace ProcessWire;
// https://processwire.com/talk/topic/1716-integrating-a-member-visitor-login-form/#comment-15919
// <a href="http://processwire.com/api/variables/user/">VARIABLES USER</a>

$showForm = true;
$messge_front = '';
$email = $sanitizer->email($input->post->email);
if($email) {
 $u = $users->get("email=$email");
 if($u->id) {
   // generate a random, temporary password
   $pass = '';
   $chars = 'abcdefghjkmnopqrstuvwxyz23456789'; // add more as you see fit
   $length = mt_rand(9,12); // password between 9 and 12 characters
   for($n = 0; $n < $length; $n++) $pass .= $chars[mt_rand(0, strlen($chars)-1)];
   $u->of(false);
   $u->tmp_pass = $pass; // populate a temporary pass to their profile
   $u->save();
   $u->of(true);
   $message = "Your temporary password on our web site is: $pass\n";
   $message .= "Please change it after you login.";
   mail($u->email, "Password reset", $message, "From: noreply@{$config->httpHost}");
   $messge_front = "<p>An email has been dispatched to you with further instructions.</p>";
   $showForm = false;
 } else {
   $messge_front = "<p>Sorry, account doesn't exist or doesn't have an email.</p>";
 }
}
?>
<div id="main">
<h1><?php echo $messge_front; ?></h1>
    <?php if($showForm) : ?>
        <form action='./' method='post'>
          <fieldset>
            <legend>Reset your password </legend>
                <label>E-Mail <input type='email' name='email'></label>
                <input type='submit'>
         </fieldset>
        </form>
    <?php endif; ?>

</div>
