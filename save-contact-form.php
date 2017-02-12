<?php namespace ProcessWire;

      // https://processwire.com/talk/topic/16-how-do-i-save-contact-form-submissions-as-pages/

      // 1. Create template with this file contact-form.php
      // 2. Create template with name contact_submission with 4 fields => title(default system), email(default system), comments(textarea), page_title(text)
      // 3. Create Page with template contact-form
      // 4. Submit and save Form :)
if($input->post->submit) {

     $p_name = $page->name;
     $p_path = $page->path;

    // create a new Page instance
    $p = new Page();

    // set the template and parent (required)
    //Add Template contact_submission with 4 fields => title (default), email(default), comments(textarea), page_title(text)
    $p->template = $templates->get("contact_submission");

    $p->parent = $pages->get("$p_path");

    // populate the page's fields with sanitized data
    // the page will sanitize it's own data, but this way no assumptions are made
    $p->title = $sanitizer->text($input->post->fullname);
    $p->email = $sanitizer->email($input->post->email);
    $p->comments = $sanitizer->textarea($input->post->comments);

    $p->page_title = $page->title;

    // PW2 requires a unique name field for pages with the same parent
    // make the name unique by combining the current timestamp with title
    $p->name = $sanitizer->pageName(time() . $p->title);

    if($p->title && $p->email) {
        // our required fields are populated, so save the page
        // you might want to email it here too
        $p->save();
        $content = "<h2>Thank you, your submission has been received.</h2>";

    } else {
        // they missed a required field
        $content = "<p class='error'>One or more required fields was missing.</p>";
    }

} else {
    // no form submitted, so render the form
    $content = "
    <form action='./' method='post'>
        <p>
        <label>Full Name</label>
        <input type='text' name='fullname' />
        </p>

        <p>
        <label>E-Mail</label>
        <input type='email' name='email' />
        </p>

        <p>
        <label>Comments</label>
        <textarea name='comments'></textarea>
        </p>

        <p>
        <input type='submit' name='submit' value='Submit' />
        </p>
    </form>";
}
