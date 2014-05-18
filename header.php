<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title><?php minicard2_themeslice_title_tag(); ?></title>
	
	<meta name="viewport" content="width=device-width" />
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
	
	<?php	minicard2_mtheme_inline(); ?>

	<?php wp_head(); ?>

	<?php minicard2_main_nav_ajax(); ?>	
</head>
<body>
<div id="wrapper">

	<div class="vcard" id="header">
	
		<img class="photo" src="<?php if ($photo_url = get_option('photo_url')) echo $photo_url; else echo get_bloginfo('template_url').get_option('minicard_theme').'/images/photo.png'; ?>" alt="Photo" height="64" width="64" />
		
		<?php if (is_home() || is_front_page()) : ?>
			<h1 id="name"><a href="<?php echo get_option('home'); ?>/" class="fn url"><?php echo get_bloginfo('name'); ?></a></h1>
		<?php else : ?>
			<div id="name"><a href="<?php echo get_option('home'); ?>/" class="fn url"><?php echo get_bloginfo('name'); ?></a></div>			
		<?php endif; ?>
		<p class="title"><?php bloginfo('description'); ?></p>

		<?php
			$vcard_email = get_option('vcard_email');
			$vcard_org = get_option('vcard_org');
			$vcard_street = get_option('vcard_street');
			$vcard_locality = get_option('vcard_locality');
			$vcard_region = get_option('vcard_region');
			$vcard_postal_code = get_option('vcard_postal_code');
			$vcard_country = get_option('vcard_country');
			$vcard_tel = get_option('vcard_tel');
			
			if ($vcard_email || $vcard_org || $vcard_street || $vcard_locality || $vcard_region || $vcard_postal_code || $vcard_country || $vcard_tel) :
			echo '<dl class="contact_details">';

			if ($vcard_email) echo '
				<dt>Email</dt>
				<dd>'.encode_email($vcard_email, '', 'email', 'mailto:').'</dd>';
			if ($vcard_org) echo '
				<dt>Org</dt>
				<dd class="org">'.$vcard_org.'</dd>';
			if ($vcard_street || $vcard_locality || $vcard_region || $vcard_postal_code || $vcard_country) {
				echo '<dt>Address</dt>
				<dd class="adr">';
				
					 if ($vcard_street) echo '<span class="street-address">'.$vcard_street.'</span><br/>';
					 if ($vcard_locality) echo '<span class="locality">'.$vcard_locality.'</span><br/>';
					 if ($vcard_region) echo '<span class="region">'.$vcard_region.'</span><br/>';
					 if ($vcard_postal_code) echo '<span class="postal-code">'.$vcard_postal_code.'</span><br/>';
					 if ($vcard_country) echo '<span class="country-name">'.$vcard_country.'</span>';
				
				echo '</dd>';
			}
			if ($vcard_tel) echo '
				<dt>Phone</dt>
				<dd class="tel">'.$vcard_tel.'</dd>';

			echo '</dl>';
			endif;
		
		echo '<div class="clear"></div>';
		
		if (get_option('vcard_enable')=='yes') {
		
			echo '<a href="http://feeds.technorati.com/contacts/'.get_bloginfo('url').'" class="dlvcard"><img src="'.get_bloginfo('template_url').get_option('minicard_theme').'/images/vcard.png" alt="Download vCard" width="46" height="38" title="Download vCard" /></a>'; 
		}
		?>

	</div>
	<div class="clear"></div>
	<div id="mainNav">
		<?php wp_nav_menu(); ?>
		<div class="clear"></div>
	</div>
	<div id="content_wrapper"><div id="content"><div class="inner">