<?php
/**
 * Plugin Name: Actors Management
 * Description: A plugin to manage actors and their details.
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL2
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Plugin activation hook.
function actors_management_activate() {
    // Code to run on activation.

}
register_activation_hook(__FILE__, 'actors_management_activate');

// Plugin deactivation hook.
function actors_management_deactivate() {
    // Code to run on deactivation.
}
register_deactivation_hook(__FILE__, 'actors_management_deactivate');

// Main plugin functionality.
function actors_management_init() {
    // Add your plugin logic here.
    // Restrict all admin sidebar menus and add back specific ones based on user role.
    function restrict_admin_menus() {
        global $menu;

        // Clear all menus.
        $menu = [];

        // Add back specific menus based on user role.
        if (current_user_can('store_manager')) { // Replace 'administrator' with the role you want to allow access.
            add_menu_page('Dashboard', 'Dashboard', 'read', 'index.php', '', 'dashicons-dashboard', 2);
            add_menu_page('Posts', 'Posts', 'edit_posts', 'edit.php', '', 'dashicons-admin-post', 5);
        }
    }
    add_action('admin_menu', 'restrict_admin_menus', 999);
}
add_action('init', 'actors_management_init');