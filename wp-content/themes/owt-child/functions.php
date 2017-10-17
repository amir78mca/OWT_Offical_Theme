<?php 

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles',999 );
	 function my_theme_enqueue_styles() { 
	 $me = wp_get_theme();
    $version = $me->Version;
 		 // wp_enqueue_style( 'parent-style', get_stylesheet_directory_uri() . '/style.css' ); 
			 wp_enqueue_style( 'owt-child', get_stylesheet_directory_uri() . '/style.css'); 
 		  } 
?>

