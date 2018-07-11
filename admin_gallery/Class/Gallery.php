<?php
class Gallery
{
    private $filePath = '';
    private $upload;

    /**
      * Upload File to server
      */
    public function upload($postId)
    {
        if (!is_numeric($postId)) {
            Response::json(['status' => false, 'msg' => \functions\LangHelper::translate('gallery_error_upload')]);
        }

        $this->upload = new FileUpload();
        if ($this->upload->upload()) {

          // Now i'll create media attachment post
            $save = $this->save($postId);
            if (count($save)>0) {
                Response::json(
                  [
                  'file'          =>  $save['file'],
                  'id_post'       =>  $postId,
                  'id_attachment' =>  $save['id_attachment'],
                  'thumbnail'     =>  $save['thumbnail'],
                  'status'        =>  true
                ]
              );
                exit;
            } else {
                Response::json(['status' => false, 'msg' => \functions\LangHelper::translate('gallery_error')]);
            }
        } else {
            Response::json(['status' => false, 'msg' => \functions\LangHelper::translate('gallery_error')]);
        }
        exit;
    }

    public function delete($postId)
    {
        if (wp_delete_post($postId, true)) {
            Response::json(['status' => true]);
        } else {
            Response::json(['status' => false, 'msg' => \functions\LangHelper::translate('gallery_error')]);
        }
        exit;
    }

    public function update($postId)
    {
        if (is_numeric($postId)) {

            $name = $_POST['galleryName'];
            $password = $_POST['galleryPassword'];

            $update = array(
                'ID'           => $postId,
                'post_title'   => $name,
            );

            // Update the post into the database
            if (wp_update_post($update, true)) {
                update_post_meta($postId, 'meta-password-access', $password);
                Response::json(['status' => true]);
            } else {
                Response::json(['status' => false, 'msg' => \functions\LangHelper::translate('gallery_error')]);
            }
        }
        exit;
    }

    public function create($galleryName, $galleryPassword)
    {
        $postTitle   = wp_strip_all_tags($galleryName);

        // Unique post name
        if (post_exists($postTitle)) {
            $postTitle   = wp_strip_all_tags($galleryName).'_'.substr(sha1(time()), 0, 5);
        }

        // Create post object
        $myPost = array(
          'post_title'    =>  $postTitle,
          'post_content'  =>  '',
          'post_type'     =>  'gallery',
          'post_status'   =>  'publish',
        );

        // Insert the post into the database
        $postId = wp_insert_post($myPost);
        if (is_numeric($postId)) {
            // Save Password Value
            update_post_meta($postId, 'meta-password-access', $galleryPassword);
            Response::json([
              'id_post' =>  $postId,
              'redirect'=>  get_home_url().'/wp-admin/admin.php?page=photo-gallery&edit='.$postId,
              'status'  =>  true
            ]);
        } else {
            Response::json(['status' => false, 'msg' => \functions\LangHelper::translate('gallery_error')]);
        }
        exit;
    }

    public function featured($postId, $attachmentId)
    {
        if (is_numeric($postId) && set_post_thumbnail($postId, $attachmentId)) {
            Response::json(['status' => true]);
        } else {
            Response::json(['status' => false, 'msg' => \functions\LangHelper::translate('gallery_error')]);
        }
        exit;
    }

    /**
      * Save uploaded file in database
      */
    private function save($postId = false, $returnThumbnail = 'small-thumbnail')
    {
        $ext = $this->upload->getExtension();
        if ($ext  ==  'jpg' || $ext  ==  'jpeg' || $ext  ==  'gif' || $ext  ==  'png') {
            if (!is_numeric($postId)) {
                return false;
            }
            // The ID of the post this attachment is for.
            $parentPostId = $postId;

            // Check the type of file. We'll use this as the 'post_mime_type'.
            $filetype = wp_check_filetype(basename($this->upload->getFileUrl()), null);

            //Set path file
            $this->filePath = $this->upload->getFullPath();
            if (strlen($this->filePath) > 5) {
                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();

                // Prepare an array of post data for the attachment.
                $attachment = array(
                    'guid'           => $this->upload->getFileUrl(),
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($this->upload->getFileUrl())),
                    'post_content'   => '',
                    'post_status'    => 'inherit',
                );
                try {
                    // Insert the attachment.
                    $attachId = wp_insert_attachment($attachment, $this->filePath, $parentPostId);

                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    // Generate the metadata for the attachment, and update the database record.
                    $attach_data = wp_generate_attachment_metadata($attachId, $this->filePath);
                    wp_update_attachment_metadata($attachId, $attach_data);

                    set_post_thumbnail($parentPostId, $attachId);
                    //Get thumbnail
                    $thumbnailUrl = wp_get_attachment_image_src($attachId, $returnThumbnail, true);
                    $thumbnailUrl = $thumbnailUrl[0];

                    return array(
                        'thumbnail'     =>  $thumbnailUrl,
                        'id_post'       =>  $parentPostId,
                        'file'          =>  $this->filePath,
                        'id_attachment' =>  $attachId
                    );
                } catch (Exception  $e) {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
