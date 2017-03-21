<?php namespace ProcessWire;
        $username = $email = $pass = "";?>

<div id="main">

  <h1>User Register</h1>

  <?php if($user->isLoggedin()) : ?>

    <h1>You Must Logout To Register</h1>
    <a href="<?=$pages->get('/logout/')->url?>">Loogout</a>

 <?php else:

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$username = $input->post->name;
$email = $input->post->email;
$pass = $input->post->password;
$conf_pass = $input->post->confirm_password;

if ($sanitizer->email($email)) {

  if( !empty($username) && !empty($email) && !empty($pass) && !empty($conf_pass) ) {

    $u_n = wire('users')->get("name=$username");
    $u_e = wire('users')->get("email=$email");

    if($u_e !='') {
        echo "This E-Mail Exsist";
      }

      if($u_n !='') {
          echo "This User Name Exsist";
        }

  if ($u_e =='' && $u_n =='') {

if ($pass == $conf_pass ) {
  //USER SAVE TO DB => http://cheatsheet.processwire.com/user/user-methods/user-save/
    $item = new User();

      $item->setOutputFormatting(false);
      $item->name = $sanitizer->pageName($username);
      $item->pass = $pass;
      $item->email = $sanitizer->email($email);
      $item->addRole('guest');
      $item->save();

      echo "<h1>You've Been Added To The DataBase</h1>";

} else {
  echo "<h1>Passwords Don't Match</h1>";
}

 }
      } else {
        echo "<h1>Fill The Fields</h1>";
      }

          } else {
            echo 'Invalid E-MAIL FORMAT';
          }

  }
?>
<form class="" action="./" method="post">
  <fieldset>
      <legend>Registration Page</legend>
      <p>
      <label for="">Name: <input type="text" name="name"  maxlength="55"></label>
      <label for="">E-Mail: <input type="email" name="email"  maxlength="55"></label>
      <label for="">Password: <input type="password" name="password" id="password" maxlength="55"></label>
      <label for="">Confirm Password: <input type="password" name="confirm_password" id="confirm_password" maxlength="55"></label>
      <input type="submit" name="sub" value="Submit">
    </p>
    </fieldset>
</form>

<script type="text/javascript">
  var password = document.getElementById("password")
  , confirm_password = document.getElementById("confirm_password");

  function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
  }

  password.onchange = validatePassword;
  confirm_password.onkeyup = validatePassword;
</script>

   <?php endif; ?>
</div>
