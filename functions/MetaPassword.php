<?php
namespace functions;

class MetaPassword
{
    public function __construct()
    {
        //New Meta value por password protection
        add_action('save_post', array($this, 'create_password_protection'));

        //Add meta html
        add_action('add_meta_boxes', array($this, 'create_password_protection_html'));
    }

    public function create_password_protection($post_id)
    {
        // Check revision status
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST[ 'sm_nonce' ]) && wp_verify_nonce($_POST[ 'sm_nonce' ], basename(__FILE__))) ? 'true' : 'false';

        // Exits script if its on revision
        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST[ 'lucasato-set-password-access' ])) {
            update_post_meta($post_id, 'meta-password-access', $_POST[ 'lucasato-set-password-access']);
        }
    }


    public function create_password_protection_html()
    {
        add_meta_box('sm_meta', __(\functions\LangHelper::translate('meta_password_lock'), 'sm-textdomain'), array($this, 'sm_meta_callback'), 'post', 'side');
    }

    /**
      * Lock access with password to a post: inactive in this template.
      */
    public function sm_meta_callback($post)
    {
        $meta = get_post_meta($post->ID); ?>
            <p>
                <div class="sm-row-content">
                    <label for="meta-checkbox">
                        <input type="text"
                            length="50"
                            name="lucasato-set-password-access"
                            id="lucasato-set-password-access"
                            placeholder="<?php echo \functions\LangHelper::translate('meta_password_placeholder'); ?>"
                            value="<?php echo isset($meta['meta-password-access']) ? $meta['meta-password-access'][0] : ''; ?>"
                        />
                        <?php _e(\functions\LangHelper::translate('meta_password_message'), 'sm-textdomain'); ?>
                    </label>
                </div>
            </p>
        <?php
    }
}
