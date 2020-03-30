<?php

require_once( get_stylesheet_directory() . '/customizer.php' );
require_once( get_stylesheet_directory() . '/widgets.php' );
/*
 * Enqueue Styles
 */
add_action( 'wp_enqueue_scripts', 'fau_blog_enqueue_styles' );
function fau_blog_enqueue_styles() {
    $parent_style = 'fau-style-css';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'fau-blog-style-css',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

/*
 * Remove Unnecessary Page Templates
 */
add_filter( 'theme_page_templates', 'fau_blog_remove_page_templates' );
function fau_blog_remove_page_templates( $templates ) {
    unset( $templates['page-templates/page-portal.php'] );
    unset( $templates['page-templates/page-portalindex.php'] );
    unset( $templates['page-templates/page-start.php'] );
    return $templates;
}

/*
 * Add Body Classes
 */
add_filter( 'body_class', 'fau_blog_body_class' );
function fau_blog_body_class( $classes ) {
    $classes[] = 'fauorg-kooperation';
    $fau_blog_page_sidebar = get_theme_mod('fau_blog_color_scheme');
    switch ($fau_blog_page_sidebar) {
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
    return $classes;
}