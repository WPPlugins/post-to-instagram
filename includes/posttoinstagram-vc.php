<?php

if (!defined('ABSPATH')) exit;


function PTI_POSTTOINSTAGRAM_vc() {
	global $PTI_POSTTOINSTAGRAM_defaults;
	extract($PTI_POSTTOINSTAGRAM_defaults, EXTR_SKIP);

	vc_map(array(
		'name' => __('posttoinstagram', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
		'description' => __('Instagram Widget', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
		'base' => 'posttoinstagram',
		'class' => '',
		'category' => __('Social', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
		'icon' => plugins_url('assets/img/posttoinstagram-vc-icon.png', PTI_POSTTOINSTAGRAM_FILE),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __('Username', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'username',
				'value' => (!empty($username) ? esc_attr($username) : ''),
				'description' => __('Instagram username.', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'textfield',
				'heading' => __('Hashtag', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'hashtag',
				'value' => (!empty($hashtag) ? esc_attr($hashtag) : ''),
				'description' => __('Instagram hashtag. You can specify multiple tags separated by comma or space.', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Language', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'lang',
				'value' => array(
					__('English', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'en',
					__('Bahasa Indonesia', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'id',
					__('Español', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'es',
					__('Français', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'fr',
					__('Italiano', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'it',
					__('Nederlands', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'nl',
					__('Polski', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'pl',
					__('Português', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'pt-BR',
					__('Русский', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'ru',
					__('Svenska', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'sv',
					__('Türkçe', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'tr',
					__('中文', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'zh-HK',
					__('日本語', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'ja',
					__('한국의', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'ko',
					__('עִבְרִית', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'he'
				),
				'std' => (!empty($lang) ? esc_attr($lang) : ''),
				'description' => __('Choose widget\'s UI language.', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'show_heading',
				'value' => array(
					__('Show Heading', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'true'
				),
				'std' => (!empty($show_heading) ? esc_attr($show_heading) : ''),
				'description' => __('Show heading panel.', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'scroll',
				'value' => array(
					__('Scroll', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'true'
				),
				'std' => (!empty($scroll) ? esc_attr($scroll) : ''),
				'description' => __('Enable pagination and scroll.', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'textfield',
				'heading' => __('Width', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'width',
				'value' => (!empty($width) ? esc_attr($width) : ''),
				'description' => __('Widget width (any CSS valid value: px, %, em, etc). Set "auto" to make the widget responsive.', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'textfield',
				'heading' => __('Height', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'height',
				'value' => (!empty($height) ? esc_attr($height) : ''),
				'description' => __('Widget height (any CSS valid value: px, %, em, etc).', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Image Size', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'image_size',
				'value' => array(
					__('Small', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'small',
					__('Medium', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'medium',
					__('Large', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'large',
					__('xLarge', PTI_POSTTOINSTAGRAM_TEXTDOMAIN) => 'xlarge'
				),
				'std' => (!empty($image_size) ? esc_attr($image_size) : ''),
				'description' => __('Size of images (small, medium, large, xlarge).', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Header and Button Color', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'bg_color',
				'value' => (!empty($bg_color) ? esc_attr($bg_color) : ''),
				'description' => __('Header and button background color (any CSS valid value).', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Content Background Color', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'content_bg_color',
				'value' => (!empty($content_bg_color) ? esc_attr($content_bg_color) : ''),
				'description' => __('Content background color (any CSS valid value).', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Text Color', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'font_color',
				'value' => (!empty($font_color) ? esc_attr($font_color) : ''),
				'description' => __('Header and button text color (any CSS valid value).', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'textfield',
				'heading' => __('Ban by Username', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'ban',
				'value' => (!empty($ban) ? esc_attr($ban) : ''),
				'description' => __('List of usernames to hide their photos from feed separated by comma or space.', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			),
			array(
				'type' => 'textfield',
				'heading' => __('Cache media time', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				'param_name' => 'cache_media_time',
				'value' => (!empty($cache_media_time) ? esc_attr($cache_media_time) : ''),
				'description' => __('It defines how long in seconds the photos will be cached in browsers\' localStorage. Set "0" to turn the cache off.', PTI_POSTTOINSTAGRAM_TEXTDOMAIN)
			)
		)
   ));
}
add_action('vc_before_init', 'PTI_POSTTOINSTAGRAM_vc');

?>