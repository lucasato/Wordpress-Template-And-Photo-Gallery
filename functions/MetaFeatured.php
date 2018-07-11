<?php
namespace functions;

class MetaFeatured
{
    private $tagMeta = 'meta-post-featured';

    public function __construct()
    {
        // OnSubmit run this
        add_action('save_post', array($this, 'create_featured_post'));

        // Add meta box
        add_action('add_meta_boxes', array($this, 'create_featured_html'));
    }

    public function create_featured_post($postId)
    {
        // Check revision status
        $is_autosave = wp_is_post_autosave($postId);
        $is_revision = wp_is_post_revision($postId);
        $is_valid_nonce = (isset($_POST[ 'sm_featured' ]) && wp_verify_nonce($_POST[ 'sm_featured' ], basename(__FILE__))) ? 'true' : 'false';

        // Exits script if its on revision
        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST[$this->tagMeta])) {
            $checkValue = 'true';
        } else {
            $checkValue = 'false';
        }
        update_post_meta($postId, $this->tagMeta, $checkValue);
    }


    public function create_featured_html()
    {
        add_meta_box('sm_featured', __(\functions\LangHelper::translate('meta_featured'), 'sm-textdomain'), array($this, 'sm_meta_callback'), 'post', 'side');
    }

    public function sm_meta_callback($post)
    {
        $meta = get_post_meta($post->ID); ?>
            <p>
                <div class="sm-row-content">
                    <label for="<?php echo $this->tagMeta; ?>">
                        <?php echo \functions\LangHelper::translate('meta_featured_placeholder'); ?>
                        <input type="checkbox" 
                            name="<?php echo $this->tagMeta; ?>"
                            id="<?php echo $this->tagMeta; ?>"
                            <?php 
                                if (isset($meta[$this->tagMeta]) && $meta[$this->tagMeta][0] == 'true') {
                                    echo 'checked';
                                } ?>
                        />
                    </label>
                </div>
            </p>
        <?php
    }
}
