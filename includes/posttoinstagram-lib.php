<?php

if (!defined('ABSPATH')) exit;


// register styles and scripts
function PTI_POSTTOINSTAGRAM_lib() {
	wp_register_style('posttoinstagram', plugins_url('assets/posttoinstagram/posttoinstagram-2.0.7.min.css', PTI_POSTTOINSTAGRAM_FILE), array(), PTI_POSTTOINSTAGRAM_VERSION);
	wp_register_script('posttoinstagram', plugins_url('assets/posttoinstagram/posttoinstagram-2.0.7.min.js', PTI_POSTTOINSTAGRAM_FILE), array('jquery'), PTI_POSTTOINSTAGRAM_VERSION);

	wp_enqueue_style('posttoinstagram');
	wp_enqueue_script('posttoinstagram');
}
add_action('wp_enqueue_scripts', 'PTI_POSTTOINSTAGRAM_lib');

?>