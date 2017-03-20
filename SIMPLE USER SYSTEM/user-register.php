<?php namespace ProcessWire; ?>
<div id="main">

  <h1>User Register</h1>

  <?php if($user->isLoggedin()) : ?>

    <h1>You Must Logout To Register</h1>
    <a href="<?=$pages->get('/logout/')->url?>">Loogout</a>
    
 <?php else: ?>

  <form class="" action="./" method="post">

    <input type="text" name="name" value="Name">
    <input type="email" name="email" value="E-Mail">
    <input type="password" name="password" value="Password">
    <input type="submit" name="" value="Submit">

  </form>

<?php

$username = $input->name;
$email = $input->email;
$pass = $input->password;

if($username != '' && $email !='' && $pass !='' ) {

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

    echo "<h1>You've Been Added To The Base</h1>";
}

  } else {
  echo "<h1>Add Data</h1>";
}
 ?>
   <?php endif; ?>
</div>
