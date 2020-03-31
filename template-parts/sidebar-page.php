

<?php if(get_post_type() == 'page' && is_active_sidebar( 'sidebar-page' ) ) { ?>
    <button class="sidebar-toggle" type="button">
        <span class="button-text"><?php _e('Sidebar', 'fau'); ?></span>
        <span class="fa fa-angle-double-right"aria-hidden="true"></span>
    </button>
    <aside id="sidebar-page" class="sidebar-page" aria-label="Sidebar">
        <?php dynamic_sidebar( 'sidebar-page' ); ?>
    </aside>
<?php } ?>
