<?php
get_header();
?>
    <!-- Page Content -->
    <div class="container">
    <?php
        echo \functions\HtmlHelper::breadcrumb();
    ?>
		<div class="row text-center">
            <section class="card-columns">
                <?php \functions\HtmlHelper::posts();?>
            </section>

            <section class="paginate col-lg-12 text-right">
                <?php
                    // Pagination start
                    echo paginate_links();
                ?>
            </section>
		</div>
    </div>
<?php
get_footer();
