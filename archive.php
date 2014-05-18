<?php get_header(); ?>

<div id="mainContent">

<?php if (have_posts()) : ?>

		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php
		/* If this is a category archive */ 
		if (is_category()) { ?>
			<h1 class="title"><?php _e('Archive for the &ldquo;', 'minicard'); ?><?php single_cat_title(); ?><?php _e('&rdquo; category', 'minicard'); ?> <?php if (get_query_var('paged')) echo ' &mdash; '.__('Page', 'minicard').' '.get_query_var('paged'); ?></h1>
		<?php /* If this is a tag archive */ 
		} elseif( is_tag() ) { ?>
		<h1 class="title"><?php _e('Posts Tagged &ldquo;', 'minicard'); ?><?php single_tag_title(); ?><?php _e('&rdquo;', 'minicard'); ?> <?php if (get_query_var('paged')) echo ' &mdash; '.__('Page', 'minicard').' '.get_query_var('paged'); ?></h1>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h1 class="title"><?php _e('Archive for', 'minicard'); ?> <?php the_time('F jS, Y'); ?> <?php if (get_query_var('paged')) echo ' &mdash; '.__('Page', 'minicard').' '.get_query_var('paged'); ?></h1>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h1 class="title"><?php _e('Archive for', 'minicard'); ?> <?php the_time('F, Y'); ?> <?php if (get_query_var('paged')) echo ' &mdash; '.__('Page', 'minicard').' '.get_query_var('paged'); ?></h1>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h1 class="title"><?php _e('Archive for', 'minicard'); ?> <?php the_time('Y'); ?> <?php if (get_query_var('paged')) echo ' &mdash; '.__('Page', 'minicard').' '.get_query_var('paged'); ?></h1>
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h1 class="title"><?php 
		global $wp_query;
		$curauth = $wp_query->get_queried_object();
		echo ucwords($curauth->nickname); 
		?><?php _e("\'s Author Archive", 'minicard'); ?> <?php if (get_query_var('paged')) echo ' &mdash; Page '.get_query_var('paged'); ?></h1>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h1 class="title"><?php _e('Blog Archives', 'minicard'); ?> <?php if (get_query_var('paged')) echo ' &mdash; '.__('Page', 'minicard').' '.get_query_var('paged'); ?></h1>
		<?php } ?>

	<?php $full_post = get_option('full_post'); while (have_posts()) : the_post(); ?>
	
		<div class="post">			
			<h2 class="post-title"><a href="<?php echo get_permalink($post->ID); ?>"><?php the_title(); ?></a></h2>
			<?php
			if (function_exists('the_post_thumbnail') && has_post_thumbnail()) :
				echo '<a href="'.get_permalink($post->ID).'" class="post_thumbnail">';
				the_post_thumbnail();
				echo '</a>';
			endif; 
			?>	

			<?php
				if ($full_post == 'yes' ) the_content(); else the_excerpt();
			?>

			<?php minicard2_common_meta(); ?>

			<div class="clear"></div>
		</div>
		
	<?php endwhile; else: ?>
		<p><?php _e('Sorry, no posts matched your criteria.', 'minicard'); ?></p>
	<?php endif; ?>

<div class="paging">
		<div style="float:right;"><?php next_posts_link(__('Next Posts &raquo;', 'minicard')) ?></div>
		<div style="float:left;"><?php previous_posts_link(__('&laquo; Previous Posts', 'minicard')) ?></div>
</div>
<div class="clear"></div>

</div>

<?php get_footer(); ?>