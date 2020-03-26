

<?php if(get_post_type() == 'page' && is_active_sidebar( 'sidebar-page' ) ) { ?>
    <aside class="sidebar-page" aria-label="Sidebar">
        <?php dynamic_sidebar( 'sidebar-page' ); ?>
    </aside>
<?php } ?>
