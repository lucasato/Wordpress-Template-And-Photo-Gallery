<?php
/**
  * Single-Gallery will be the single post equivalent for galleries.
  */
get_header();
if (have_posts()):
    while (have_posts()) : the_post();
        do_shortcode('[gallery id="'.get_the_ID().'" share="true" date="true" type="single-page"]');
    endwhile;
else:
    echo '<p>'. \functions\LangHelper::translate('not_found') .'</p>';
endif;
get_footer();
