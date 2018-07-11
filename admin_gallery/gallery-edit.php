<table class="wp-list-table widefat striped">
    <thead>
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
    </thead>
    <tbody>
        <?php
        $postId = $_GET['edit'];
        $post  = get_post($postId);
        if ($post) {
            ?>
                <tr>
                    <td>
                        <?php
                        $image = array();
            if (has_post_thumbnail($postId)):
                          $image = wp_get_attachment_image_src(get_post_thumbnail_id($postId), 'small-thumbnail');
            endif; ?>
                        <img class="img-fluid" id="featuredPhoto" src="<?php echo $image[0]; ?>" alt="...">
                    </td>
                    <td>
                        <a href="<?php echo $post->post_name; ?>" target="_blank" alt="f522" class="dashicons dashicons-controls-play"></a>
                        <input type="text" value="<?php echo $post->post_title; ?>" id="edit-gallery-post-name" />
                    </td>
                    <td>
                        <?php echo $post->post_date; ?>
                    </td>
                    <td>
                        <?php
                              $meta = get_post_meta($postId); ?>
                        <input type="text"
                            value="<?php echo isset($meta['meta-password-access'][0]) ? $meta['meta-password-access'][0] : ''; ?>"
                            id="edit-gallery-post-password" />
                    </td>
                    <td>[gallery id="<?php echo $postId; ?>"]</td>
                    <td>
                        <a href="Javascript:void(0);" class="button button-primary" onclick="updateGallery(<?php echo $postId; ?>);">
                            <?php echo \functions\LangHelper::translate('gallery_btn_update'); ?>
                        </a>
                        <a href="#TB_inline?width=300&height=200&inlineId=modal-window-delete"
                            onclick="deleteGallery(<?php echo $postId; ?>)"
                            class="button button-secondary thickbox">
                            <?php echo \functions\LangHelper::translate('gallery_btn_delete'); ?>
                        <a/>
                    </td>
                </tr>
            <?php
        }
        ?>
    </tbody>
  </table>

  <h2>
      <?php echo \functions\LangHelper::translate('gallery_title_add'); ?>
  </h2>
  <hr>
  <div class="custom_file_gallery_upload"></div>
  <script>
  jQuery(document).ready(function()
  {
      showUploader( <?php echo $postId;?> );
  });
  </script>

  <h2>
      <?php echo \functions\LangHelper::translate('gallery_title_loaded'); ?>
  </h2>
  <section id="galleryList" class="galleryList adminGalleryList">
    <?php
    //Get images
    $attachments = get_posts(array(
            'post_type'   => 'attachment',
            'numberposts' => -1,
            'post_status' => null,
            'post_parent' => $postId
        ));
    if ($attachments) {
        foreach ($attachments as $attachment) {
            $thumbnail_url = wp_get_attachment_image_src($attachment->ID, 'small-thumbnail', true);
            $thumbnail_url = $thumbnail_url[0]; ?>
              <article id="photo_<?php echo $attachment->ID; ?>">
                <img src="<?php echo $thumbnail_url; ?>" class="img-fluid" />
                <p>
                  <input
                      type="button"
                      onclick="featuredGalleryPhoto(<?php echo $postId; ?>,<?php echo $attachment->ID; ?>);"
                      class="button"
                      value="<?php echo \functions\LangHelper::translate('gallery_btn_featured'); ?>" />
                  <input
                      type="button"
                      onclick="deletePhoto(<?php echo $attachment->ID; ?>)"
                      class="button-primary delete"
                      value="<?php echo \functions\LangHelper::translate('gallery_btn_remove'); ?>" />
                </p>
              </article>
              <?php
        }
    }
    ?>
  </section>
