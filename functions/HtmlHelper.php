<?php
namespace functions;

class HtmlHelper
{
    public static function getTitleArchive()
    {
        if (is_category()) {
            return single_cat_title("", false);
        } elseif (is_tag()) {
            return single_tag_title("", false);
        } elseif (is_author()) {
            the_post();
            return LangHelper::translate('articles_by', get_the_author());
        } elseif (is_day()) {
            return LangHelper::translate('posts_from', get_the_date());
        } elseif (is_month()) {
            return LangHelper::translate('posts_from', get_the_date('F'));
        } elseif (is_year()) {
            return LangHelper::translate('posts_from', get_the_date('Y'));
        } else {
            return LangHelper::translate('archives');
        }
    }

    public static function posts()
    {
        if (have_posts()) {
            while (have_posts()) : the_post(); ?>
            <div class="card">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('post-thumbnail', ['class' => 'card-img-top img-fluid']); ?>
                </a>
                <div class="card-body">
                    <h4 class="card-title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h4>
                    <p class="card-text">
                        <?php
                            echo get_the_excerpt(); ?>
                    </p>
                </div>
                <div class="card-footer">
                <small class="text-muted">
                    <?php the_time('F j, Y g:i a'); ?> |
                    <?php $categories = get_the_category();
                    $cat_list = '';
                    foreach ($categories as $cat):
                        $cat_list .=
                            '<a href="'.get_category_link($cat->term_id).'">'.
                                $cat->cat_name
                            .'</a>, ';
                    endforeach;
                    echo $cat_list;
                    ?>
                    <a href="<?php the_permalink(); ?>" class="btn btn-read-more" role="button">
                        <?php echo \functions\LangHelper::translate('read_more'); ?>
                    </a>
                </small>
                </div>
            </div>

            <?php
            endwhile;
        } else {
            echo '<p class="col-lg-12 text-justify">'. \functions\LangHelper::translate('not_found') .'</p>';
        }
    }

    public static function featuredPosts($limit = 3)
    {
        $args = [
            'posts_per_page' => $limit,
            'meta_query' => [
                array(
                    'key'     => 'meta-post-featured',
                    'value'   => 'true',
                    'compare' => '=',
                )
            ]
        ];
        $query = new \WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post(); ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 card-container-fix">
                <div class="card">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('post-thumbnail', ['class' => 'card-img-top img-fluid']); ?>
                    </a>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h4>
                        <p class="card-text">
                            <?php
                                echo get_the_excerpt(); ?>
                        </p>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">
                            <?php the_time('F j, Y g:i a'); ?> |
                            <?php $categories = get_the_category();
                            $cat_list = '';
                            foreach ($categories as $cat) {
                                    $cat_list .=
                                        '<a href="'.get_category_link($cat->term_id).'">'.
                                            $cat->cat_name
                                        .'</a>, ';
                            }
                            echo $cat_list;
                            ?>
                            <a href="<?php the_permalink(); ?>" class="btn btn-read-more" role="button">
                                <?php echo \functions\LangHelper::translate('read_more'); ?>
                            </a>
                        </small>
                    </div>
                </div>
            </div>
            <?php
            endwhile;
        } else {
            echo '<p class="col-lg-12 text-justify">'. \functions\LangHelper::translate('not_found') .'</p>';
        }
    }

    public static function share($socialMedia = ['twitter', 'facebook', 'google','linkedin'])
    {
        $iconsPath = get_template_directory_uri().'/images/social/';
        $socialShareUrl = [
            'twitter'   => 'https://twitter.com/share?url=',
            'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=',
            'linkedin'  => 'https://www.linkedin.com/shareArticle?url=',
            'google'    => 'https://plus.google.com/share?url=',
        ];

        global $wp;
        $url = home_url($wp->request);
        $html = '<nav class="share-object">';

        foreach ($socialMedia as $social) {
            $html .= "<a href='{$socialShareUrl[$social]}{$url}'><i class='fa-$social'></i></a>";
        }
        $html .= '</nav>';
        return $html;
    }

    public static function author()
    {
        $text = \functions\LangHelper::translate('wrote_by', get_the_author());
        $link = get_author_posts_url(get_the_author_meta('ID'));
        return "<a href='$link' rel='author'>" . $text . "</a>";
    }

    public static function commentsTotal()
    {
        $comments = get_comments_number(get_the_ID());
        $text = \functions\LangHelper::translate('comments', $comments);
        return "<a href='#comments'><i class='fa-comments'></i> $text</a>";
    }

    public static function searchBar()
    {
        $text = \functions\LangHelper::translate('search');
        $value = isset($_GET['s']) ? $_GET['s'] : null; ?>
            <form role="search" method="get" class="search-form" action="<?php echo get_site_url();?>">
                <div class="form-group row text-right">
                        <label for="s" class="col-form-label">
                            <?php echo $text; ?>:
                        </label>
                        <input type="text" class="form-control" name="s" value="<?php echo $value; ?>">

                        <input type="submit" class="btn btn-primary" id="searchsubmit" value="<?php echo $text; ?>">
                </div>
            </form>
        <?php
    }

    public static function comments($postId)
    {
        self::getCommentsTemplate([
            'post_id' => $postId,
            'status'  => 'approve',
            'parent'  => 0
        ],
        '/template/comments');
        comment_form();
    }

    public static function getCommentsTemplate($args, $template)
    {
        $comments = get_comments($args);
        if ($comments) {
            set_query_var('comments', $comments);
            get_template_part($template);
        }
    }

    public static function commentAvatar($comment)
    {
        $currentUser = wp_get_current_user();
        $avatarUrl = null;
        $userName = $comment->comment_author;
        if (isset($currentUser->user_email)) {
            $avatarUrl = get_avatar_url($currentUser->user_email);
        }
        ?>
        <a href="">
            <img class="mx-auto rounded-circle img-fluid" src="<?php echo $avatarUrl;?>" alt="avatar">
        </a>
        <?php
    }

    public static function commentAuthor($comment)
    {
        $userName = $comment->comment_author;
        echo "<a href='#'>$userName</a>";
    }

    public static function breadcrumb() {
        $html = '<nav aria-label="breadcrumb">';
    		$html .= '<ol class="breadcrumb">';
  		  $html .= '<li class="breadcrumb-item"><a href="'. get_option('home') . '">Home</a></li>';
        if (is_category() || is_single()) {
          	$html .= '<li class="breadcrumb-item">'. get_the_category_list('</li><li>').'</li>';
      	} else if (is_tag()) {
            $html .= single_tag_title('', false);
        } else if (is_day()) {
            $html .= "<li class='breadcrumb-item'>Archive for ". the_time('F jS, Y'); echo'</li>';
      	} else if (is_month()) {
            $html .= "<li class='breadcrumb-item'>Archive for ". the_time('F, Y'); echo'</li>';
      	} else if (is_year()) {
            $html .= "<li class='breadcrumb-item'>Archive for ". the_time('Y'); echo'</li>';
      	} else if (is_author()) {
            $html .= "<li class='breadcrumb-item'>Author Archive</li>";
      	} else if (isset($_GET['paged']) && !empty($_GET['paged'])) {
            $html .=  "<li class='breadcrumb-item'>Blog Archives</li>";
      	} else if (is_search()) {
            $html .= "<li class='breadcrumb-item'>Search Results</li>";
        }
      	$html .= '</ol></nav>';
        return $html;
    }

    public static function gallery($galleryId, $type, $date = false, $share = false)
    {
        ?>
            <div class="row">
              <section class="container">
                  <?php
                      $gallery = new \functions\GalleryHelper();
                      $gallery->show($galleryId, $type, $date, $share);
                  ?>
              </section>
            </div>
        <?php
    }
}
