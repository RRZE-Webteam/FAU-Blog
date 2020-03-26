<?php
/*-----------------------------------------------------------------------------------*/
/* WP Customizer for theme settings
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since FAU-Blog 1.0.0
/*-----------------------------------------------------------------------------------*/

add_action( "customize_register", "fau_blog_customizer_settings", 20 );
function fau_blog_customizer_settings( $wp_customize ) {
    // Panel 'Daten zum Webauftritt'
    $wp_customize->get_setting( 'website_type' )->default = 3;
    $wp_customize->remove_control("website_type");
    $wp_customize->remove_setting('website_type');
    $wp_customize->remove_control("default_faculty_useshorttitle");
    $wp_customize->remove_setting('default_faculty_useshorttitle');
    $wp_customize->get_setting( 'startseite_banner_image' )->description = 'Festes Banner für die Startseite';

    $wp_customize->add_setting('fau_blog_color_scheme', array(
        'default'   => 'fau',
        'transport' => 'refresh'
    ));
    $wp_customize->add_control('fau_blog_color_scheme', array(
        'label'     => esc_html__('Farbschema', 'fau'),
        'section'   => 'webgroup',
        'priority'  => 2,
        'settings'  => 'fau_blog_color_scheme',
        'type'      => 'select',
        'choices'   => array(
            'fau'   => esc_html__('FAU', 'fau'),
            'phil'  => esc_html__('PhilFak', 'fau'),
            'rw'    => esc_html__('RWFak', 'fau'),
            'med'   => esc_html__('MedFak', 'fau'),
            'nat'   => esc_html__('NatFak', 'fau'),
            'tf'    => esc_html__('TechFak', 'fau'),
        ),
    ));
}