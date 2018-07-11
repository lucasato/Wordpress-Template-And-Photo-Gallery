<nav class="navbar navbar-default navbar-static navbar-background">
    <div class="container">
        <div class="navbar-header">
            
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
                <i class="fas fa-bars"></i>
                <span class="sr-only">Toggle navigation</span>
            </button>
        
            <div class="collapse navbar-collapse js-navbar-collapse">
                <?php
                    $args = array(
                        'theme_location'	=>	'primary', // name
                        'container'			=> 	false,
                        'menu_class'		=>	'collapse navbar-collapse',
                        'items_wrap'		=>	'<ul class="nav navbar-nav menu-main col-lg-9 col-sm-9 yamm">%3$s</ul>'
                    );
                    wp_nav_menu($args);
                ?>
            </div>
        </div>
    </div>
</nav>
