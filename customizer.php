<?php
/*-----------------------------------------------------------------------------------*/
/* WP Customizer for theme settings
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since FAU-Blog 1.0.0
/*-----------------------------------------------------------------------------------*/

add_action( "customize_register", "fau_blog_customizer_settings", 20 );
function fau_blog_customizer_settings( $wp_customize ) {
    $wp_customize->get_setting( 'website_type' )->default = 2;
    $wp_customize->get_setting( 'startseite_banner_image' )->description = 'Festes Banner für die Startseite';
    $wp_customize->get_setting( 'contact_address_name' )->default = '';
    $wp_customize->get_setting( 'contact_address_name2' )->default = '';
    $wp_customize->get_setting( 'contact_address_street' )->default = '';
    $wp_customize->get_setting( 'contact_address_plz' )->default = '';
    $wp_customize->get_setting( 'contact_address_ort' )->default = '';
    $wp_customize->get_setting( 'contact_address_country' )->default = '';
    $remove_settings = [
        'website_type',
        //'menu_pretitle_portal',
        //'menu_aftertitle_portal',
        //'advanced_reveal_pages_id',
        //'advanced_activate_page_langcode',
        'google-site-verification',
        'advanced_header_template',
    ];
    foreach ($remove_settings as $setting) {
        $wp_customize->remove_control($setting);
        $wp_customize->remove_setting($setting);
    }
    $remove_sections = [
        'slider'
    ];
    foreach ($remove_sections as $section) {
        $wp_customize->remove_section($section);
    }

    $wp_customize->add_setting('fau_blog_color_scheme', array(
        'default'   => 'fau',
        'transport' => 'refresh'
    ));
    $wp_customize->add_control('fau_blog_color_scheme', array(
        'label'     => esc_html__('Farbschema', 'fau'),
        'section'   => 'title_tagline',
        'priority'  => 10,
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
    $wp_customize->add_setting('fau_blog_blogroll_layout', array(
        'default'   => 'default',
        'transport' => 'refresh'
    ));
    $wp_customize->add_control('fau_blog_blogroll_layout', array(
        'label'     => esc_html__('Bloglayout', 'fau'),
        'section'   => 'postoptions',
        'priority'  => 0,
        'settings'  => 'fau_blog_blogroll_layout',
        'type'      => 'select',
        'choices'   => array(
            'default'   => esc_html__('Standard', 'fau'),
            'tiles'  => esc_html__('Kacheln', 'fau'),
        ),
    ));
}