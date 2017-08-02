<?php

if (!defined('ABSPATH')) exit;


// shortcode [posttoinstagram]
function PTI_POSTTOINSTAGRAM_shortcode($atts) {
	global $PTI_POSTTOINSTAGRAM_defaults;

	extract(shortcode_atts($defaults = $PTI_POSTTOINSTAGRAM_defaults, $atts, 'posttoinstagram'), EXTR_SKIP);

	$show_heading = $show_heading === '' ? 'false' : $show_heading;
	$scroll = $scroll === '' ? 'false' : $scroll;

	return '<div 
		data-il
		data-il-api="' . PTI_POSTTOINSTAGRAM_API_URL . '"
		data-il-username="' . (!empty($username) ? esc_attr($username) : '') . '" 
		data-il-hashtag="' . (!empty($hashtag) ? esc_attr($hashtag) : '') . '"
		data-il-lang="' . (!empty($lang) ? esc_attr($lang) : '') . '"
		data-il-show-heading="' . (!empty($show_heading) ? esc_attr($show_heading) : '') . '" 
		data-il-scroll="' . (!empty($scroll) ? esc_attr($scroll) : '') . '" 
		data-il-width="' . (!empty($width) ? esc_attr($width) : '') . '" 
		data-il-height="' . (!empty($height) ? esc_attr($height) : '') . '" 
		data-il-image-size="' . (!empty($image_size) ? esc_attr($image_size) : '') . '" 
		data-il-bg-color="' . (!empty($bg_color) ? esc_attr($bg_color) : '') . '" 
		data-il-content-bg-color="' . (!empty($content_bg_color) ? esc_attr($content_bg_color) : '') . '" 
		data-il-font-color="' . (!empty($font_color) ? esc_attr($font_color) : '') . '"
		data-il-ban="' . (!empty($ban) ? esc_attr($ban) : '') . '"
		data-il-cache-media-time="' . (!empty($cache_media_time) ? esc_attr($cache_media_time) : '') . '">
	</div>';
}
add_shortcode('posttoinstagram', 'PTI_POSTTOINSTAGRAM_shortcode');

?>