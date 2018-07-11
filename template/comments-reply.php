<?php
foreach ($comments as $number => $comment) {
?>
    <!-- reply is indented -->
    <div class="comment-reply col-md-11 offset-md-1 col-sm-10 offset-sm-2">
        <div class="row">
            <div class="comment-avatar col-md-1 col-sm-2 text-center pr-1">
                <?php \functions\HtmlHelper::commentAvatar($comment);?>
            </div>
            <div class="comment-content col-md-11 col-sm-10 col-12">
                <h6 class="small comment-meta">
                    <?php \functions\HtmlHelper::commentAuthor($comment);?>
                    <?php echo $comment->comment_date;?>
                </h6>
                <div class="comment-body">
                    <p>
                        <?php echo $comment->comment_content;?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- /reply is indented -->
<?php
}
?>
