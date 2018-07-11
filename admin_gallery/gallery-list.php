<table class="wp-list-table widefat striped">
    <thead>
        <tr>
          <th>
              <?php echo \functions\LangHelper::translate('gallery_th_featured');?>
          </th>
          <th>
              <?php echo \functions\LangHelper::translate('gallery_th_name');?>
          </th>
          <th>
              <?php echo \functions\LangHelper::translate('gallery_th_date');?>
          </th>
          <th>
              <?php echo \functions\LangHelper::translate('gallery_th_password');?>
          </th>
          <th>
              <?php echo \functions\LangHelper::translate('gallery_th_shortcode');?>
          </th>
          <th>
              <?php echo \functions\LangHelper::translate('gallery_th_action');?>
          </th>
        <tr>
    </thead>
    <tbody>
        <?php
            $paged = isset($_GET['paged'])?$_GET['paged']:1;
            $wp_order = 'DESC';
            $args = array(
                'posts_per_page'  =>  6,
                'paged'           =>  $paged,
                'post_type'       =>  'gallery'
            );
            $postslist = new WP_Query($args);

            if ($postslist->have_posts()) :
            while ($postslist->have_posts()) : $postslist->the_post();
                $postId  = get_the_ID();
            ?>
                <tr id="gallery_row_<?php echo $postId;?> ">
                    <td>
                        <?php
                            $image = array();
                            if (has_post_thumbnail($postId)):
                                $image = wp_get_attachment_image_src(get_post_thumbnail_id($postId), 'small-thumbnail');
                            endif;
                        ?>
                        <img class="img-fluid" src="<?php echo $image[0];?>" alt="...">
                    </td>
                    <td>
                        <?php echo the_title();?>
                    </td>
                    <td>
                        <?php echo the_date();?>
                    </td>
                    <td>
                        <?php
                            $meta = get_post_meta($postId);
                            echo isset($meta['meta-password-access'][0])?$meta['meta-password-access'][0]:'';
                        ?>
                    </td>
                    <td>
                        [gallery id="<?php echo $postId;?>"]</br>
                        <strong> (squared | column) Default: squared</strong>
                    </td>
                    <td>
                        <a href="admin.php?page=photo-gallery&edit=<?php echo $postId;?>" class="button">
                            Edit Gallery
                        </a>
                        <a href="#TB_inline?width=300&height=200&inlineId=modal-window-delete"
                            onclick="deleteGallery(<?php echo $postId;?>)"
                            class="button button-primary thickbox">
                            <?php echo \functions\LangHelper::translate('gallery_btn_delete');?>
                        <a/>
                    </td>
                </tr>
            <?php
            endwhile;
        endif;
        ?>
    </tbody>
</table>

<?php
/* Functional pagination have finished! */
if ($postslist->have_posts()) :
    $max_pages = $postslist->max_num_pages;
    $nextpage = $paged + 1;
    $prevpage = max(($paged - 1), 0); //max() will discard any negative value
    if ($prevpage !== 0) {
        echo '<a href="admin.php?page=photo-gallery&paged='. $prevpage .'">Anterior</a>';
    }
    echo ' | ';
    if ($max_pages > $paged) {
        echo '<a href="admin.php?page=photo-gallery&paged='. $nextpage .'">Siguiente</a>';
    }
endif;

/* Functional pagination have finished! */
wp_reset_postdata();

$pagination = get_the_posts_pagination(array(
        'mid_size' => 2,
        'prev_text' => __('Newer', 'textdomain'),
        'next_text' => __('Older', 'textdomain'),
));
echo $pagination;
