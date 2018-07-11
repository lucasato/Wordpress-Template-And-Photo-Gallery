<?php
define('TEMPLATE_URL', get_template_directory_uri());
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// Require functions
require(__DIR__ .'/functions/HtmlHelper.php');
require(__DIR__ .'/functions/LangHelper.php');
require(__DIR__ .'/functions/GalleryHelper.php');
require(__DIR__ .'/functions/MetaPassword.php');
require(__DIR__ .'/functions/MetaFeatured.php');

//Add featured shortcode
require(__DIR__ .'/shortcode/featured.php');
//Add gallery shortcode
require(__DIR__ .'/shortcode/gallery.php');

// Wordpress 4.7
if (wp_doing_ajax()) {
    //Add gallery ajax methods
    require(__DIR__ .'/admin_gallery/gallery-ajax-upload.php');
}
//Register AJAX WP-ADMIN URL
add_action('admin_footer', 'set_ajax_url');

//Set admin ajax url
function set_ajax_url()
{
    if (!isset($_POST['action'])):
    ?>
    <script>
       var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>
    <?php
  endif;
}

add_action('admin_enqueue_scripts', 'admin_assets_enqueue');

function admin_assets_enqueue()
{
    //Activate modal window
    add_thickbox();

    // Basic
    wp_enqueue_style('style', get_stylesheet_uri());

    // Custom
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_style('wp-jquery-ui-dialog');
    wp_enqueue_script('gallery-file-upload', get_template_directory_uri(). '/admin_gallery/assets/js/gallery-file-upload.js', array( 'jquery' ), '1.0', true);
    wp_enqueue_script('gallery-admin', get_template_directory_uri(). '/admin_gallery/assets/js/gallery-admin.js', array( 'jquery' ), '1.0.1', true);
    wp_enqueue_script('uploadfile.min', get_template_directory_uri(). '/admin_gallery/assets/js/jquery.uploadfile.min.js', array( 'jquery' ), '1.0.1', true);
    wp_enqueue_style('uploadfile.style', get_template_directory_uri(). '/admin_gallery/assets/css/uploadfile.min.css', false, '1.0', 'all');
    wp_enqueue_style('custom_gallery', get_template_directory_uri(). '/admin_gallery/assets/css/custom_gallery.css', false, '1.0', 'all');
    wp_enqueue_script('loadingoverlay_progress.min', get_template_directory_uri().'/script/loadingoverlay_progress.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('loadingoverlay.min', get_template_directory_uri().'/script/loadingoverlay.min.js', array('jquery'), '1.0', true);
}

//Set sitesetup
add_action('wp_enqueue_scripts', 'siteSetup');

//Include scripts
function siteSetup()
{
    // Basic
    wp_enqueue_style('style', get_stylesheet_uri());

    // Enable comment replies
    wp_enqueue_script('comment-reply');

    // Jquery + Bootstrap
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri(). '/script/jquery.js', false, '1.0', true);
    wp_enqueue_script('bootstrap.min', get_template_directory_uri(). '/script/bootstrap/bootstrap.min.js', array( 'jquery' ), '1.0', true);
    wp_enqueue_style('bootstrap.min', get_template_directory_uri(). '/css/style.css', false, '1.0', 'all');

    // Google font oswald & Noto
    wp_enqueue_style('font-oswald', 'https://fonts.googleapis.com/css?family=Noto+Sans:400,700|Oswald:400,600', false, '1.0', 'all');

    // Gallery
    wp_enqueue_style('easy-gallery', get_template_directory_uri(). '/css/easyGallery.css', false, '1.0', 'all');
    wp_enqueue_script('easy-gallery', get_template_directory_uri(). '/script/easyGallery.js', array( 'jquery' ), '1.2', true);
    wp_enqueue_style('custom_lucasato_gallery', get_template_directory_uri(). '/admin_gallery/assets/css/custom_gallery.css', false, '1.0', 'all');

    // Custom script
    wp_enqueue_script('ready', get_template_directory_uri(). '/script/ready.js', array( 'jquery' ), '1.2', true);
}

//Custom logo upload.
function themename_custom_logo_setup()
{
    $defaults = array(
        'height'      => 177,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    );
    add_theme_support('custom-logo', $defaults);
}

// Add logo support
add_action('after_setup_theme', 'themename_custom_logo_setup');

//Post thumbnails support
add_theme_support('post-thumbnails');
add_image_size('small-thumbnail', 200, 200, true);
add_image_size('medium-thumbnail', 350, 350, true);
add_image_size('web-thumbnail', 800, 600, true);
add_image_size('banner-image', 1600, 500, true);


//Excerpt for individual posts
function lucasato_get_the_excerpt($post_id)
{
    $output = get_the_excerpt($post_id);
    return $output;
}

//Excerpt
function custom_excerpt_length($length)
{
    return 9; //Number of words
}

add_filter('excerpt_length', 'custom_excerpt_length');



//Register menus
register_nav_menus(array(
    'primary'	=>	__('Primary Menu'),
));

function register_widget_area()
{
    register_sidebar(array(
      'name'          => 'Footer Left',
      'id'            => 'footer_left_area',
      'before_widget' => '<p>',
      'after_widget'  => '</p>',
      'before_title'  => '<h4>',
      'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => 'Footer Center',
        'id'            => 'footer_center_area',
        'before_widget' => '<p>',
        'after_widget'  => '</p>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
      ));

    register_sidebar(array(
      'name'          => 'Footer Right',
      'id'            => 'footer_right_area',
      'before_widget' => '<p>',
      'after_widget'  => '</p>',
      'before_title'  => '<h4>',
      'after_title'   => '</h4>',
    ));
}

add_action('widgets_init', 'register_widget_area');

//woocommerce support
add_action('after_setup_theme', 'woocommerce_support');
function woocommerce_support()
{
    add_theme_support('woocommerce');
}

// Metaboxes
// $metaPassword = new \functions\metaPassword();
$metaFeatured = new \functions\metaFeatured();

// Gallery

/**
  * Admin Panel Section:
  * Create a Section in admin panel called "Photo gallery"
  * Template: admin_gallery/gallery-content.php
  */
add_action('admin_menu', 'register_theme_gallery_menu'); //admin_page.php. add_menu = obligatory

function register_theme_gallery_menu()
{
    add_menu_page(
        __(\functions\LangHelper::translate('photo_gallery')),
        __(\functions\LangHelper::translate('photo_gallery')),
        'manage_options',
        'photo-gallery',
        'theme_gallery_menu_page',
        'dashicons-admin-media'
    );
}

function theme_gallery_menu_page()
{
    get_template_part('/admin_gallery/gallery', 'content');
}
