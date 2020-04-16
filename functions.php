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