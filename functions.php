<?php

function lionrouge_enqueue_styles() {
	wp_enqueue_style(
		'storefront-parent-style',
		get_parent_theme_file_uri( 'style.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'lionrouge_enqueue_styles' );
