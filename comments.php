<?php

//ADD TO HEADER PAGE
/*
<?php if($page->comments): ?>
	 <link rel='stylesheet' type='text/css' href='<?=$config->urls->FieldtypeComments?>comments.css' />
	 <script type='text/javascript' src='<?=$config->urls->FieldtypeComments?>comments.min.js'></script>
 <?php endif; ?>
*/

          // comments
          $limit = 5; // NUMBER OF COMMENTS
          $start = ($input->pageNum - 1) * $limit;
          $comments = $page->comments->slice($start, $limit);
              
           if ($page->comments) {        
            $bl_com = $comments->render(array(
                'headline' => '<h3>Comments</h3>',
                'commentHeader' => 'Dodał {cite} w dn. {created} {stars} {votes}',
                'dateFormat' => 'm/d/y - H:i',
                'encoding' => 'UTF-8',
                'admin' => false, // shows unapproved comments if true
                'replyLabel' => 'Reply',
              ));
            
            $com_f = $page->comments->renderForm(array(
                'headline' => '<h2>Join The Discussion</h2>',
                'pendingMessage' => 'Your comment must be approved by admin',
                'successMessage' => 'Thanks Your comment has been saved',
                'errorMessage' => 'There were errors and the comment was not approved',
                'attrs' => array(
                'id' => 'CommentForm',
                'action' => './',
                'method' => 'post',
                'class' => 'comm-form',
                'rows' => 5,
                'cols' => 50,
                ),
                'labels' => array(
                        'cite' => 'Name',
                        'email' => 'E-Mail',
                        'text' => 'Comment',
                        'submit' => 'Submit',
                    ),
                ));
                echo $bl_com;
                echo $com_f;
          }

            if($input->pageNum > 1) {
                echo "<a href='./page" . ($input->pageNum - 1) . "'>&laquo; Previous</a> ";
            }
            if($start + $limit < count($page->comments)) {
                echo "<a href='./page" . ($input->pageNum + 1) . "'>Next &raquo;</a> ";
            }
    ?>