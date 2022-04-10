<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="google-site-verification" content="zOYbKlCTmi15V0VUqTzFI2tkjfyg1lJIVj9OdZd7qL4" />
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
	<div id="header">
    <div id="logo-title"<?php if ( function_exists('the_custom_logo') ) { echo ' class="has-custom-logo"'; } ?>>
      <?php if ( function_exists('the_custom_logo') ) { the_custom_logo(); } ?>
      <?php
        $title_parts  = explode(' ', get_bloginfo('name'));
        $title        = implode(' <br />', $title_parts);
      ?>
  		<?php if ( is_front_page() ) { ?>
  			<h1 id="title"><a href="<?php echo home_url(); ?>"><?php echo $title; ?></a></h1>
  		<?php } else { ?>
  			<p id="title"><a href="<?php echo home_url(); ?>"><?php echo $title; ?></a></p>
  		<?php } ?>
    </div>
    <p class="section-header"><?php echo bloginfo('description'); ?></p>
    <?php get_breadcrumbs(); ?>
	</div>
