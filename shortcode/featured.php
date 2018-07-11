<?php
add_shortcode('featured', 'getFeatured');

function getFeatured($attr = array())
{
    ?>
        <header>
            <h2>
                <?php echo \functions\LangHelper::translate('title_featured_posts');?>
            </h2>
        </header>
        <section class="card-deck">
            <div class="row col-12 text-center card-deck-fix-display">
                <?php \functions\HtmlHelper::featuredPosts(); ?>
            </div>
        </section>
    <?php
}
