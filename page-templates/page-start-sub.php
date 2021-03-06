<?php
/**
 * Template Name: Startseite
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

    global $is_sidebar_active;

    get_header();    
    get_template_part('template-parts/hero', 'banner');

?>

    <div id="content">
	<div class="container">
	    <div class="row">
            <?php get_template_part('template-parts/sidebar', 'inline');
            if ($is_sidebar_active) {
                echo '<div class="startpage-blogroll with-sidebar">';
            } else {
                echo '<div class="startpage-blogroll">';
            } ?>
		    <main<?php echo fau_get_page_langcode($post->ID);?> id="droppoint">	  
			<h1 class="screen-reader-text"><?php the_title(); ?></h1>
		    <?php 
			wp_reset_postdata();
			wp_reset_query();

			the_content();
			
		
			$number = 0;
			$max = get_theme_mod('start_max_newspertag');
			$maxall = get_theme_mod('start_max_newscontent');

			if ($maxall > 0) {
                if (get_theme_mod('fau_blog_blogroll_layout') == 'tiles') {
                    echo '<div class="blogroll">';
                }
			    
			    $displayedposts = array();
			    for($j = 1; $j <= 3; $j++) {
				    $i = 0;
				    $thistag = get_theme_mod('start_prefix_tag_newscontent').$j;    
				    $query = new WP_Query( 'tag='.$thistag );
				    while ($query->have_posts() && ($i<$max) && ($number<$maxall) ) {
                        $query->the_post();
                        if (get_theme_mod('fau_blog_blogroll_layout') == 'tiles') {
                            echo fau_blog_display_news_tiles($post->ID, true);
                        } else {
                            echo fau_display_news_teaser($post->ID, true);
                        }
                        $i++;
                        $number++;
                        $displayedposts[] = $post->ID;
				    }
				    wp_reset_postdata();
				    wp_reset_query();

			    }
			    if (($number==0) || ($number < $maxall)) {
				    $startlinknewscat = get_theme_mod('start_link_news_cat');
                    if ($number < $maxall) {
                        $num = $maxall - $number;
                        if ($num <=0 ) {
                        $num=1;
                        }

                        if (isset($startlinknewscat)) {
                        $query = new WP_Query(  array( 'post__not_in' => $displayedposts, 'posts_per_page'  => $num, 'has_password' => false, 'post_type' => 'post', 'cat' => $startlinknewscat ) );
                        } else {
                        $query = new WP_Query(  array( 'post__not_in' => $displayedposts, 'posts_per_page'  => $num, 'has_password' => false, 'post_type' => 'post'  ) );
                        }
                    } else {
                         $args = '';
                        if (isset($startlinknewscat)) {
                        $args = 'cat='.$startlinknewscat;
                        }
                        if (isset($args)) {
                        $args .= '&';
                        }

                        $args .= 'post_type=post&has_password=0&posts_per_page='.get_theme_mod('start_max_newscontent');
                        $query = new WP_Query( $args );
                    }
                    while ($query->have_posts() ) {
                        $query->the_post();
                        if (get_theme_mod('fau_blog_blogroll_layout') == 'tiles') {
                            echo fau_blog_display_news_tiles($post->ID, true);
                        } else {
                            echo fau_display_news_teaser($post->ID, true);
                        }
                        wp_reset_postdata();
                    }
			    }
                if (get_theme_mod('fau_blog_blogroll_layout') == 'tiles') {
                    echo '</div>';
                }

			    if (get_theme_mod('start_link_news_show')) {
				    echo fau_get_category_links();
			    }
			}

			?>
			</main>
        </div>
    </div>
		<?php  


		 $menuslug = get_post_meta( $post->ID, 'portalmenu-slug', true );	
		 if ($menuslug) { 	
		    echo "<hr>";
		    		
		    $nosub  = get_post_meta( $post->ID, 'fauval_portalmenu_nosub', true );
		    if ($nosub==1) {
			$displaysub =0;
		    } else {
			$displaysub =1;
		    }
		    $nofallbackthumbs  = get_post_meta( $post->ID, 'fauval_portalmenu_nofallbackthumb', true );
		    $nothumbnails  = get_post_meta( $post->ID, 'fauval_portalmenu_thumbnailson', true ); 

		    fau_get_contentmenu($menuslug,$displaysub,0,$nothumbnails,$nofallbackthumbs);

		 }
		$logoliste = get_post_meta( $post->ID, 'fauval_imagelink_catid', true );
		if ($logoliste) { 
		    /* New since 1.10.57 */
		    $logos = fau_imagelink_get(array('size' => "logo-thumb", 'catid' => $logoliste, "autoplay" => true, "dots" => true));
		    if ((isset($logos) && (!empty($logos)))) {
			echo "<hr>\n";
			echo $logos;
		    }

		} ?>
	</div>
    </div>  
<?php get_template_part('template-parts/footer', 'social'); ?>	
<?php 
get_footer(); 

