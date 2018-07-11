<?php
get_header();
?>
    <div class="container">
    		<header>
    			<h1 class="page-header text-left">
      				<?php
                  echo \functions\HtmlHelper::getTitleArchive();
              ?>
    			</h1>
    		</header>

    		<!-- Page Content -->
    		<div class="col-12 text-center">
    			<section class="card-columns">
    				<?php \functions\HtmlHelper::posts();?>
    			</section>
    		</div>
    </div>
<?php
get_footer();
