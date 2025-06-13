<?php
/**
 * Plugin Name: Actors Management
 * Description: A plugin to manage actors and their details.
 * Version: 1.0.0
 * Author: Mikiyas Shiferaw
 * Author URI: https://axumcode.com
 * License: GPL2
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Plugin activation hook.
function actors_management_activate() {
    // Code to run on activation.
    add_role('warehouse_staff', 'Warehouse Staff', [
        'read' => true,
        'manage_woocommerce' => true,
        'edit_shop_orders' => true,
        'view_woocommerce_reports' => true,
    ]);

    add_role('price_manager', 'Price Manager', [
        'read' => true,
        'edit_products' => true,
        'manage_product_terms' => true,
        'manage_woocommerce' => true,
    ]);

    add_role('ecommerce_manager', 'Ecommerce Manager', [
        'read' => true,
        'edit_posts' => true,
        'publish_posts' => true,
        'manage_woocommerce' => true,
        'view_woocommerce_reports' => true,
    ]);

    add_role('finance_staff', 'Finance Staff', [
        'read' => true,
        'view_woocommerce_reports' => true,
        'manage_woocommerce' => true,
    ]);

    add_role('cashier', 'Cashier', [
        'read' => true,
        'view_woocommerce_reports' => true,
    ]);
}
register_activation_hook(__FILE__, 'actors_management_activate');

// Plugin deactivation hook.
function actors_management_deactivate() {
    // Code to run on deactivation.
    remove_role('warehouse_staff');
    remove_role('price_manager');
    remove_role('ecommerce_manager');
    remove_role('finance_staff');
    remove_role('cashier');

}
register_deactivation_hook(__FILE__, 'actors_management_deactivate');

// Main plugin functionality.
function actors_management_init() {



    // Add your plugin logic here.

function log_admin_menus_and_submenus() {
    global $menu, $submenu;

    $menus = [];

    foreach ($menu as $main_item) {
        $menu_slug = $main_item[2];

        $menus[$menu_slug] = [
            'menu_title' => maybe_unserialize($main_item[0]),
            'capability' => $main_item[1],
            'menu_slug'  => $menu_slug,
            'position'   => isset($main_item[3]) ? $main_item[3] : null,
            'icon'       => isset($main_item[6]) ? $main_item[6] : null,
            'submenus'   => [],
        ];

        if (isset($submenu[$menu_slug])) {
            foreach ($submenu[$menu_slug] as $sub_item) {
                $menus[$menu_slug]['submenus'][] = [
                    'menu_title' => maybe_unserialize($sub_item[0]),
                    'capability' => $sub_item[1],
                    'menu_slug'  => $sub_item[2],
                    'position'   => isset($sub_item[3]) ? $sub_item[3] : null,
                ];
            }
        }
    }

    $json_data = json_encode($menus, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    $file_path = plugin_dir_path(__FILE__) . 'menus.json';
    file_put_contents($file_path, $json_data);
}
add_action('admin_menu', 'log_admin_menus_and_submenus', 998);


// Restrict all admin sidebar menus and add back specific ones based on user role.
function restrict_admin_menus() {
    global $menu;

    $roles= [
        'warehouse_staff',
        'price_manager',
        'ecommerce_manager',
        'finance_staff',
        'cashier'
    ];

    // If user has one of the target roles, clear all menus
    if (array_intersect($roles, (array) $user->roles)) {
        $menu = [];

    // Re-add based on role
    switch ($role) {
        case 'warehouse_staff':
            add_menu_page('WooCommerce', 'WooCommerce', 'manage_woocommerce', 'woocommerce', '', 'dashicons-cart', 55);
            add_menu_page('Orders', 'Orders', 'edit_shop_orders', 'edit.php?post_type=shop_order', '', 'dashicons-list-view', 56);
            break;

        case 'price_manager':
            add_menu_page('Products', 'Products', 'edit_products', 'edit.php?post_type=product', '', 'dashicons-products', 55);
            break;

        case 'ecommerce_manager':
            add_menu_page('Posts', 'Posts', 'edit_posts', 'edit.php', '', 'dashicons-admin-post', 5);
            add_menu_page('WooCommerce', 'WooCommerce', 'manage_woocommerce', 'woocommerce', '', 'dashicons-cart', 55);
            add_menu_page('Products', 'Products', 'edit_products', 'edit.php?post_type=product', '', 'dashicons-products', 56);
            break;

        case 'finance_staff':
            add_menu_page('WooCommerce', 'WooCommerce', 'manage_woocommerce', 'woocommerce', '', 'dashicons-cart', 55);
            add_menu_page('Orders', 'Orders', 'edit_shop_orders', 'edit.php?post_type=shop_order', '', 'dashicons-list-view', 56);
            add_menu_page('Analytics', 'Analytics', 'view_woocommerce_reports', 'wc-admin&path=/analytics/overview', '', 'dashicons-chart-bar', 57);
            break;

       case 'cashier':
            add_menu_page('Orders', 'Orders', 'edit_shop_orders', 'edit.php?post_type=shop_order', '', 'dashicons-list-view', 55);
            add_menu_page('WooCommerce', 'WooCommerce', 'view_woocommerce_reports', 'woocommerce', '', 'dashicons-cart', 56);
            break;
            
    }
}
add_action('admin_menu', 'restrict_admin_menus', 999);


    add_action('admin_menu', 'restrict_admin_menus', 999);
}
add_action('init', 'actors_management_init');