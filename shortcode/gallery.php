<?php
// Add shortcode template & register post_type
add_shortcode('gallery', 'getGallery');
register_post_type('gallery', array('public' => true));

function getGallery($attr = array())
{
    $type = isset($attr['type']) ? $attr['type'] : 'squared';
    $id = isset($attr['id']) ? $attr['id'] : null;
    $share = isset($attr['share']) ? filter_var($attr['share'], FILTER_VALIDATE_BOOLEAN) : false;
    $date = isset($attr['date']) ? filter_var($attr['date'], FILTER_VALIDATE_BOOLEAN) : false;

    switch ($type) {
        case 'squared':
            \functions\HtmlHelper::gallery($id, $type, $date, $share);
            break;
        case 'column':
            \functions\HtmlHelper::gallery($id, $type, $date, $share);
            break;
        case 'single-page':
            \functions\HtmlHelper::gallery($id, $type, $date, $share);
            break;
        case 'all-galleries':
            get_template_part('/template/gallery', 'all');
            break;
        default:
            \functions\HtmlHelper::gallery($id, $type, $date, $share);
            break;
    }
}
