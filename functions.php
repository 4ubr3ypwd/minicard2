<?php

function minicard2_main_nav_ajax() { ?>
	<script type="text/javascript">
	/* <![CDATA[ */
		jQuery.noConflict();

		(function($) { 
			$(function() {
			
				<?php if (get_option('main_nav_ajax')=='yes') : ?>
							
					// Main Nav Ajax Stuff
					$('#mainNav a').click(function(){
						var url = $(this).attr('href');
						
						$("#content").slideUp('',function(){
							$(this).load( url + " #content .inner", function() {
								$(this).slideDown();
							})
						});
						
						$('#mainNav li').removeClass('current_page_item current_page_parent current_page_ancestor');
						$(this).parent().addClass('current_page_item');
						return false;
					});
				
				<?php endif; ?>
					
				// Feed AJAX
				<?php if (file_exists(TEMPLATEPATH.'/premium/feed_head.js')) include(TEMPLATEPATH.'/premium/feed_head.js'); ?>
			});
		})(jQuery);
	/* ]]> */
	</script>
<?php }

function minicard2_themeslice_title_tag() {
	if (function_exists('themeslice_title_tag')) : 
		themeslice_title_tag(); 
	else : 
		wp_title('&laquo;', true, 'right');
		bloginfo('name'); 
	endif;
}

function minicard2_mtheme_inline() {
	$mtheme = get_option('minicard_theme');
	$mtheme_p = get_option('minicard_theme_p');
	if (!$mtheme_p) $mtheme_p = 'burst.jpg';
	if ($mtheme_p=='burst.jpg') $mtheme_repeat = 'no-repeat'; else $mtheme_repeat = 'repeat';
	if ($mtheme) {
		echo '<link rel="stylesheet" href="'.get_bloginfo('template_url').$mtheme.'/style.css" type="text/css" media="screen" />';
		echo '
			<style type="text/css">
				body {
					background-image: url('.get_bloginfo('template_url').''.$mtheme.'/images/bg/'.$mtheme_p.');
					background-repeat:'.$mtheme_repeat.';
				}
			</style>
		';
	} else {
		echo '
			<style type="text/css">
				body {
					background-image: url('.get_bloginfo('template_url').'/images/bg/'.$mtheme_p.');
					background-repeat: '.$mtheme_repeat.';
				}
			</style>
		';
	}
}

function minicard2_enqueue_scripts() {
	wp_enqueue_style( 
		'jquery.fancybox.css', 
		get_template_directory_uri()
			. 'js/jquery.fancybox/jquery.fancybox.css', 
		array(
			'jquery'
		), 
		'3.9', 
		'all' 
	);

	wp_enqueue_script( 
		'jquery.easing.1.3.js', 
		get_template_directory_uri()
			. 'js/jquery.fancybox/jquery.easing.1.3.js', 
		array(
			'jquery'
		), 
		'3.9', 
		'all' 
	);

	wp_enqueue_script( 
		'jquery.fancybox-1.2.1.pack.js', 
		get_template_directory_uri()
			. 'js/jquery.fancybox/jquery.fancybox-1.2.1.pack.js', 
		array(
			'jquery'
		), 
		'3.9',
		'all' 
	);
}

add_action( 'wp_enqueue_script', 'minicard2_enqueue_scripts' );

function minicard2_register_nav_menu() {
	register_nav_menu(
		'minicard2-navigation',
		__( 'Navigation' )
	);
}

add_action( 'init', 'minicard2_register_nav_menu' );

function minicard2_common_meta() { ?>
	<p class="meta">
		
		<?php if ( comments_open() ) : ?>
			<span class="comment">
				<a href="<?php echo get_permalink($post->ID); ?>#comments">
					<?php comments_number('<strong>0</strong>', '<strong>1</strong>', '<strong>%</strong>'); ?>
				</a>
			</span>
		<?php endif; ?>

		<strong class="user">
			<?php the_author_posts_link(); ?>
		</strong> 

		<br>

		<span class="date">
			<?php the_time('jS M Y'); ?>
		</span> 

		<br>

		<span class="cat">
			<?php if (function_exists('parentless_categories')) parentless_categories(','); else the_category( ', ', 'multiple' ); ?>
		</span>

		<?php the_tags(' <br> <span class="tag">', ', ', '</span>'); ?>
	</p>

<?php }

if (file_exists(TEMPLATEPATH.'/admin/theme-config.php')) include_once("admin/theme-config.php");
if (file_exists(TEMPLATEPATH.'parentless-categories.php')) include_once("parentless-categories.php");

if ( function_exists('register_sidebar') ) {

	register_sidebar(array(
	  	'name' => __('Beneath the Card (Top)', 'minicard'),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '<div class="clear"></div></li>',
        'before_title' => '<h2 class="section widgettitle">',
        'after_title' => '</h2>',
	));
	register_sidebar(array(
	  	'name' => __('Beneath the Card (Bottom)', 'minicard'),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '<div class="clear"></div></li>',
        'before_title' => '<h2 class="section widgettitle">',
        'after_title' => '</h2>',
	));
	
}

if(function_exists('add_theme_support')) :
    add_theme_support( 'nav-menus' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'post-thumbnails', array( 'post' ) );
    set_post_thumbnail_size( 400, 144, true ); 
    add_image_size( 'folio', 206, 150, true );
else :
	function add_folio_size($sizes) {
		$sizes[] = 'folio';
		add_option('folio_size_w', '206');
		add_option('folio_size_h', '150');
		add_option('folio_crop', 'true');
		return $sizes;
	}
	add_filter('intermediate_image_sizes', 'add_folio_size');
endif;

function minicard_feed_link(){
	global $wp_version;
	$default_feed_link = '<link rel="alternate" type="application/rss+xml" title="'.get_bloginfo('name').' RSS Feed" href="'.get_bloginfo('rss2_url').'" />';
	if($wp_version < 3) {
		if(function_exists(automatic_feed_links)){
			$output .= automatic_feed_links();
		} else {
			$output .= $default_feed_link;
		}
	}
	echo $output;
}
add_action('wp_head', 'minicard_feed_link');

################################################################################
// Lang Handling
################################################################################

function minicard_language(){
	load_theme_textdomain('minicard', get_bloginfo('template_url') . '/lang');
}
add_action ('init', 'minicard_language');

################################################################################
// Show navigation (if not using wordpress nav menus)
################################################################################

function minicard_page_menu() {
	global $wp_query;
	echo '<ul>';
	$exclude = str_replace(' ', '', trim(get_option('exclude_ids')));
	if ($exclude) $exclude = '&exclude='.$exclude.'';
	
	/* Show home unless un-needed */
		$show_home = true;
		$post_obj = $wp_query->get_queried_object();
		$post_ID = $post_obj->ID;

		$show_on_front = get_option( 'show_on_front'); // does the front page display the latest posts or a static page
		$page_on_front = get_option( 'page_on_front' ); // if it shows a page, what page
		$page_for_posts = get_option( 'page_for_posts' );
		
		if ($show_on_front == 'page' && $page_on_front > 0) $show_home = false;
		
		if ($show_home) {
		
			echo '<li class="page_item ';
			
			if (is_home() && $post_ID!=$page_for_posts) echo 'current_page_item';
			
			echo '"><a href="'.get_bloginfo('url').'" title="'.__('Home','minicard').'">'.__('Home','minicard').'</a></li>';
			
			if ( ( is_home() || is_search() ) && $post_ID!=$page_for_posts) {
				echo str_replace('current_page_parent','', wp_list_pages('sort_column=menu_order&title_li=&echo=0&link_before=&link_after=&depth=1'.$exclude));
			} else {
				echo wp_list_pages('sort_column=menu_order&title_li=&echo=0&link_before=&link_after=&depth=1'.$exclude);
			}
		
		} else {				
			echo wp_list_pages('sort_column=menu_order&title_li=&echo=0&link_before=&link_after=&depth=1'.$exclude);					
		}
	/* end */
	echo minicard_menu_search_box();
	echo '</ul>';
}

function minicard_menu_search_box( $html = '', $args = '' ) {
	if (get_option('enable_search')=='yes') :
		$html .= '<li class="search">';
		$html .= '<form method="get" class="searchform" action="'.get_bloginfo('url').'/">
			<div><label class="hidden" for="sf">'.__('Search:', 'minicard').'</label><input type="text" value="'.get_search_query().'" name="s" id="sf" class="text" /><input type="submit" class="submit" value="'.__('Search', 'minicard').'" /></div>
		</form>';
		$html .= '</li>';
	endif;
	return $html;
}

################################################################################
// Append URL
################################################################################
if (!function_exists('minicard_append_url')) {
function minicard_append_url( $old, $append ) {
	$querystring = explode('?', $old);
	$add = '?';
	if ($querystring[1]) {
		$add .= $querystring[1].'&amp;';
	}
	$add .= $append;
	return $querystring[0].$add;
}
}
	
################################################################################
// Encode Email - Modded from http://bakery.cakephp.org/articles/view/easy-email-address-encoder
################################################################################

function encode_email($mail, $text="", $class="", $prefix)
{
    $encmail ="";
    for($i=0; $i<strlen($mail); $i++)
    {
        $encMod = rand(0,2);
        switch ($encMod) {
        case 0: // None
            $encmail .= substr($mail,$i,1);
            break;
        case 1: // Decimal
            $encmail .= "&#".ord(substr($mail,$i,1)).';';
            break;
        case 2: // Hexadecimal
            $encmail .= "&#x".dechex(ord(substr($mail,$i,1))).';';
            break;
        }
    }
    $encprefix ="";
    for($i=0; $i<strlen($prefix); $i++)
    {
        $encMod = rand(0,2);
        switch ($encMod) {
        case 0: // None
            $encprefix .= substr($prefix,$i,1);
            break;
        case 1: // Decimal
            $encprefix .= "&#".ord(substr($prefix,$i,1)).';';
            break;
        case 2: // Hexadecimal
            $encprefix .= "&#x".dechex(ord(substr($prefix,$i,1))).';';
            break;
        }
    }

    if(!$text)
    {
        $text = $encmail;
    }
    $encmail = $prefix.$encmail;
    return "<a class='$class' href='$encmail'>$text</a>";
}

################################################################################
// Comment formatting
################################################################################

function themeslice_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment_container">

			<p class="meta">
				<small>
					<?php echo get_avatar( $comment, $size='32' ); ?><strong>
					
					<?php comment_author_link(); ?></strong><br>
					<?php echo get_comment_date(); ?>, <?php echo get_comment_time(); ?>). 

					<span class="bull-comment_reply_link">&mdash;</span>

					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
					</small>
			</p>
  			
  			<div class="comment-text">
  				<?php if ($comment->comment_approved == '0') : ?>
  					<p><em><?php _e('Your comment is awaiting approval', 'minicard'); ?></em></p>
  				<?php endif; ?>
  				<?php comment_text(); ?>
  			</div>			
		</div>
	<?php
}

################################################################################
// ThemeSlice Title Tag
################################################################################

function themeslice_title_tag() {
	
	global $wp_query;
	$page_for_posts = get_option( 'page_for_posts' );
	$post_obj = $wp_query->get_queried_object();
	$thepostid = $post_obj->ID;
	
	$addsuffix = true;
	
	$doctitle = array();
        	
    if ( is_single() ) 							
    	$doctitle[] = single_post_title('', FALSE);
    elseif ( is_home() || is_front_page() ) {
    	
    	if (get_option( 'show_on_front') == 'page' && $thepostid==$page_for_posts) {
    		$doctitle[] = $post_obj->post_title;
    	} else {
    		$doctitle[] = get_bloginfo('name').' &ndash; '.get_bloginfo('description');
    		$addsuffix = false;
    	}
    	
    } elseif ( is_page() ) 						
    	$doctitle[] = single_post_title('', FALSE);
    elseif ( is_search() ) {
    	$doctitle[] = __('Search Results for:','minicard').' &ldquo;'.wp_specialchars(stripslashes(get_search_query()), true).'&rdquo;';
    }
    elseif ( is_category() ) {
		$doctitle[] = __('Category Archives:','minicard').' '.single_cat_title("", false);
    }
    elseif ( is_tag() ) { 
		$doctitle[] =  __('Tag Archives:','minicard').' &ldquo;'.single_tag_title("", false).'&rdquo;';
    }
    elseif ( is_404() ) { 
		$doctitle[] =  __('Page Not Found','minicard'); 
    }
    elseif (is_author()) { 
		global $wp_query;
		$curauth = $wp_query->get_queried_object();
		
		$doctitle[] = ucwords($curauth->nickname).__('\'s author archive','minicard'); 
    } else { 
		$doctitle[] = get_bloginfo('name').' &ndash; '.get_bloginfo('description');
		$addsuffix = false;
    }

    if (get_query_var('paged')) {
		$doctitle[] = ' &mdash; Page '.get_query_var('paged');
    }
    
    if ($addsuffix) $doctitle[] = ' &lsaquo; '.get_bloginfo('name').' &ndash; '.get_bloginfo('description');
	
    // But if they don't, it won't try to implode
    $doctitle_string = implode('', $doctitle);
    
	if ($doctitle_string) echo $doctitle_string; else wp_title('',true);
}

################################################################################
// Get ID of top category parent
################################################################################

if (!function_exists('get_top_cat')) {
	function get_top_cat($cat = '') {
		$parentCatList = get_category_parents($cat,false,',');
		$parentCatListArray = split(",",$parentCatList);
		$topParentName = $parentCatListArray[0];
		return get_cat_id($topParentName);
	}
}

################################################################################
// Filters
################################################################################
function reduce_excerpt_len() {
	return 23;
}
add_filter('excerpt_length', 'reduce_excerpt_len'); 
	
function custom_excerpt($text) {
	return str_replace('[...]', ' <a href="'. get_permalink($post->ID) . '" class="more">' . __('More&nbsp;&raquo;','minicard') . '</a>', $text);
}
add_filter('the_excerpt', 'custom_excerpt');
?>