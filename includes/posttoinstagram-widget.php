<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('posttoinstagramWidget')) {
	/**
	 * Adds posttoinstagramWidget widget.
	 */
	class posttoinstagramWidget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		function __construct() {
			parent::__construct(
				'posttoinstagramWidget',
				__('Post to Instagram Widget', PTI_POSTTOINSTAGRAM_TEXTDOMAIN),
				array('description' => __('posttoinstagram - WordPress Instagram Widget', PTI_POSTTOINSTAGRAM_TEXTDOMAIN))
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget($args, $instance) {
			extract($instance, EXTR_SKIP);

			echo '<div 
				data-il
				data-il-api="' . PTI_POSTTOINSTAGRAM_API_URL . '"
				data-il-username="' . (!empty($username) ? esc_attr($username) : '') . '" 
				data-il-hashtag="' . (!empty($hashtag) ? esc_attr($hashtag) : '') . '"
				data-il-lang="' . (!empty($lang) ? esc_attr($lang) : '') . '"
				data-il-show-heading="' . (!empty($show_heading) ? $show_heading : 'false') . '" 
				data-il-scroll="' . (!empty($scroll) ? $scroll : 'false') . '" 
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

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form($instance) {
			global $PTI_POSTTOINSTAGRAM_defaults;
			$instance = wp_parse_args($instance, $PTI_POSTTOINSTAGRAM_defaults);
			extract($instance, EXTR_SKIP);
			?>
			<?php if(isset($username)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo esc_attr($username); ?>" autofocus>
				</p>
			<?php } ?>
			
			<?php if(isset($hashtag)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('hashtag'); ?>"><?php _e('Hashtag:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('hashtag'); ?>" name="<?php echo $this->get_field_name('hashtag'); ?>" type="text" value="<?php echo esc_attr($hashtag); ?>">
				</p>
			<?php } ?>

			<?php if(isset($lang)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('lang'); ?>"><?php _e('Language:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<select class='widefat' id="<?php echo $this->get_field_id('lang'); ?>" name="<?php echo $this->get_field_name('lang'); ?>">
						<option value="en"<?php echo ($lang == 'en') ? ' selected' : ''; ?>><?php _e('English', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="id"<?php echo ($lang == 'id') ? ' selected' : ''; ?>><?php _e('Bahasa Indonesia', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="de"<?php echo ($lang == 'de') ? ' selected' : ''; ?>><?php _e('Deutsch', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="es"<?php echo ($lang == 'es') ? ' selected' : ''; ?>><?php _e('Espa&ntilde;ol', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="fr"<?php echo ($lang == 'fr') ? ' selected' : ''; ?>><?php _e('Fran&ccedil;ais', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="it"<?php echo ($lang == 'it') ? ' selected' : ''; ?>><?php _e('Italiano', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="nl"<?php echo ($lang == 'nl') ? ' selected' : ''; ?>><?php _e('Nederlands', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="pl"<?php echo ($lang == 'pl') ? ' selected' : ''; ?>><?php _e('Polski', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="pt-BR"<?php echo ($lang == 'pt-BR') ? ' selected' : ''; ?>><?php _e('Portugu&ecirc;s', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="ru"<?php echo ($lang == 'ru') ? ' selected' : ''; ?>><?php _e('&#x0420;&#x0443;&#x0441;&#x0441;&#x043a;&#x0438;&#x0439;', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="sv"<?php echo ($lang == 'sv') ? ' selected' : ''; ?>><?php _e('Svenska', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="tr"<?php echo ($lang == 'tr') ? ' selected' : ''; ?>><?php _e('T&uuml;rk&ccedil;e', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="zh-HK"<?php echo ($lang == 'zh-HK') ? ' selected' : ''; ?>><?php _e('&#x4e2d;&#x6587;', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="ja"<?php echo ($lang == 'ja') ? ' selected' : ''; ?>><?php _e('&#x65e5;&#x672c;&#x8a9e;', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="ko"<?php echo ($lang == 'ko') ? ' selected' : ''; ?>><?php _e('&#xd55c;&#xad6d;&#xc758;', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                        <option value="he"<?php echo ($lang == 'he') ? ' selected' : ''; ?>><?php _e('&#x5E2;&#x5B4;&#x5D1;&#x5B0;&#x5E8;&#x5B4;&#x5D9;&#x5EA;', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
		        	</select>
				</p>
			<?php } ?>

			<?php if(isset($show_heading)) {?>
				<p>
					<input class="checkbox" type="checkbox" <?php checked($show_heading == 'true', true); ?> id="<?php echo $this->get_field_id('show_heading'); ?>" name="<?php echo $this->get_field_name('show_heading'); ?>" value="true">
					<label for="<?php echo $this->get_field_id('show_heading'); ?>"><?php _e('Show Heading', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
				</p>
			<?php } ?>

			<?php if(isset($scroll)) {?>
				<p>
					<input class="checkbox" type="checkbox" <?php checked($scroll == 'true', true); ?> id="<?php echo $this->get_field_id('scroll'); ?>" name="<?php echo $this->get_field_name('scroll'); ?>" value="true">
					<label for="<?php echo $this->get_field_id('scroll'); ?>"><?php _e('Scroll', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
				</p>
			<?php } ?>

			<?php if(isset($width)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($width); ?>">
				</p>
			<?php } ?>

			<?php if(isset($height)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($height); ?>">
				</p>
			<?php } ?>

			<?php if(isset($image_size)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Image Size:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<select class='widefat' id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>">
		          		<option value='small'<?php echo ($image_size == 'small') ? ' selected' : ''; ?>><?php _e('Small', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
		          		<option value='medium'<?php echo ($image_size == 'medium') ? ' selected' : ''; ?>><?php _e('Medium', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
		          		<option value='large'<?php echo ($image_size == 'large') ? ' selected' : ''; ?>><?php _e('Large', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
		          		<option value='xlarge'<?php echo ($image_size == 'xlarge') ? ' selected' : ''; ?>><?php _e('xLarge', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
		        	</select>
				</p>
			<?php } ?>

			<?php if(isset($bg_color)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('bg_color'); ?>"><?php _e('Panel and Button Background:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('bg_color'); ?>" name="<?php echo $this->get_field_name('bg_color'); ?>" type="text" value="<?php echo esc_attr($bg_color); ?>">
				</p>
			<?php } ?>

			<?php if(isset($content_bg_color)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('content_bg_color'); ?>"><?php _e('Content Background Color:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('content_bg_color'); ?>" name="<?php echo $this->get_field_name('content_bg_color'); ?>" type="text" value="<?php echo esc_attr($content_bg_color); ?>">
				</p>
			<?php } ?>
			
			<?php if(isset($font_color)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('font_color'); ?>"><?php _e('Font Color:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('font_color'); ?>" name="<?php echo $this->get_field_name('font_color'); ?>" type="text" value="<?php echo esc_attr($font_color); ?>">
				</p>
			<?php } ?>

			<?php if(isset($ban)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('ban'); ?>"><?php _e('Ban by Username:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('ban'); ?>" name="<?php echo $this->get_field_name('ban'); ?>" type="text" value="<?php echo esc_attr($ban); ?>">
				</p>
			<?php } ?>

			<?php if(isset($cache_media_time)) {?>
				<p>
					<label for="<?php echo $this->get_field_id('cache_media_time'); ?>"><?php _e('Cache Media Time:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('cache_media_time'); ?>" name="<?php echo $this->get_field_name('cache_media_time'); ?>" type="text" value="<?php echo esc_attr($cache_media_time); ?>">
				</p>
			<?php }
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update($new_instance, $old_instance) {
			$instance = $old_instance;
		    $instance['username'] = !empty($new_instance['username']) ? $new_instance['username'] : '';
		    $instance['hashtag'] = !empty($new_instance['hashtag']) ? $new_instance['hashtag'] : '';
		    $instance['lang'] = !empty($new_instance['lang']) ? $new_instance['lang'] : '';
		    $instance['show_heading'] = !empty($new_instance['show_heading']) ? 'true' : 'false';
		    $instance['scroll'] = !empty($new_instance['scroll']) ? 'true' : 'false';
		    $instance['width'] = !empty($new_instance['width']) ? $new_instance['width'] : '';
		    $instance['height'] = !empty($new_instance['height']) ? $new_instance['height'] : '';
		    $instance['image_size'] = !empty($new_instance['image_size']) ? $new_instance['image_size'] : '';
		    $instance['bg_color'] = !empty($new_instance['bg_color']) ? $new_instance['bg_color'] : '';
		    $instance['content_bg_color'] = !empty($new_instance['content_bg_color']) ? $new_instance['content_bg_color'] : '';
		    $instance['font_color'] = !empty($new_instance['font_color']) ? $new_instance['font_color'] : '';
		    $instance['ban'] = !empty($new_instance['ban']) ? $new_instance['ban'] : '';
		    $instance['cache_media_time'] = !empty($new_instance['cache_media_time']) ? $new_instance['cache_media_time'] : '';

		    return $instance;
		}
	}

	if(!function_exists('PTI_POSTTOINSTAGRAM_register_widget')) {
		function PTI_POSTTOINSTAGRAM_register_widget() {
		    register_widget('posttoinstagramWidget');
		}
		add_action('widgets_init', 'PTI_POSTTOINSTAGRAM_register_widget');	
	}
}

?>