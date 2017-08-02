<?php
/*
Plugin Name: Post to Instagram & Gallery, Feed, Widget
Plugin URI: https://www.wpcloudapp.com/shop/wordpress-plugins/post-instagram-api-plugin/
Description: Show your Instagram Posts in a beautiful Widget and send your Wordpress Posts or Woocommerce Products to Instagram automatically or on a schedule. 
Version: 2.0
Author: WPCloudApp.com
Author URI: https://www.wpcloudapp.com
*/
if (!defined('ABSPATH')) exit;
if (false !== strstr($_SERVER['REQUEST_URI'], 'page=posttoinstagram') || false !== strstr($_SERVER['REQUEST_URI'], 'page=post-to-instagram.php')) {
    $resp = PosttoInstagram::woocommerce_woo_api(get_option('post-to-instagram_woocommerce_api_key'), get_option('post-to-instagram_woocommerce_api_email'), 'status');
    if (!isset($resp->status_check)) {
        $resp->status_check = 'inactive';
    }
    if ($resp->status_check != 'active' && $_SERVER['REQUEST_URI'] != '/wp-admin/admin.php?page=posttoinstagram-validation') {
        if (header('Location:' . get_site_url() . '/wp-admin/admin.php?page=posttoinstagram-validation')) {
            exit;
        }
    }
}
define('PTI_POSTTOINSTAGRAM_SLUG', 'posttoinstagram');
define('PTI_POSTTOINSTAGRAM_VERSION', '1.6.7');
define('PTI_POSTTOINSTAGRAM_FILE', __FILE__);
define('PTI_POSTTOINSTAGRAM_PATH', plugin_dir_path(__FILE__));
define('PTI_POSTTOINSTAGRAM_URL', plugin_dir_url( __FILE__ ));
define('PTI_POSTTOINSTAGRAM_PLUGIN_SLUG', plugin_basename( __FILE__ ));
define('PTI_POSTTOINSTAGRAM_TEXTDOMAIN', 'posttoinstagram');
define('PTI_POSTTOINSTAGRAM_API_URL', PTI_POSTTOINSTAGRAM_URL . 'api/');
$PTI_POSTTOINSTAGRAM_defaults = array(	
	'username' => '',
	'hashtag' => '',
	'lang' => 'en',
	'show_heading' => 'true',
	'scroll' => 'true',
	'width' => '270px',
	'height' => '350px',
	'image_size' => 'medium',
	'bg_color' => '#285989',
	'content_bg_color' => '#f8f8f8',
	'font_color' => '#ffffff',
	'ban' => '',
	'cache_media_time' => '0'
);
require_once(PTI_POSTTOINSTAGRAM_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'posttoinstagram-admin.php')));
require_once(PTI_POSTTOINSTAGRAM_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'posttoinstagram-shortcode.php')));
require_once(PTI_POSTTOINSTAGRAM_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'posttoinstagram-vc.php')));
require_once(PTI_POSTTOINSTAGRAM_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'posttoinstagram-widget.php')));
require_once(PTI_POSTTOINSTAGRAM_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'posttoinstagram-lib.php')));
require_once 'instagram-api/Instagram.php';
$posttoInstagram = new PosttoInstagram();
$posttoInstagram->init();
class PosttoInstagram {
    CONST woo_domain = 'www.wpcloudapp.com';
    CONST plugin_id = 'Post To Instagram';
    
    protected $pageName = 'post-to-instagram.php';
    protected $settingsGroup = 'instagram_auto_poster_settings';
    protected $domain = 'post-to-instagram';
    
    public function init() {
        load_plugin_textdomain($this->domain, false, dirname(plugin_basename(__FILE__)) . '/languages');
           
        add_action('admin_head', array($this, 'admincss'));
        add_action('admin_head', array($this, 'adminjs'));
        add_action('admin_menu', array($this, 'plugin_options'));
        add_action('admin_init', array($this, 'register_settings'));
        
        add_filter('publish_post', array($this, 'onPostSave'));
        add_filter('publish_product', array($this, 'onPostSave'));
        
        add_action('wp_ajax_instagram_send', array($this, 'ajaxSend'));
        
        add_action('add_meta_boxes', array($this, 'addMetaBox'));
        add_action('instagram_scheduled', array($this, 'publishToInstagram'), 10, 3);
        
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'settings_link'));
        if ($this->getOption('columns') == "on") {
            add_filter('manage_edit-post_columns', array($this, 'add_column'), 4);
            add_filter('manage_edit-product_columns', array($this, 'add_column'), 4);
        
            add_filter('manage_posts_custom_column', array($this, 'fill_column'), 5, 2);
            add_filter('manage_products_custom_column', array($this, 'fill_column'), 5, 2);
        }
    }
    
    public function settings_link($links) {
        $links[] = '<a href="'. esc_url(get_admin_url(null, 'admin.php?page=post-to-instagram.php')) .'">Settings</a>';
        return $links;
    }
    
    protected function getOption($name) {
        $options = get_option($this->settingsGroup);
        if (empty($options) || !is_array($options)) {
            return null;
        }
        return $options[$name];
    }
    
    /**
     * Send requests to woocommerce API manager
     * @param string $key
     * @param string $email
     * @param string $request
     * @return mixed
     */
    static function woocommerce_woo_api($key, $email, $request) {
        $email_enc = urlencode($email);
        $instance = md5($key . $email . $_SERVER['SERVER_NAME']);
        $plugin_id = urlencode(self::plugin_id);
        $url = "http://" . self::woo_domain . "/?wc-api=am-software-api&request={$request}&email={$email_enc}&licence_key={$key}&product_id={$plugin_id}&instance={$instance}&platform={$_SERVER['SERVER_NAME']}";
        $resp = file_get_contents($url);
        $resp = json_decode($resp);
        return $resp;
    }
    
    public function plugin_options() {
        add_submenu_page(
			'posttoinstagram',
            __('Settings - Post To Instagram', $this->domain),
            __('PTI Poster', $this->domain),
            'manage_options',
            $this->pageName,
            array($this, 'plugin_options_page')
        );
        add_submenu_page(
            'posttoinstagram',  __( 'Options', $this->domain ), __( 'Options', $this->domain ), 'manage_options', 'posttoinstagram-validation', array( $this, 'plugin_validation_page' ));
    }
    
    public function plugin_validation_page() {
            //activate/deactivate API key
        if ($_POST) {
            if ($_POST['status_check'] == 'active') {
                $woo_request = 'activation';
            } else {
                $woo_request = 'deactivation';
            }
            $resp = self::woocommerce_woo_api(trim($_POST['post-to-instagram_woocommerce_api_key']), trim($_POST['post-to-instagram_woocommerce_api_email']), $woo_request);
            if (isset($resp->error)) {
                $notices[] = array('message' => 'Error: ' . $resp->error . ' Code: ' . $resp->code, 'class' => 'notice notice-error is-dismissible');
            } else {
                if ($_POST['status_check'] == 'active') {
                    $notices[] = array('message' => 'Successfully activated', 'class' => 'notice notice-success is-dismissible');
                } else {
                    $notices[] = array('message' => 'Successfully deactivated', 'class' => 'notice notice-success is-dismissible');
                }
            }
            update_option('post-to-instagram_woocommerce_api_key', trim($_POST['post-to-instagram_woocommerce_api_key']));
            update_option('post-to-instagram_woocommerce_api_email', trim($_POST['post-to-instagram_woocommerce_api_email']));
        }
        $resp = self::woocommerce_woo_api(get_option('post-to-instagram_woocommerce_api_key'), get_option('post-to-instagram_woocommerce_api_email'), 'status');
        if (isset($resp->status_check)) {
            $status_check = $resp->status_check;
        } else {
            $status_check = '';
        }
        ?>
        <div class="wrap woocommerce">
            <h2><?php _e("Options", $this->domain); ?></h2>
            <?php
            if (isset($notices)):
                foreach ($notices as $notice):
                    ?>
                    <div class='<?= $notice['class']; ?>'>
                        <p><?= $notice['message']; ?></p>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
            <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                <input type="hidden" name="fp_poster_options_nonce" id="fp_poster_options_nonce" value="<?php echo wp_create_nonce('fp_poster_options_nonce'); ?>" />
                <table class="form-table">
                    <tr>
                        <th scope="row">
                        </th>
                        <td>
                            Get an API key: <a target="_blank" href="https://www.wpcloudapp.com/shop/wordpress-plugins/post-instagram-api-plugin/">link</a>
                        </td> 
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="post-to-instagram_woocommerce_api_key">API License Key</label>
                        </th>
                        <td>
                            <?php if ($status_check == 'active'): ?>
                                <?php echo get_option('post-to-instagram_woocommerce_api_key'); ?>
                                <input id="post-to-instagram_woocommerce_api_key" type="hidden" name="post-to-instagram_woocommerce_api_key" value="<?php echo get_option('post-to-instagram_woocommerce_api_key'); ?>"/>
                            <?php else: ?>
                                <input id="post-to-instagram_woocommerce_api_key" type="text" name="post-to-instagram_woocommerce_api_key" value="<?php echo get_option('post-to-instagram_woocommerce_api_key'); ?>"/>
                            <?php endif; ?> 
                        </td> 
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="post-to-instagram_woocommerce_api_email">API License Email</label>
                        </th>
                        <td> 
                            <?php if ($status_check == 'active'): ?>
                                <?php echo get_option('post-to-instagram_woocommerce_api_email'); ?>
                                <input id="post-to-instagram_woocommerce_api_email" type="hidden" name="post-to-instagram_woocommerce_api_email" value="<?php echo get_option('post-to-instagram_woocommerce_api_email'); ?>"/>
                            <?php else: ?>
                                <input id="post-to-instagram_woocommerce_api_email" type="text" name="post-to-instagram_woocommerce_api_email" value="<?php echo get_option('post-to-instagram_woocommerce_api_email'); ?>"/>
                            <?php endif; ?>
                        </td> 
                    </tr> 
                    <tr>
                        <th scope="row">
                            <label for="post-to-instagram_woocommerce_api_key">Activation status</label>
                        </th>
                        <td>
                            <?= $status_check; ?>
                        </td> 
                    </tr>
                </table>
                <p class="submit">
                    <?php if ($status_check == 'active'): ?>
                        <input type="submit" class="button-primary" value="<?php _e('Deactivate plugin') ?>" />
                        <input type="hidden" name="status_check" value="inactive" />
                    <?php else: ?>
                        <input type="submit" class="button-primary" value="<?php _e('Activate plugin') ?>" />
                        <input type="hidden" name="status_check" value="active" />
                    <?php endif; ?>
                </p>
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="page_options" value="post-to-instagram_woocommerce_api_key" />
            </form>	
        </div>
        <?php
    }
    
    public function plugin_options_page() {
        echo '
        <div class="wrap">
            <h2>' . __('Post To Instagram', $this->domain) . '</h2>
            <p>' . __('The plugin allows to publish in your account instagram your posts.', $this->domain) . '</p>
            
            <form method="post" enctype="multipart/form-data" action="options.php">
        ';
                
        echo settings_fields($this->settingsGroup);
        echo do_settings_sections($this->pageName);
        
        echo '  <p class="submit">
                    <input type="submit" class="button-primary" value="' . __('Save Changes', $this->domain) . '" />
                </p>
            </form>
        </div>
        ';
    }
    
    public function register_settings() {
        register_setting($this->settingsGroup, $this->settingsGroup, array($this, 'true_validate_settings'));
        add_settings_section('instagram_section', __('Settings', $this->domain), '', $this->pageName);
        
        //login
        add_settings_field(
            'login',
            __('Instagram Login', $this->domain),
            array($this, 'display_input_field'),
            $this->pageName,
            'instagram_section',
            array(
                'type'      => 'text',
                'id'        => 'login',
                'required'  => 'required="required"',
                'desc'      => '',
                'label_for' => 'login'
            )
        );
        
        //passw
        add_settings_field(
            'passw',
            __('Instagram Password', $this->domain),
            array($this, 'display_input_field'),
            $this->pageName,
            'instagram_section',
            array(
                'type'      => 'password',
                'id'        => 'passw',
                'required'  => 'required="required"',
                'desc'      => '',
                'label_for' => 'passw'
            )
        );
        
        //status
        add_settings_field(
            'status',
            __('How would you like to post to Instagram?', $this->domain),
            array($this, 'display_input_field'),
            $this->pageName,
            'instagram_section',
            array(
                'type' => 'radio',
                'id'   => 'status',
                'vals' => array(
                    'on' => __('Send automatically when published.', $this->domain),
                    'off' => __('Do not send automatically.', $this->domain),
                )
            )
        );
        
        //make
        add_settings_field(
            'make',
            __('If publishing automatically, how would you like it executed?', $this->domain),
            array($this, 'display_input_field'),
            $this->pageName,
            'instagram_section',
            array(
                'type' => 'radio',
                'id'   => 'make',
                'vals' => array(
                    'off' => __('Use built in WP Cron for scheduling.', $this->domain),
                    'on' => __('Publish Immediately', $this->domain),
                )
            )
        );
        
        //columns
        add_settings_field(
            'columns',
            __('Display status column in Posts and Products list page?', $this->domain),
            array($this, 'display_input_field'),
            $this->pageName,
            'instagram_section',
            array(
                'type' => 'radio',
                'id'   => 'columns',
                'vals' => array(
                    'on' => __('Enable', $this->domain),
                    'off' => __('Disable', $this->domain),
                )
            )
        );
        
        //shortlink
        add_settings_field(
            'shortlink',
            __('Use a link shortening service?', $this->domain),
            array($this, 'display_input_field'),
            $this->pageName,
            'instagram_section',
            array(
                'type' => 'radio',
                'id'   => 'shortlink',
                'vals' => array(
                    'on' => __('Enable', $this->domain),
                    'off' => __('Disable', $this->domain),
                )
            )
        );
        
        //htags
        add_settings_field(
            'format',
            __('Instagram Post Format', $this->domain),
            array($this, 'display_input_field'),
            $this->pageName,
            'instagram_section',
            array(
                'type' => 'textarea',
                'id'   => 'format',
                'desc'      => implode("<br>", array(
                    "<b>{TITLE}</b> - Inserts the Title of the post",
                    "<b>{URL}</b> - Inserts the URL of the post",
                    "<b>{EXCERPT}</b> - Inserts the excerpt of the post",
                    "<b>{TAGS}</b> - Inserts post tags",
                    "<b>{CATS}</b> - Inserts post categories",
                    "<b>{HCATS}</b> - Inserts post categories as hashtags",
                    "<b>{HTAGS}</b> - Inserts post tags as hashtags",
                    "<b>{AUTHORNAME}</b> - Inserts the author's name",
                    "<b>{SITENAME}</b> - nserts the the Blog/Site name"
                )),
                'label_for' => 'format'
            )
        );
    }
        
    public function true_validate_settings($input) {
        foreach ($input as $k => $v) {
            $valid_input[$k] = trim($v);
        }
        return $valid_input;
    }
    
    public function addMetaBox() {
        $screens = array('post', 'product');
        foreach ($screens as $screen) {
            add_meta_box('instagram_box', __('Post To Instagram', $this->domain), array($this, 'metaBoxGetPrintCheckResults'), $screen, 'side', 'high');
        }
    }
    
    public function metaBoxGetPrintCheckResults() {
        global $post;
        
        wp_nonce_field(plugin_basename(__FILE__), 'boom_noncename');
        
        $status = get_post_meta($post->ID, "instagram-send", true);
        if (is_array($status) && isset($status['status']) && $status['status'] == 'fail') {
            echo '<div class="instagram_date"></div>';
            echo '<span class="instagram_send button" data-id="'.$post->ID.'">'.__('Send/Resend to Instagram', $this->domain).'</span>';
            echo '<div class="instagram_result">'.$status['message'].'</div>';
        } elseif ((is_array($status) && isset($status['status']) && $status['status'] == 'ok') || $status == "1") {
            echo '<div class="instagram_date">' . $this->getPostedDate($post->ID) . '</div>';
            echo '<span class="instagram_send button" data-id="'.$post->ID.'">'.__('Send/Resend to Instagram', $this->domain).'</span>';
            echo '<div class="instagram_result"></div>';
        } elseif ($status == "sending") {
            echo '<div class="instagram_result">'.__('Sending...', $this->domain).'</div>';
        } elseif ($status == "auth-error") {
            echo '<div class="instagram_date"></div>';
            echo '<span class="instagram_send button" data-id="'.$post->ID.'">'.__('Send/Resend to Instagram', $this->domain).'</span>';
            echo '<div class="instagram_result">'.__('Authorisation error', $this->domain).'</div>';
        } elseif ($status == "image-error") {
            echo '<div class="instagram_date"></div>';
            echo '<span class="instagram_send button" data-id="'.$post->ID.'">'.__('Send/Resend to Instagram', $this->domain).'</span>';
            echo '<div class="instagram_result">'.__('No thumbnail', $this->domain).'</div>';
        } elseif ($status == "not-sent") {
            echo '<div class="instagram_date"></div>';
            echo '<span class="instagram_send button" data-id="'.$post->ID.'">'.__('Send/Resend to Instagram', $this->domain).'</span>';
            echo '<div class="instagram_result">'.__('Not sent to instagram', $this->domain).'</div>';
        } else {
            echo '<div class="instagram_date"></div>';
            echo '<span class="instagram_send button" data-id="'.$post->ID.'">'.__('Send/Resend to Instagram', $this->domain).'</span>';
            echo '<div class="instagram_result">'.__('Has Not Been Posted to Instagram', $this->domain).'</div>';
        }
    }
    
    public function add_column($columns) {
        $arr = array();
        $index = 0;
        
        foreach ($columns as $key => $title) {
            if ($index == 2) {
                $arr['instagram'] = 'Post To Instagram';
            }
            
            $arr[$key] = $title;
            
            $index++;
        }
        return $arr;
    }
    
    public function fill_column($column_name, $post_id) {
        if ($column_name != 'instagram') {
            return;
        }
        
        $status = get_post_meta($post_id, "instagram-send", true);
        
        echo '<span class="instagram">';
        if (is_array($status) && isset($status['status']) && $status['status'] == 'fail') {
            echo "Error: <strong>" . $status['message'] . "</strong>";
        } elseif ((is_array($status) && isset($status['status']) && $status['status'] == 'ok') || $status == "1") {
            echo $this->getPostedDate($post_id);
        } elseif ($status == "sending") {
            echo "Sending...";
        } elseif ($status == "auth-error") {
            echo "Error: <strong>Authorisation error</strong>";
        } elseif ($status == "image-error") {
            echo "Error: <strong>No thumbnail</strong>";
        } else {
            echo "Not yet sent...";
        }
        echo '</span>';
    }
    
    public function getPostedDate($post_id) {
        $time = get_post_meta($post_id, 'instagram-time', 1);
        $code = get_post_meta($post_id, 'instagram-code', 1);
                
        return '<p><a href="https://www.instagram.com/p/'.$code.'/" target="_blank">' . __('Posted on', $this->domain) . ' ' . '('.date("d.m.Y H:i", $time).')</a></p>';
    }
    
    public function onPostSave($post_id) {
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }
        
        if (get_post_type($post_id) != "post" && get_post_type($post_id) != "product") {
            return;
        }
        
        $isSending = $this->isSending($post_id);
        
        if ($this->getOption('make') == "on") {
            if (!$isSending) {
                $this->publishToInstagram($post_id);
                return;
            }
        }
        
        if ($this->getOption('status') == "on") {
            if (!$isSending) {
                update_post_meta($post_id, "instagram-send", "sending");
                wp_schedule_single_event(time() + 1, 'instagram_scheduled', array($post_id));
            }
        } else {
            update_post_meta($post_id, "instagram-send", "not-sent");
        }
    }
    
    public function isSending($post_id) {
        $status = get_post_meta($post_id, "instagram-send", true);
        
        if ((is_array($status) && isset($status['status']) && $status['status'] == 'ok') || $status == "1") {
            return true;
        }
        
        return false;
    }
    
    public function ajaxSend() {
        $post_id = $_POST['post_id'];
        
        $result = $this->publishToInstagram($post_id);
        
        if ($result === true) {
            echo json_encode(array(
                'status' => true,
                'html' => $this->getPostedDate($post_id)
            ));
        } else {
            echo json_encode(array(
                'status' => false,
                'error' => $result
            ));
        }
        wp_die();
    }
    
    public function publishToInstagram($post_id) {
        $login = $this->getOption('login');
        $passw = $this->getOption('passw');
        
        if (empty($login) || empty($passw)) {
            update_post_meta($post_id, "instagram-send", "auth-error");
            return __('Authorisation error', $this->domain);
        }
              
        $Instagram = new Instagram($login, $passw, false);
        
        try {
            $Instagram->login();
        } catch (InstagramException $e) {
            update_post_meta($post_id, "instagram-send", "auth-error");
            return __('Authorisation error', $this->domain);
        }
        
        try {
            $timestamp = time() + get_option('gmt_offset') * 3600;
                                    
            $image = $this->createJPG($post_id); #false or path
            $caption = $this->getCaption($post_id);
            
            if ($image === false) {
                update_post_meta($post_id, "instagram-send", "image-error");
                return __('No thumbnail', $this->domain);
            }
            
            $result = $Instagram->uploadPhoto($image, $caption);
            if ($result['status'] == 'ok') {
                update_post_meta($post_id, "instagram-send", $result);
                update_post_meta($post_id, "instagram-time", $timestamp);
                update_post_meta($post_id, "instagram-code", $result['media']['code']);
                return true;
            } else {
                update_post_meta($post_id, "instagram-send", $result);
            }
            
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            update_post_meta($post_id, "instagram-exception", $e->getMessage());
        }
    }
    
    public function getImagePath($post_id) {
        return get_attached_file(get_post_thumbnail_id($post_id));
    }
    
    public function createJPG($post_id) {
        $path = $this->getImagePath($post_id);
        if (!$path) {
            return false;
        }
        $imagedata = getimagesize($path);
        
        $width  = $imagedata[0];
        $height = $imagedata[1];
        $type = $imagedata['mime'];
              
        $tmp_image = imagecreatetruecolor($width, $height);
        
        if ($type == "image/jpeg") {
            $original_image = imagecreatefromjpeg($path);
        } elseif ($type == "image/png") {
            $original_image = imagecreatefrompng($path);
        }
        
        $tmp_dir = get_temp_dir() . basename($path);
        
        imagecopyresampled($tmp_image, $original_image, 0, 0, 0, 0, $width, $height, $width, $height);
        imagejpeg($tmp_image, $tmp_dir, 100);
        
        return $tmp_dir;
    }
    
    public function getCaption($post_id) {        
        $format = $this->getOption('format');
        
        $data = array(
            '{TITLE}' => $this->getParam($this->entity_decode(get_the_title($post_id))),
            '{URL}' => $this->getParam($this->getUrl($post_id)),
            '{EXCERPT}' => $this->getParam($this->getExcerpt($post_id)),
            '{TAGS}' => $this->getParam($this->getTags($post_id)),
            '{CATS}' => $this->getParam($this->getCategory($post_id)),
            '{HCATS}' => $this->getParam($this->getHcats($post_id)),
            '{HTAGS}' => $this->getParam($this->getHtags($post_id)),
            '{AUTHORNAME}' => $this->getParam(get_the_author_meta('display_name', get_post_field('post_author', $post_id))),
            '{SITENAME}'  => $this->getParam(get_bloginfo('name'))
        );
        
        $search  = array();
        $replace = array();
        
        foreach ($data as $key => $val) {
            $search[]  = $key;
            $replace[] = $val;
        }
        
        return str_replace($search, $replace, $format);
    }
    
    public function getUrl($post_id) {        
        return $this->getOption('shortlink') == "on" ? wp_get_shortlink($post_id) : get_permalink($post_id);
    }
    
    public function getExcerpt($post_id) {
        global $post;  
        
        $save_post = $post;
        $post = get_post($post_id);
        setup_postdata($post);
        $output = $this->entity_decode(get_the_excerpt());
        $post = $save_post;
        return trim($this->strip_all_shortcodes($output));
    }
    
    public function entity_decode($text) {
        return html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    }
    
    public function strip_all_shortcodes($text){
        $text = preg_replace("/\[[^\]]+\]/", '', $text);  #strip shortcode
        return $text;
    }
    
    public function getParam($value) {
        return $value ? $value : '';
    }
    
    public function getTags($post_id) {
        $arr = array();
        $tags = get_the_tags($post_id);
        
        if ($tags) {
            foreach ($tags as $tag) {
                $arr[] = $tag->name;
            }
        }
        
        return implode(', ', $arr);
    }
    
    public function getCategory($post_id) {
        $arr = array();
        $category = get_the_category($post_id);
        
        if ($category) {
            foreach ($category as $tag) {
                $arr[] = $tag->name;
            }
        }
        
        return implode(', ', $arr);
    }
    
    public function getHcats($post_id) {
        $arr = array();
        $category = get_the_category($post_id);
        
        if ($category) {
            foreach ($category as $cat) {
                $arr[] = mb_strtolower(str_replace(array(' ', '-'), '', $cat->name));
            }
        }
        
        $htags = "";
        foreach ($arr as $tag) {
            $htags .= ", #" . $tag;
        }
        return trim(trim($htags, ','));
    }
    
    public function getHtags($post_id) {
        $arr = array();
        $tags = get_the_tags($post_id);
        
        if ($tags) {
            foreach ($tags as $tag) {
                $arr[] = mb_strtolower(str_replace(array(' ', '-'), '', $tag->name));
            }
        }
        
        $htags = "";
        foreach ($arr as $tag) {
            $htags .= ", #" . $tag;
        }
        
        return trim(trim($htags, ','));
    }
    
    public function admincss() {
        echo <<<HTML
            <style type='text/css'>
                .instagram_result {
                    font-size: 16px;
                    margin: 10px 0 0 0;
                }
            </style>
HTML;
    }
    public function adminjs() {
        echo '
            <script type="text/javascript">
            jQuery(document).ready(function($) {
    
                jQuery(".instagram_send").bind("click", function(){
                    var post_id = jQuery(this).data("id");
                    var data = {
                        action: "instagram_send",
                        post_id: post_id
                    };
    
                    jQuery(".instagram_result").html("'.__('Sending...', $this->domain).'");
                    jQuery.post(ajaxurl, data, function(json) {
                        if (json.status) {
                            jQuery(".instagram_date").html(json.html);
                            jQuery(".instagram_result").html("'.__('Successfully sent', $this->domain).'");
                        } else {
                            if (json.error) var error = json.error;
                            if (json.error.message) var error = json.error.message;
                            jQuery(".instagram_result").html("'.__('Error:', $this->domain).' " + error);
                        }
                    }, "json");
                });
                
            });
            </script>
        ';
    }
    
    public function display_input_field($args) {
        extract( $args );
        
        $option = $this->getOption($id);
        switch ( $type ) {
            case 'text':
                echo sprintf(
                    "<input class='regular-text' type='text' id='%s' required='required' name='%s[%s]' value='%s' />", $id, $this->settingsGroup, $id, esc_attr(stripslashes($option))
                );
                echo $desc ? "<br /><span class='description'>$desc</span>" : "";
                break;
            case 'password':
                echo sprintf(
                    "<input class='regular-text' type='password' id='%s' required='required' name='%s[%s]' value='%s' />", $id, $this->settingsGroup, $id, esc_attr(stripslashes($option))
                );
                echo $desc ? "<br /><span class='description'>$desc</span>" : "";
                break;
            case 'textarea':
                echo sprintf(
                    "<textarea class='code large-text' cols='50' rows='10' type='text' id='%s' name='%s[%s]'>%s</textarea>", $id, $this->settingsGroup, $id, esc_attr(stripslashes($option))
                );
                echo $desc ? "<br /><span class='description'>$desc</span>" : "";
                break;
            case 'checkbox':
                $checked = ($option == 'on') ? " checked='checked'" :  '';
                echo sprintf(
                    "<label><input type='checkbox' id='%s' name='%s[%s]' %s /> ", $id, $this->settingsGroup, $id, $checked
                );
                echo $desc ? $desc : "";
                echo "</label>";
                break;
            case 'select':
                echo sprintf(
                    "<select id='%s' name='%s[%s]'>", $id, $this->settingsGroup, $id
                );
                foreach($vals as $v => $l){
                    $selected = ($option == $v) ? "selected='selected'" : '';
                    echo "<option value='$v' $selected>$l</option>";
                }
                echo $desc ? $desc : "";
                echo "</select>";
                break;
            case 'radio':
                echo "<fieldset>";
                foreach($vals as $v=>$l){
                    $checked = ($option == $v || !$option) ? "checked='checked'" : '';
                    echo sprintf(
                        "<label><input type='radio' name='%s[%s]' value='%s' %s />%s</label><br />", $this->settingsGroup, $id, $v, $checked, $l
                    );
                }
                echo "</fieldset>";
                break;
        }
    }
}
?>