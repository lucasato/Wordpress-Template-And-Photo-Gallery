<?php
/**
  * Single will show the post Content
  */
get_header();
?>

<!-- Page Content -->
<article class="container">
  <?php
  if (have_posts()):
      while (have_posts()) : the_post();
      ?>
          <!-- Breadcrumb -->
          <?php echo \functions\HtmlHelper::breadcrumb();?>

        	<!-- Title + excerpt -->
        	<header>
        		<h1>
        			<?php the_title();?>
        		</h1>
        	</header>
        	<p>
        		<?php
                    echo get_the_excerpt();
                ?>
        	</p>

        	<!-- Share & Author -->
        	<address class="row">
        		<div class="col-lg-6 col-md-6 col-xs-12">
        			<?php
                        echo \functions\HtmlHelper::share();
                    ?>
        		</div>
        		<div class="col-lg-6 col-md-6 col-xs-12 author text-right">
        			<?php
                  echo \functions\HtmlHelper::author();
              ?>
        			<?php
                  echo \functions\HtmlHelper::commentsTotal();
              ?>
        		</div>
        	</address>

        	<!-- Image post -->
        	<section class="post-caption text-center">
        		<?php
                if (has_post_thumbnail(get_the_ID())):
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'banner-image');
                    echo "<img class='img-fluid' src='$image[0]' />";
                endif;
                ?>
        	</section>

        	<!-- Post content -->
        	<div class="col-sm-12 col-md-12 col-sm-6 col-xs-12">
        		<p class="text">
        			<?php the_content();?>
        		</p>
        	</div>

        	<!-- Comments -->
        	<section class="comments" id="comments">
        		<?php
                    echo \functions\HtmlHelper::comments(get_the_ID());
                ?>
        	</section>
        <?php
        endwhile;
    else:
        echo '<p>'. \functions\LangHelper::translate('not_found') .'</p>';
    endif;
?>
</article>
<?php
get_footer();
