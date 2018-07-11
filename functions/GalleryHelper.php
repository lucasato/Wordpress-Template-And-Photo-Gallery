<?php
namespace functions;

class GalleryHelper
{
    private static $postData;
    private static $designMode;

    public static function show($galleryId, $designMode = 'square', $date = false, $share = false)
    {
        self::$postData = get_post($galleryId);
        self::$designMode = $designMode;

        if (self::$postData) {
            if (self::viewAccess($galleryId)) {
                $attachments = self::getAttachment($galleryId);
                // show html
                self::getTitle();
                if ($date || $share) {
                    self::getTitleBottomBar($date, $share);
                }
                self::getImageList($attachments);
            } else {
                get_template_part('/template/form', 'password');
            }
        } else {
            // message not found
            echo '<p>'. \functions\LangHelper::translate('not_found') .'</p>';
        }
    }

    private static function getTitle()
    {

        $title = self::$postData->post_title;
        echo "<header><h2>$title</h2></header>";
    }

    private static function getTitleBottomBar($date, $share)
    {
        ?>
        <hr>
        <address class="row">
            <div class="col-lg-6 col-md-6 col-xs-12">
              <?php
                  if ($share) {
                      echo \functions\HtmlHelper::share();
                  }
              ?>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 author text-right">
              <?php
                  if ($date) {
                      echo self::$postData->post_date;
                  }
              ?>
            </div>
        </address>
        <?php
    }

    private static function getImageList($attachments)
    {
        $imgClass = 'img-fluid';
        $thumbnailType = 'small-thumbnail';
        switch (self::$designMode) {
            case 'squared':
                $classMode = 'galleryList';
                $thumbnailType = 'small-thumbnail';
                break;
            case 'column':
            default:
                $classMode = 'galleryColumn';
                $imgClass = null;
                $thumbnailType = 'medium-thumbnail';
                break;
        }
        ?>
        <ul class="<?php echo $classMode;?> easyGallery">
            <?php
            foreach ($attachments as $attachment) {
                $thumbnail_url = wp_get_attachment_image_src($attachment->ID, $thumbnailType, true);
                $thumbnail_url = $thumbnail_url[0];

                $thumbnail_url_web = wp_get_attachment_image_src($attachment->ID, 'full', true);
                $thumbnail_url_web = $thumbnail_url_web[0];

                $image_alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
            ?>
                <li>
                    <a href="<?php echo $thumbnail_url_web; ?>" title="<?php echo $image_alt; ?>">
                        <img src="<?php echo $thumbnail_url; ?>" alt="<?php echo $image_alt; ?>" class="<?php echo $imgClass;?>" />
                    </a>
                </li>
            <?php
            }
            ?>
        </ul>
        <?php
    }

    private static function getAttachment($galleryId)
    {
        return get_posts([
            'post_type'   => 'attachment',
            'numberposts' => -1,
            'post_status' => null,
            'post_parent' => $galleryId
        ]);
    }

    /**
      * function must return true if user have permissions to see the gallery
      * @param int $galleryId: gallery post_id
      * @return bool
      */
    private static function viewAccess($galleryId)
    {
        $meta = get_post_meta($galleryId);
        if (isset($meta['meta-password-access'][0]) && !empty(trim($meta['meta-password-access'][0]))) {
            if (isset($_GET['password']) && trim($_GET['password'])  ==  trim($meta['meta-password-access'][0])) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
