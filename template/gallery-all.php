<section class="card-deck">
    <div class="row col-12 text-center">
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
                $image = array();
                if (has_post_thumbnail($postId)):
                  $image = wp_get_attachment_image_src(get_post_thumbnail_id($postId), 'small-thumbnail');
                endif;
                ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 card-container-fix">
                    <div class="card">
                        <a href="<?php echo get_permalink($postId);?>">
                            <img class="card-img-top" src="<?php echo $image[0];?>" alt="<?php echo the_title();?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php echo get_permalink($postId);?>">
                                    <?php echo the_title();?>
                                </a>
                            </h5>
                            <a href="<?php echo get_permalink($postId);?>" class="btn btn-primary">
                                <?php
                                    echo \functions\LangHelper::translate('gallery_btn_open');
                                ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            endwhile;
        else:
            echo '<p>'. \functions\LangHelper::translate('not_found') .'</p>';
        endif;
        ?>
    </div>
</section>
