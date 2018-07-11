<?php
/**
  * Manage only page content
  */
get_header();
?>
<!-- Page Content -->
<div class="container">
      <?php
      if(have_posts()):
          while (have_posts()) : the_post();
          ?>
                <header>
                  <h1 class="page-header text-left">
        					       <?php the_title();?>
                  </h1>
                </header>

              <section>
                <?php the_content();?>
              </section>
          <?php
          endwhile;
      else:
          echo '<p>'. \functions\LangHelper::translate('not_found') .'</p>';
      endif;
      ?>
</div>
<?php
get_footer();
