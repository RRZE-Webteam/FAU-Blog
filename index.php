<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

if (isset($_GET['format']) && $_GET['format'] == 'embedded') {
    get_template_part('template-parts/index', 'embedded');

    return;
}
if (is_active_sidebar('news-sidebar')) {
    fau_use_sidebar(TRUE);
}
get_header();

$posttype = get_post_type();
$screenreadertitle = '';
$herotype = get_theme_mod('advanced_header_template');
if (($posttype == 'post') && (is_archive())) {
    if (is_category()) {
        $screenreadertitle = single_cat_title("", FALSE);
    } else {
        $screenreadertitle = get_the_archive_title();
    }
} else {
    $screenreadertitle = __('Index', 'fau');
}

$titleforscreenreader = TRUE;
if (empty($herotype)) {
    $titleforscreenreader = TRUE;
    $herotype = 'default';
} elseif (($herotype == 'banner') || ($herotype == 'slider')) {
    $titleforscreenreader = FALSE;
}
?>

    <div id="content" class="herotype-<?php
    echo $herotype; ?>">
        <div class="content-container">
            <div class="post-row">
                <?php
                if (get_post_type() == 'post') { ?>
                <main class="entry-content">
                    <?php
                    } else { ?>
                    <main>
                        <?php
                        }

                        if ($titleforscreenreader) { ?>
                            <h1 id="maintop" class="mobiletitle"><?php
                                echo $screenreadertitle; ?></h1>
                        <?php
                        } else { ?>
                            <h1 id="maintop"><?php
                                echo $screenreadertitle; ?></h1>
                        <?php
                        }

                        if (get_theme_mod('fau_blog_blogroll_layout') == 'tiles') {
                            echo '<div class="blogroll">';
                        }

                        $line = 0;
                        while (have_posts()) {
                            the_post();

                            $line ++;
                            if ($posttype == 'person') {
                                echo FAU_Person_Shortcodes::fau_person(["id" => $post->ID, 'format' => 'kompakt', 'showlink' => 0, 'showlist' => 1]);
                            } elseif ($posttype == 'post') {
                                if (get_theme_mod('fau_blog_blogroll_layout') == 'tiles') {
                                    echo fau_blog_display_news_tiles($post->ID, TRUE);
                                } else {
                                    echo fau_display_news_teaser($post->ID, TRUE);
                                }
                            } else { ?>

                                <h2 class="small"><a href="<?php
                                    the_permalink(); ?>"><?php
                                        the_title(); ?></a></h2>

                                <?php
                                if (has_post_thumbnail($post->ID)) { ?>
                                    <div class="row">
                                    <div class="span3">
                                        <?php
                                        $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                                        echo fau_get_image_htmlcode($post_thumbnail_id);
                                        ?>
                                    </div>
                                    <div class="span5">
                                <?php
                                }
                                the_content();
                                if (has_post_thumbnail($post->ID)) { ?>
                                    </div>
                                    </div>
                                <?php
                                }
                            }
                        }

                        if (get_theme_mod('fau_blog_blogroll_layout') == 'tiles') {
                            echo '</div>';
                        }

                        if (($posttype == 'person') || ($posttype == 'post')) {
                            $next = get_next_posts_link(__('Ältere Beiträge', 'fau'));
                            $prev = get_previous_posts_link(__('Neuere Beiträge', 'fau'));

                            if ($next || $prev) {
                                echo '<nav class="index-navigation">';
                                if ($prev) {
                                    echo '<div class="prev">' . $prev . '</div>';
                                }
                                if ($next) {
                                    echo '<div class="next">' . $next . '</div>';
                                }
                                echo '</nav>';
                            }
                        }
                        ?>
                    </main>
                    <?php
                    if ($posttype == 'post') {
                        get_template_part('template-parts/sidebar', 'posts');
                    }
                    ?>
            </div>
        </div>

    </div>
<?php
get_footer(); 

