<?php

/*
 * Add Page Widget Area
 */
add_action( 'widgets_init', 'fau_blog_widgets_init' );
function fau_blog_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Page Sidebar', 'textdomain' ),
        'id'            => 'sidebar-page',
        'description'   => __( 'Widgets in this area will be shown on two column pages.', 'fau' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );

    register_widget("Fau_Blog_Subpages_Menu");
}

/*
 * Add Subpages Widget
 */
class Fau_Blog_Subpages_Menu extends WP_Widget {

    public function __construct() {
        // actual widget processes
        parent::__construct(
            'fau_blog_subpages_menu',  // Base ID
            'Subpages Menu'   // Name
        );
    }

    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );

    public function widget( $args, $instance ) {
        // outputs the content of the widget
        global $post;

        $page_args = [
            'depth' => 2,
            'container' => 'nav',
            'menu_class' => 'page-sidebar-subnav',
            'echo' => false,
        ];
        if ($post->post_parent == 0 ) {
            $page_args['child_of'] = $post->ID;
            $title = get_the_title();
        } else {
            $page_args['child_of'] = $post->post_parent;
            $title = '<a href="' . get_permalink($post->post_parent) . '">' . get_the_title($post->post_parent) . '</a>';
        }
        $page_args['before'] = '<h2 id="subnavtitle" class="small menu-header">' . $title . '</h2><ul class="menu">';

        $pages = get_pages($page_args);
        if ( count($pages) > 0 ) {
            print '<div class="widget widget_nav_menu">'
                . wp_page_menu($page_args)
                . '</div>';
        }
    }

    public function form( $instance ) {
        // outputs the options form in the admin
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'text_domain' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}