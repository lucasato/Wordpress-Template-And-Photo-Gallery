<div class="container">
    <div class="row">
        <div class="comments col-md-9" id="comments">
            <h3 class="mb-2">
                 <?php echo \functions\LangHelper::translate('title_comments', get_comments_number(get_the_ID()));?>
            </h3>

            <?php
            foreach ($comments as $number => $comment) {
            ?>
            <!-- comment -->
            <div class="comment mb-2 row">
                <div class="comment-avatar col-md-1 col-sm-2 text-center pr-1">
                      <?php \functions\HtmlHelper::commentAvatar($comment);?>
                </div>
                <div class="comment-content col-md-11 col-sm-10">
                    <h6 class="small comment-meta">
                        <?php \functions\HtmlHelper::commentAuthor($comment);?>
                        <?php echo $comment->comment_date;?>
                    </h6>
                    <div class="comment-body">
                        <p>
                            <?php echo $comment->comment_content;?>
                            <br>
                            <?php
                            if (is_singular() && get_option('thread_comments')) {

                              //get the setting configured in the admin panel under settings discussions "Enable threaded (nested) comments  levels deep"
                              $max_depth = get_option('thread_comments_depth');
                              //add max_depth to the array and give it the value from above and set the depth to 1
                              $default = array(
                                  'add_below'  => 'comment',
                                  'respond_id' => 'respond',
                                  'reply_text' => __('Reply'),
                                  'login_text' => __('Log in to Reply'),
                                  'depth'      => 1,
                                  'before'     => '',
                                  'after'      => '',
                                  'max_depth'  => $max_depth
                                  );
                              comment_reply_link($default,$comment->comment_ID,$comment->comment_post_ID);
                            ?>
                            <?php
                            }
                            ?>
                        </p>
                    </div>
                </div>
                <?php
                // Find replies to this comment
                $args = array(
                    'status' => 'approve',
                    'parent' => $comment->comment_ID,
                );
                \functions\HtmlHelper::getCommentsTemplate($args, '/template/comments-reply');
                ?>
            </div>
            <?php
            }
            ?>

            </div>
            <!-- /comment -->

        </div>
    </div>
</div>
