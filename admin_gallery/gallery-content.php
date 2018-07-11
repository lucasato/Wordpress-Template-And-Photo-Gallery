<div class="wrap">
      <h1 class="wp-heading-inline">
        <?php
          echo \functions\LangHelper::translate('photo_gallery');
        ?>
        <input type="button" id="btn_new_gallery" class="button" data-id-modal="my-dialog" value="<?php echo \functions\LangHelper::translate('gallery_new');?>" />
        <script>
        // Js translations
        globalGalleryTranslate = {
            'gallery_new': '<?php echo \functions\LangHelper::translate('gallery_new_box');?>',
            'gallery_validate_name': '<?php echo \functions\LangHelper::translate('gallery_validate_name');?>',
            'gallery_delete_sure': '<?php echo \functions\LangHelper::translate('gallery_delete_sure');?>',
            'gallery_delete_confirm': '<?php echo \functions\LangHelper::translate('gallery_delete_confirm');?>',
            'gallery_upload_title': '<?php echo \functions\LangHelper::translate('gallery_upload_title');?>',
            'gallery_btn_featured': '<?php echo \functions\LangHelper::translate('gallery_btn_featured');?>',
            'gallery_btn_remove': '<?php echo \functions\LangHelper::translate('gallery_btn_remove');?>',

        };
        </script>
      </h1>

<?php
//Delete gallery & Files
if (isset($_GET['delete'])) {
    $id_delete  = is_numeric($_GET['delete'])?$_GET['delete']:false;
    if (!$id_delete):
    __('Error, id is not a number'); else:
    wp_delete_post($id_delete, true);
    $attachments = get_posts(array(
            'post_type'   => 'attachment',
            'numberposts' => -1,
            'post_status' => null,
            'post_parent' => $id_delete
        ));
    if ($attachments) {
        foreach ($attachments as $attachment) {
            wp_delete_post($attachment->ID, true);
        }
    }
    endif;
}

if (!isset($_GET['edit'])) {
    require "gallery-list.php";
} else {
    require "gallery-edit.php";
}
?>

<!-- MODAL BOX -->
<div id="modal-window-delete" style="display:none;">

</div>
<!-- MODAL BOX -->

<div id="my-dialog" class="hidden" style="max-width:600px">
    <section class="atp_meta_options">
      <div class="atp_options_box ">
          <div class="option-row">
              <div class="atp_label">
                  <label><?php echo \functions\LangHelper::translate('gallery_th_name');?>:</label>
              </div>
          </div>
          <div class="atp_inputs">
              <input type="hidden" id="post-id" value="" />
              <input type="text" id="galleryName" length="100" placeholder="" />
          </div>
      </div>

      <div class="atp_options_box ">
          <div class="option-row">
              <div class="atp_label">
                  <label>
                      <?php echo \functions\LangHelper::translate('gallery_th_password');?>:
                  </label>
              </div>
          </div>
          <div class="atp_inputs">
              <input type="text" id="galleryPassword" length="100" placeholder="" />
          </div>
      </div>
      <hr>
      <p id="galleryAnswer"></p>
      <div class="atp_options_box ">
          <div class="option-row">
              <div class="atp_label">
              </div>
          </div>
          <div class="atp_inputs">
              <input type="button" class="button" onclick="createGallery();" value="<?php echo \functions\LangHelper::translate('gallery_create');?>" />
              <div class="clear"></div>
          </div>
      </div>

    </section>
</div>

</section>

</section>
</div>
