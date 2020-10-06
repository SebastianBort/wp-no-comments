<?php
/*
Plugin Name: Wyłączenie systemu komentarzy 
Description: Całkowicie wyłącza natywne komentarze z silnika Wordpress, w optymalny i lekki sposób.
Version: 1.0.0
Author: Sebastian Bort
*/

class WP_No_Comments {

        public function __construct() {
                
                add_action('admin_init', [$this, 'remove_builtin_functions'], 11);
                add_action('admin_menu', [$this, 'remove_from_admin_menu']);                
                add_action('init', [$this, 'remove_from_admin_bar']);
        
                add_filter('comments_open', '__return_false', 20, 2);
                add_filter('pings_open', '__return_false', 20, 2);                    
                add_filter('comments_array', function($comments, $post_id) { return []; }, 10, 2);
        }

        public function remove_builtin_functions() {
              
              foreach(get_post_types() AS $post_type) {
                  remove_post_type_support($post_type, 'comments');
                  remove_post_type_support($post_type, 'trackbacks');
              }
              
              remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
              
              global $pagenow;
              if($pagenow === 'edit-comments.php') {
                  wp_redirect(admin_url());
                  exit;
              }
        }

        public function remove_from_admin_menu() {
              
                remove_menu_page('edit-comments.php');
        }
        
        public function remove_from_admin_bar() {
              
              if(is_admin_bar_showing()) {
                  remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
              }
        }
}

new WP_No_Comments();

?>