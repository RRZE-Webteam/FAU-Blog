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
        'default_faculty_useshorttitle',
        'menu_pretitle_portal',
        'menu_aftertitle_portal',
        'advanced_display_portalmenu_thumb_credits',
        'advanced_activate_synonyms',
        'advanced_activate_glossary',
        'advanced_reveal_pages_id',
        'advanced_activate_page_langcode',
        'google-site-verification',
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
    $wp_customize->add_setting( 'fau_blog_header_watermark', array(
            'default' => 1,
            'transport' => 'refresh',
            'sanitize_callback' => 'fau_sanitize_customizer_toggle_switch'
        )
    );
    $wp_customize->add_control( new WP_Customize_Control_Toggle_Switch( $wp_customize, 'fau_blog_header_watermark', array(
            'label' => __('Show Watermark in Hero', 'fau'),
            'section' => 'webgroup',
            'priority'  => 3,
            'description'	=> 'Show FAU Watermark in hero.',
        )
    ) );

    $wp_customize->add_setting( 'advanced_display_portalmenu_plainview', array(
            'default' => 1,
            'transport' => 'refresh',
            'sanitize_callback' => 'fau_sanitize_customizer_toggle_switch',
        )
    );
    $wp_customize->add_control( new WP_Customize_Control_Toggle_Switch( $wp_customize, 'advanced_display_portalmenu_plainview', array(
            'label'   => __( 'Unterpunkte stilfrei', 'fau' ),
            'description'   => __( 'Die Unterpunkte des Menüs werden ohne Zitat oder Bilder der Portalseite angezeigt.', 'fau' ),
            'section' => 'topmenulinks',
            'default' => false,
        )
    ) );
    $wp_customize->add_setting('fau_blog_blogroll_layout', array(
        'default'   => 'defauls',
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