<?php
/**
  * First page that will be loaded.
  */
get_header();
?>
<!-- Page Content -->
<div class="container">
    <?php
        do_shortcode('[featured]');
    ?>
    <section>
        <header>
            <h2>
                <?php echo \functions\LangHelper::translate('title_latest_posts');?>
            </h2>
        </header>
        <section class="card-columns">
            <?php \functions\HtmlHelper::posts();?>
        </section>
    </section>
</div>
<?php
get_footer();
