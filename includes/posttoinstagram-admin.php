<?php

if (!defined('ABSPATH')) exit;

function PTI_POSTTOINSTAGRAM_admin_init() {
    wp_register_style('posttoinstagram-admin', plugins_url('assets/css/posttoinstagram-admin.css', PTI_POSTTOINSTAGRAM_FILE), array(), PTI_POSTTOINSTAGRAM_VERSION);
    wp_register_script('posttoinstagram-admin', plugins_url('assets/js/posttoinstagram-admin.js', PTI_POSTTOINSTAGRAM_FILE), array('jquery', 'wp-color-picker'), PTI_POSTTOINSTAGRAM_VERSION);
}

function PTI_POSTTOINSTAGRAM_admin_scripts() {
    wp_enqueue_style('posttoinstagram-admin');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('posttoinstagram-admin');
}

function PTI_POSTTOINSTAGRAM_create_menu() {
    $page_hook = add_menu_page(__('Post to Instagram Widget', PTI_POSTTOINSTAGRAM_TEXTDOMAIN), __('Post to Instagram', PTI_POSTTOINSTAGRAM_TEXTDOMAIN), 'manage_options', 'posttoinstagram', 'PTI_POSTTOINSTAGRAM_settings_page', PTI_POSTTOINSTAGRAM_URL . 'assets/img/posttoinstagram-wp-icon.png');
    add_action('admin_init', 'PTI_POSTTOINSTAGRAM_admin_init');
    add_action('admin_print_styles-' . $page_hook, 'PTI_POSTTOINSTAGRAM_admin_scripts');	
}
add_action('admin_menu', 'PTI_POSTTOINSTAGRAM_create_menu');

function PTI_POSTTOINSTAGRAM_settings_page() {
    global $PTI_POSTTOINSTAGRAM_defaults;
    $posttoinstagram_config = $PTI_POSTTOINSTAGRAM_defaults;

    $purchase_code = get_option('posttoinstagram_purchase_code', '');
    $activated = get_option('posttoinstagram_activated', '') == 'true';

    $latest_version = get_option('posttoinstagram_latest_version', '');
    $last_check_datetime = get_option('posttoinstagram_last_check_datetime', '');
    $activation_message = get_option('posttoinstagram_activation_message', '');

    $activation_css_classes = '';
    if ($activated) {
        $activation_css_classes .= 'posttoinstagram-admin-activated ';
    }
    else if (!empty($purchase_code)) {
        $activation_css_classes .= 'posttoinstagram-admin-activation-invalid ';
    }
    if (!empty($latest_version) && version_compare(PTI_POSTTOINSTAGRAM_VERSION, $latest_version, '<')) {
        $activation_css_classes .= 'posttoinstagram-admin-activation-has-new-version ';
    }
 
    if(empty($posttoinstagram_config['username']))
        $posttoinstagram_config['username'] = 'instagram';

    extract($posttoinstagram_config, EXTR_SKIP);

    ?><div class="posttoinstagram-admin wrap">
		<h1>Post to Instagram Widget Settings</h1>
        <div class="posttoinstagram-admin-demo posttoinstagram-admin-block">
            <div class="posttoinstagram-admin-block-icon"><span class="posttoinstagram-admin-icon-settings posttoinstagram-admin-icon"></span></div>
				
            <div class="posttoinstagram-admin-block-inner">
                <div class="posttoinstagram-admin-demo-header">
                    <h2><?php _e('Installation', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></h2>
                    <span class="posttoinstagram-admin-demo-header-hint"><?php _e('Adjust the widget sttings as you wish then get the shortcode below and paste it into any page or post.  You can also use the Widget by going to Appearance => Widgets.', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                </div>

                <div class="posttoinstagram-demo">
                    <form class="posttoinstagram-demo-form">                                
                        <?php if(isset($username)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Username:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input type="text" name="username" value="<?php echo $username; ?>">
                                </label>
                            </div>
                        <?php } ?>
                        
                        <?php if(isset($hashtag)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Hashtag:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input type="text" name="hashtag" value="<?php echo $hashtag; ?>">
                                </label>
                            </div>
                        <?php } ?>

                        <?php if(isset($lang)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Language:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <select name="lang">
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
                                </label>
                            </div>
                        <?php } ?>
                        
                        <?php if(isset($show_heading)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Show Heading:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input type="checkbox" name="show_heading" value="true" <?php checked($show_heading == 'true', true); ?>>
                                </label>
                            </div>
                        <?php } ?>
                        
                        <?php if(isset($scroll)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Scroll:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input type="checkbox" name="scroll" value="true" <?php checked($scroll == 'true', true); ?>>
                                </label>
                            </div>
                        <?php } ?>
                       
                       <?php if(isset($width)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label class="posttoinstagram-demo-field-width">
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Width:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input type="text" name="width" value="<?php echo $width; ?>" size="10">
                                </label>

                                <label class="posttoinstagram-demo-field-responsive">
                                    <input type="checkbox" name="responsive" value="1">
                                    <span class="label"><?php _e('Responsive', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        <?php } ?>

                        <?php if(isset($height)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Height:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input type="text" name="height" value="<?php echo $height; ?>" size="10">
                                </label>
                            </div>
                        <?php } ?>
                        
                        <?php if(isset($image_size)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Image size:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <select name="image_size">
                                        <option value="small"<?php echo $image_size == 'small' ? ' selected' : ''; ?>><?php _e('Small', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                                        <option value="medium"<?php echo $image_size == 'medium' ? ' selected' : ''; ?>><?php _e('Medium', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                                        <option value="large"<?php echo $image_size == 'large' ? ' selected' : ''; ?>><?php _e('Large', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                                        <option value="xlarge"<?php echo $image_size == 'xlarge' ? ' selected' : ''; ?>><?php _e('xLarge', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></option>
                                    </select>
                                </label>
                            </div>
                        <?php } ?>
                      
                        <?php if(isset($bg_color)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Panel, button color:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input class="posttoinstagram-colorpicker" type="text" name="bg_color" value="<?php echo $bg_color; ?>">
                                </label>
                            </div>
                        <?php } ?>

                        <?php if(isset($content_bg_color)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Content background:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input class="posttoinstagram-colorpicker" type="text" name="content_bg_color" value="<?php echo $content_bg_color; ?>">
                                </label>
                            </div>
                        <?php } ?>
                        
                        <?php if(isset($font_color)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Text color:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input class="posttoinstagram-colorpicker" type="text" name="font_color" value="<?php echo $font_color; ?>">
                                </label>
                            </div>
                        <?php } ?>

                        <?php if(isset($ban)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Ban by Username:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input type="text" name="ban" value="<?php echo $ban; ?>">
                                </label>
                            </div>
                        <?php } ?>

                        <?php if(isset($cache_media_time)) {?>
                            <div class="posttoinstagram-demo-field">
                                <label>
                                    <span class="posttoinstagram-demo-field-label"><?php _e('Cache Media Time:', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></span>
                                    <input type="text" name="cache_media_time" value="<?php echo $cache_media_time; ?>" size="10">
                                    <span class="posttoinstagram-demo-field-hint" title="seconds">s</span>
                                </label>
                            </div>
                        <?php } ?>

                        <div class="posttoinstagram-demo-shortcode">
                            <p><?php _e('Copy this shortcode and paste it into any page or post.', PTI_POSTTOINSTAGRAM_TEXTDOMAIN); ?></p>
                            <?php 
                            $shortcode_params = '';
                            foreach($posttoinstagram_config as $key => $value) {
                                if(!empty($value))
                                    $shortcode_params .= sprintf(' %s="%s"', $key, $value);
                            }?>
                            <textarea spellcheck="false" rows="4" readonly>[posttoinstagram<?php echo $shortcode_params; ?>]</textarea>
                        </div>
                    </form>

                    <div class="posttoinstagram-demo-preview-container">
                        <?php $preview_url = PTI_POSTTOINSTAGRAM_URL . 'includes/posttoinstagram-admin-preview.php?' . http_build_query($posttoinstagram_config); ?>

                        <div class="posttoinstagram-demo-preview">
                            <iframe src="<?php echo $preview_url; ?>"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
<?php } ?>