<?php namespace ProcessWire;?>

<div id='content'>

<?php
//Change Your Mail
  $your_mail = 'yourmail@gmail.com';
  // Create Field Type E-Mail => my_mail
  if($page->my_mail !='') {
    $your_mail = $sanitizer->email($page->my_mail);
  // echo "Mail Exsist" . '<br />';
  }

//Change Your Subject
  $my_subject = 'My Subject';
  // Create Field Type Text => my_subject
  if($page->my_subject !='') {
     $my_subject = $sanitizer->text($page->my_subject);
  // echo "Subject Exsist" . '<br />';
  }

//Change Your Succ Message
  $success_m = 'Your Message Has Been Sent :)';
  // Create Field Type Text => success_m
  if($page->success_m !='') {
     $success_m = $page->success_m;
 // echo "Success Message Exsist " . '<br />';
}

  // form was submitted so we process the form
  if($input->post->submit) {

       // https://processwire.com/api/ref/session/c-s-r-f/
          if($session->CSRF->hasValidToken()) {

       // https://processwire.com/api/variables/input/
       // https://processwire.com/api/variables/sanitizer/

            $name = $sanitizer->text($input->post->name);
            $email = $sanitizer->email($input->post->email);
            $subject = $sanitizer->textarea($input->post->subject);

  if($name && $email && $subject == true) {

    //https://processwire.com/api/ref/mail/
    $message = $mail->new();
    $message->to($your_mail)->from($your_mail);
    $message->subject($my_subject)->body("Name: $name, E-Mail: $email, Subject: $subject")->bodyHTML("<h4>Name: $name</h4> <h4>E-Mail: $email</h4> <h4>Subject: </h4> <p>$subject</p>");
    $numSent = $message->send();

   //Success Message
      echo  "<div class='uk-alert-success' uk-alert><h3>" . $success_m . "</h3></div>";
   //Yor Submit Message
      echo "<h4>Your Message:</h4>" .
      "<ul>
        <li><b>Name:</b> $name</li>
        <li><b>E-Mail:</b> $email</li>
        <li><b>Subject:</b> $subject</li>
      </ul>";

  } else { ?>

    <div class="uk-alert-danger" uk-alert>
        <a class="uk-alert-close" uk-close></a>
        <p>Your Sanitizer Stopped !!! ... Fill in the fields correctly ...</p>
    </div>

<?php  }

          } else { ?>

            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p>CSRF Token Not Found</p>
            </div>
<?php  }

  } else { ?>

    <!-- DEFAULT FORM IF NOT SUMBIT -->
    <form action="./" method="post">

  <?php // https://processwire.com/talk/topic/3779-use-csrf-in-your-own-forms/
        $tokenName = $this->session->CSRF->getTokenName();
        $tokenValue = $this->session->CSRF->getTokenValue(); ?>

        <input type="hidden" id="_post_token" class="uk-input" name="<?=$tokenName?>" value="<?=$tokenValue?>" placeholder="Name">

        <div class="uk-margin">
          <input class="uk-input" type="text" name="name" value="" placeholder="Name">
        </div>

        <div class="uk-margin">
          <input class="uk-input" type="email" name="email" value="" placeholder="E-Mail">
        </div>

          <div class="uk-margin">
            <textarea name="subject" class="uk-textarea" rows="5" placeholder="Textarea"></textarea>
          </div>

        <input type="submit" name="submit" class="uk-button uk-button-default uk-margin-bottom" value="Submit">

    </form>

  <?php } ?>

</div>
