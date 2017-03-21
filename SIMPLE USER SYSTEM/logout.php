<?php namespace ProcessWire;
// https://processwire.com/talk/topic/1716-integrating-a-member-visitor-login-form/#comment-15919
// <a href="http://processwire.com/api/variables/user/">VARIABLES USER</a>

if($user->isLoggedin()) $session->logout();
$session->redirect($pages->get('/')->url); ?>
