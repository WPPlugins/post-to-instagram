<?php 

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();

// delete plugin options
delete_option('posttoinstagram_purchase_code');
delete_option('posttoinstagram_activated');
delete_option('posttoinstagram_latest_version');
delete_option('posttoinstagram_last_check_datetime');
delete_option('posttoinstagram_activation_message');
delete_option('post-to-instagram_woocommerce_api_key');
delete_option('post-to-instagram_woocommerce_api_email');

?>