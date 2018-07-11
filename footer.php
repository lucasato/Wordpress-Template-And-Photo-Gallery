<!-- Footer -->
<section class="navbar-background footer">
    <div class="container">
        <div class="row footer">
                <div class="col-lg-4 footer-container text-justify">
                    <?php if (is_active_sidebar('footer_left_area')) : ?>
                        <?php dynamic_sidebar('footer_left_area'); ?>
                    <?php endif; ?>
                </div>
                <div class="col-lg-4 footer-container text-justify">
                    <?php if (is_active_sidebar('footer_center_area')) : ?>
                        <?php dynamic_sidebar('footer_center_area'); ?>
                    <?php endif; ?>
                </div>
                <div class="col-lg-4 footer-container text-justify">
                    <?php if (is_active_sidebar('footer_right_area')) : ?>
                        <?php dynamic_sidebar('footer_right_area'); ?>
                    <?php endif; ?>
                </div>
        </div>
    </div>
</section>

<div class="footer-bottom text-center">
    © 2018
    <a href="https://github.com/lucasato/Wordpress-Template-And-Photo-Gallery">lucasato</a> |
    <a href="#">Terms & Conditions</a>
</div>

<?php wp_footer();?>
</body>
</html>
