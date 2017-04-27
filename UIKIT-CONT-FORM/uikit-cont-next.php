<?php namespace ProcessWire;?>

<div id='content'>

  <?php
  //Change Your Mail
    $your_mail = 'mymail@gmail.com';
    // Create Field Type E-Mail => my_mail
    if($page->my_mail !='') {
      $your_mail = $sanitizer->email($page->my_mail);
  //  echo "Mail Exsist" . '<br />';
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


// https://processwire.com/talk/topic/2089-create-simple-forms-using-api/

  // when processing form (POST request), check to see if token is present
  $out = '';
  // create a new form field (also field wrapper)
  $form = $modules->get("InputfieldForm");

// https://processwire.com/talk/topic/2089-create-simple-forms-using-api/?page=6

  $form->setMarkup(array(
  'list' => "<div {attrs}>{out}</div>",
  'item' => "<div {attrs}>{out}</div>",
  'item_label' => "<label class='uk-form-label' for='{for}'>{out}</label>",
  'item_content' => "{out}",
  'item_error' => "<p>{out}</p>",
  'item_description' => "<p>{out}</p>",
  'item_head' => "<h2>{out}</h2>",
  'item_notes' => "<p class='notes'>{out}</p>",
));

$form->setClasses(array(
  'list' => '[list-class]',
  'list_clearfix' => '',
  'item' => '{class}',
  'item_required' => '',
  'item_error' => 'uk-alert-danger uk-padding-small',
  'item_collapsed' => '',
  'item_column_width' => '',
  'item_column_width_first' => ''
));

  $form->action = "./";
  $form->method = "post";
  $form->attr("id+name",'contact-form');

  // create a text input
  $field = $modules->get("InputfieldText");
  $field->label = "Imie";
  $field->attr('id+name','name');
  $field->required = 1;
  $field->addClass('uk-input');
  $form->append($field); // append the field to the form

  // create email field
  $field = $modules->get("InputfieldEmail");
  $field->label = "E-Mail";
  $field->attr('id+name','email');
  $field->required = 1;
  $field->addClass('uk-input');
  $form->append($field); // append the field

// create textarea field
  $field = $modules->get("InputfieldTextarea");
  $field->label = "Subject";
  $field->attr('id+name','subject');
  $field->required = 1;
  $field->setAttribute('rows', 3);
  $field->addClass('uk-textarea');
  $form->append($field); // append the field to the form

  // oh a submit button!
  $submit = $modules->get("InputfieldSubmit");
  $submit->attr("value","Wyślij");
  $submit->attr("id+name","submit");
  $submit->addClass('uk-button uk-button-primary uk-margin-top');
  $form->append($submit);

  // form was submitted so we process the form
  if($input->post->submit) {

      // user submitted the form, process it and check for errors
      $form->processInput($input->post);

      // here is a good point for extra/custom validation and manipulate fields
      $email = $form->get("email");

      if($form->getErrors()) {

       echo $out .= $form->render();

      } else {

       // https://processwire.com/api/ref/session/c-s-r-f/
          if($session->CSRF->hasValidToken()) {

       // https://processwire.com/api/variables/input/
       // https://processwire.com/api/variables/sanitizer/

            $name = $sanitizer->text($input->post->name);
            $email = $sanitizer->email($input->post->email);
            $subject = $sanitizer->textarea($input->post->subject);

if($name && $email && $subject == true) {

echo  $success_m  . '<br />';

  echo "<h3>Name: $name</h3>";
  echo "<h3>E-Mail: $email</h3>";
  echo "<h3>Subject: $subject</h3>";


//https://processwire.com/api/ref/mail/
$message = $mail->new();
$message->to($your_mail)->from($your_mail);
$message->subject($my_subject)->body("Name: $name, E-Mail: $email, Subject: $subject")->bodyHTML("<h4>Name: $name</h4> <h4>E-Mail: $email</h4> <h4>Subject: </h4> <p>$subject</p>");
$numSent = $message->send();

} else {

   echo "<h1>Your Sanitizer Stopped !!! ... Fill in the fields correctly ...</h1>";

}

          } else {
            // form submission is NOT valid
            echo "Token Not Found";

          }

      }

  } else {

    //  render out form without processing
      echo $out .= $form->render();

  } ?>

  </div>
