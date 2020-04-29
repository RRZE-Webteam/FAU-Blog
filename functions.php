<?php

require_once( get_stylesheet_directory() . '/customizer.php' );
require_once( get_stylesheet_directory() . '/widgets.php' );

/*
 * Blog Theme Setup: Overwrite some defaults
 */
add_action( 'after_setup_theme', 'fau_blog_setup', 11 );
function fau_blog_setup()
{
    global $default_link_liste;
    $default_link_liste['techmenu'] = [
        'link1' => [
            'name' => __('Nutzungsbedingungen', 'fau'),
            'content' => 'https://blogs.fau.de/nutzungsbedingungen/',
        ],
        'link2' => [
            'name' => __('Hilfe', 'fau'),
            'content' => 'https://blogs.fau.de/hilfe',
        ],
        'link3' => [
            'name' => __('Blogübersicht', 'fau'),
            'content' => 'https://blogs.fau.de/?a=bloguebersicht',
        ],
    ];

    global $defaultoptions;
    $defaultoptions['default_rwdimage_src'] = get_stylesheet_directory_uri() . '/img/logo-blogdienst_thumb-480x320.png';
    $defaultoptions['default_rwdimage_2-1_src'] = get_stylesheet_directory_uri() . '/img/logo-blogdienst_thumb-480x240.png';
    global $options;
    $options['default_rwdimage_src'] = get_stylesheet_directory_uri() . '/img/logo-blogdienst_thumb-480x320.png';
    $options['default_rwdimage_2-1_src'] = get_stylesheet_directory_uri() . '/img/logo-blogdienst_thumb-480x240.png';

    unregister_default_headers(['fau', 'med', 'nat', 'phil', 'rw', 'tf', 'fb-wiso', 'fb-jura']);
    $default_header_logos = [
        'blog' => [
            'url'           => get_stylesheet_directory_uri() .'/img/logo-blogdienst.svg',
            'thumbnail_url' => get_stylesheet_directory_uri() .'/img/logo-blogdienst.svg',
            'description'   => 'Blogdienst der Universität Erlangen Nürnberg'
            ]
    ];
    register_default_headers( $default_header_logos );
}

/*
 * Enqueue Styles
 */
add_action( 'wp_enqueue_scripts', 'fau_blog_enqueue_styles' );
function fau_blog_enqueue_styles() {
    $theme_data = wp_get_theme();
    $theme_version = $theme_data->Version;
    $parent_style = 'fau-style-css';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'fau-blog-style-css',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        $theme_version
    );
    wp_enqueue_script( 'fau-blog-scripts',
        get_stylesheet_directory_uri() . '/js/scripts.min.js',
        array('jquery'),
        $theme_version,
        true );

}

/*
 * Remove Unnecessary Page Templates
 */
add_filter( 'theme_page_templates', 'fau_blog_remove_page_templates' );
function fau_blog_remove_page_templates( $templates ) {
    unset( $templates['page-templates/page-portal.php'] );
    unset( $templates['page-templates/page-portalindex.php'] );
    unset( $templates['page-templates/page-start.php'] );
    unset( $templates['page-templates/page-subnav.php'] );
    return $templates;
}

/*
 * Add Body Classes
 */
add_filter( 'body_class', 'fau_blog_body_class' );
function fau_blog_body_class( $classes ) {
    $fau_blog_color_scheme = get_theme_mod('fau_blog_color_scheme');
    switch ($fau_blog_color_scheme) {
        case 'phil':
            $classes[] = 'color-phil';
            break;
        case 'rw':
            $classes[] = 'color-rw';
            break;
        case 'med':
            $classes[] = 'color-med';
            break;
        case 'nat':
            $classes[] = 'color-nat';
            break;
        case 'tf':
            $classes[] = 'color-tf';
            break;
        case 'fau':
        default:
            $classes[] = 'color-fau';
            break;
    }
    if (get_theme_mod('fau_blog_header_watermark') == 0) {
        $classes[] = 'no-watermark';
    }
    if (get_theme_mod('fau_blog_blogroll_layout') == 'tiles') {
        $classes[] = 'blogroll-tiles';
    }
        return $classes;
}

/*
 * Header-Links: FAU und Blogdienst fix
 * ersetzt fau_get_toplinks() aus FAU-Einrichtungen
 */
function fau_blog_get_toplinks($args = array()) {
    global $default_link_liste;

    $uselist =  $default_link_liste['meta'];
    $result = '';

    $thislist = "";

    if ( has_nav_menu( 'meta' ) ) {
        // wp_nav_menu( array( 'theme_location' => 'meta', 'container' => false, 'items_wrap' => '<ul id="meta-nav" class="%2$s">%3$s</ul>' ) );

        $menu_name = 'meta';

        if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
            $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
            $menu_items = wp_get_nav_menu_items($menu->term_id);
            foreach ( (array) $menu_items as $key => $menu_item ) {
                $title = $menu_item->title;
                $url = $menu_item->url;
                $class_names = '';
                //   $classes[] = 'menu-item';
                //   $classes = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
                //   $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ) ) );
                //    $class_names = ' class="' . esc_attr( $class_names ) . '"';
                $thislist .= '<li'.$class_names.'><a data-wpel-link="internal" href="' . $url . '">' . $title . '</a></li>';
            }
        }

    } else {
        foreach($uselist as $key => $entry ) {
            if (substr($key,0,4) != 'link') {
                continue;
            }
            $thislist .= '<li';
            if (isset($entry['class'])) {
                $thislist .= ' class="'.$entry['class'].'"';
            }
            $thislist .= '>';
            if (isset($entry['content'])) {
                $thislist .= '<a data-wpel-link="internal" href="'.$entry['content'].'">';
            }
            $thislist .= $entry['name'];
            if (isset($entry['content'])) {
                $thislist .= '</a>';
            }
            $thislist .= "</li>\n";
        }
    }

    global $default_fau_orga_data;
    $charset = fau_get_language_main();
    $homeorga = 'fau';
    $hometitle = $default_fau_orga_data[$homeorga]['title'];
    if (isset($default_fau_orga_data[$homeorga]['homeurl_'.$charset])) {
        $homeurl = $default_fau_orga_data[$homeorga]['homeurl_'.$charset];
    } else {
        $homeurl = $default_fau_orga_data[$homeorga]['homeurl'];
    }
    $linkimg = $default_fau_orga_data[$homeorga]['home_imgsrc'];
    if (isset($default_fau_orga_data[$homeorga]['data-imgmobile'])) {
        $linkdataset = $default_fau_orga_data[$homeorga]['data-imgmobile'];
    }
    $result .= '<ul class="orgalist">'
        . '<li class="fauhome">'
        . '<a href="'.$homeurl.'">'
        . '<img src="'.fau_esc_url($linkimg).'" alt="'.esc_attr($hometitle).'"'
        . ' data-imgmobile="'.fau_esc_url($linkdataset).'"'
        . '>'
        . '</a>'
        . '</li>'."\n"
        . '<li><a href="https://blogs.fau.de">' . __('FAU Blogs', 'fau') . '</a></li>'
        . '</ul>';

    if (isset($thislist)) {
        if (is_array($args) && isset($args['title'])) {
            $html = 'h3';
            if (isset($args['titletag'])) {
                $html = $args['titletag'];
            }
            $html = esc_attr($html);

            $result .= '<'.$html.'>'.esc_attr($args['title']).'</'.$html.'>';
        }

        $result .= '<ul class="meta-nav menu">';
        $result .= $thislist;
        $result .= '</ul>';
        $result .= "\n";
    }
    return $result;
}

function fau_blog_display_news_tiles($id = 0, $withdate = false, $hstart = 2, $hidemeta = false) {
    if ($id == 0) return;
    $post = get_post($id);
    $output = '';
    $hidemeta = (get_theme_mod('post_display_category_below') == 1 ? false : true);
    $link = get_permalink($id);

    $output .= '<article class="news-item" itemscope itemtype="http://schema.org/NewsArticle">';

    $show_thumbs = get_theme_mod('default_postthumb_always');

    if ((has_post_thumbnail( $post->ID )) || ($show_thumbs==true))  {

        $output .= '<div class="thumbnailregion">';
        $output .= '<div aria-hidden="true" role="presentation" class="passpartout" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
        $output .= '<a href="'.$link.'" tabindex="-1" class="news-image">';

        $size =  'rwd-480-3-2';
        $post_thumbnail_id = get_post_thumbnail_id( $post->ID);
        $pretitle = get_theme_mod('advanced_blogroll_thumblink_alt_pretitle');
        $posttitle = get_theme_mod('advanced_blogroll_thumblink_alt_posttitle');
        $alttext = $pretitle.get_the_title($post->ID).$posttitle;
        $alttext = esc_html($alttext);

        $imagehtml = fau_get_image_htmlcode($post_thumbnail_id,'rwd-480-3-2',$alttext,'',array('itemprop' => 'thumbnailUrl'));
        if (fau_empty($imagehtml)) {
            $imagehtml = fau_get_image_fallback_htmlcode('post-thumb',$alttext,'',array('itemprop' => 'thumbnailUrl'));
        }

        $output .= $imagehtml;
        $output .= '</a>';
        $imgmeta = wp_get_attachment_image_src($post_thumbnail_id, 'rwd-480-3-2');
        if ($imgmeta) {
            $output .= '<meta itemprop="url" content="'.fau_make_absolute_url($imgmeta[0]).'">';
        }
        global $defaultoptions;
        $output .= '<meta itemprop="width" content="'.$defaultoptions['default_rwdimage_width'].'">';
        $output .= '<meta itemprop="height" content="'.$defaultoptions['default_rwdimage_height'].'">';
        $output .= '</div>';
        $output .= '</div>';
        //$output .= '<div class="teaserregion">';
    }

    if ($withdate == true || $hidemeta == false) {
        $output .= '<div class="news-meta">';
        if ($withdate) {
            $output .= '<span class="news-meta-date" itemprop="datePublished" content="'. esc_attr( get_post_time('c') ).'"> '.get_the_date('',$post->ID)."</span>";
        } else {
            $output .= '<meta itemprop="datePublished" content="'. esc_attr( get_post_time('c') ).'">';
        }
        if ($withdate == true && $hidemeta == false) {
            $output .= ' | ';
        }
        if ($hidemeta == false) {
            $categories = get_the_category($id);
            $separator = ', ';
            $thiscatstr = '';
            if ($categories) {
                $output .= '<span class="news-meta-categories"> ';
                foreach($categories as $category) {
                    $thiscatstr .= '<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
                }
                $output .= trim($thiscatstr, $separator);
                $output .= '</span> ';
            }
        }
        $output .= '</div>';
    }

    $output .= '<h1 itemprop="headline">';
    $output .= '<a itemprop="url" ';

    $output .= 'href="'. $link .'">'.get_the_title($id).'</a>';
    $output .= "</h1>";

    $output .= '<div>';
    $output .= '<p itemprop="description">';
    $cuttet = false;
    $abstract = get_post_meta( $post->ID, 'abstract', true );
    if (strlen(trim($abstract))<3) {
        $abstract =  fau_custom_excerpt($post->ID,get_theme_mod('default_anleser_excerpt_length'),false,'',true);
    }
    $output .= $abstract;
    $output .= fau_create_readmore($link,get_the_title($post->ID),false,true);
    $output .= '</p>';
    $output .= '</div>';

    $output .= '</article>';

    return $output;

}