<?php namespace ProcessWire;
        $username = $email = $pass = "";?>
        
<div id="main">

  <h1>User Register</h1>

  <?php if($user->isLoggedin()) : ?>

    <h1>You Must Logout To Register</h1>
    <a href="<?=$pages->get('/logout/')->url?>">Logout</a>

 <?php else: ?>

  <form class="" action="./" method="post">
		Name: <input type="text" name="name" value="" maxlength="55"><br>
		E-Mail: <input type="email" name="email" value="E-Mail" maxlength="55"><br>
		Password: <input type="password" name="password" value="" maxlength="55"><br>
    <input type="submit" name="sub" value="Submit">
  </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

$username = $input->name;
$email = $input->email;
$pass = $input->password;

if ($sanitizer->email($email)) {

  if( !empty($username) && !empty($email) && !empty($pass) ) {

    $u_n = wire('users')->get("name=$username");
    $u_e = wire('users')->get("email=$email");

    if($u_e !='') {
        echo "This E-Mail Exsist";
      }

      if($u_n !='') {
          echo "This User Name Exsist";
        }

  if ($u_e =='' && $u_n =='') {

  //USER SAVE TO DB => http://cheatsheet.processwire.com/user/user-methods/user-save/
    $item = new User();

      $item->setOutputFormatting(false);
      $item->name = $sanitizer->pageName($username);
      $item->pass = $pass;
      $item->email = $sanitizer->email($email);
      $item->addRole('guest');
      $item->save();

      echo "<h1>You've Been Added To The DataBase</h1>";
  }

          } else {

            echo "<h1>Fill The Fields</h1>";
        }

      } else {

        echo 'Invalid E-MAIL FORMAT';
      }

  }
?>
   <?php endif; ?>
</div>
