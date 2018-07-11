<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
    <meta charset="<?php bloginfo('charset');?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <?php wp_head(); ?>
</head>

<body <?php body_class();?>>
  	<script>
  	// home path js
  	var set_path='<?php echo get_home_url();?>';
  	</script>
    
    <!-- Top header -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-6 col-xs-12">

                <!-- Brand Logotype -->
                <header>
                    <a href="<?php echo get_home_url();?>/index.php" class="logo-text">
                        <?php
                            $custom_logo_id = get_theme_mod('custom_logo');
                            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                            if (has_custom_logo()) {
                                echo '<img src="'. esc_url($logo[0]) .'" alt="logo" title="Home"/>';
                            } else {
                                echo '<span>'. get_bloginfo('name') .'</span>';
                            }
                        ?>
                    </a>
                </header>
            </div>

            <!-- Header bar -->
            <div class="col-lg-4 col-md-6 col-xs-12 text-right search-top">
                <?php
                    echo \functions\HtmlHelper::searchBar();
                ?>
            </div>
        </div>
    </div>

    <!-- Menu NavBar -->
    <?php
    get_template_part('menu');
