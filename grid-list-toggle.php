<?php
/*
Plugin Name: WooCommerce Grid / List toggle
Description: Adds a grid/list view toggle to product archives
Version: 0.1
Author: jameskoster
Author URI: http://jameskoster.co.uk
Requires at least: 3.1
Tested up to: 3.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/**
	 * Localisation
	 **/
	load_plugin_textdomain('wc_list_grid_toggle', false, dirname( plugin_basename( __FILE__ ) ) . '/');
	
	/**
	 * WC_List_Grid class
	 **/
	if (!class_exists('WC_List_Grid')) {
	 
		class WC_List_Grid {
		
			public function __construct() { 
				// Hooks
  				add_action( 'wp' , array(&$this, 'setup_gridlist' ) , 20);
			}
			
			// Functions
			// Setup
			function setup_gridlist() {
				add_action( 'get_header', array(&$this, 'setup_scripts_styles'), 20);
				add_action( 'woocommerce_before_shop_loop', array(&$this, 'gridlist_toggle_button'), 20);
				add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_excerpt', 5);
				add_action( 'woocommerce_after_shop_loop_item', array(&$this, 'gridlist_buttonwrap_open'), 9);
				add_action( 'woocommerce_after_shop_loop_item', array(&$this, 'gridlist_buttonwrap_close'), 11);
				add_action( 'woocommerce_after_shop_loop_item', array(&$this, 'gridlist_hr'), 30);
			}
			
			// Scripts & styles
			function setup_scripts_styles() {
				if (is_shop() || is_product_category()) {
					wp_enqueue_script( 'cookie', plugins_url( '/assets/js/jquery.cookie.min.js', __FILE__ ), array( 'jquery' ) );
					wp_enqueue_script( 'grid-list-scripts', plugins_url( '/assets/js/jquery.gridlistview.min.js', __FILE__ ), array( 'jquery' ) );
					wp_enqueue_style( 'grid-list-styles', plugins_url( '/assets/css/style.css', __FILE__ ) );
				}
			}
			
			// Toggle button
			function gridlist_toggle_button() {
				?>
					<nav id="gridlist-toggle">
						<a href="#" id="grid" class="active" title="<?php _e('Grid view', 'wc_list_grid_toggle'); ?>">&#8862; <span><?php _e('Grid view', 'wc_list_grid_toggle'); ?></span></a><a href="#" id="list" title="<?php _e('List view', 'wc_list_grid_toggle'); ?>">&#8863; <span><?php _e('List view', 'wc_list_grid_toggle'); ?></span></a>
					</nav>
				<?php
			}
			
			// Button wrap
			function gridlist_buttonwrap_open() {
				?>
					<div class="gridlist-buttonwrap">
				<?php
			}
			function gridlist_buttonwrap_close() {
				?>
					</div>
				<?php
			}
			
			// hr
			function gridlist_hr() {
				?>
					<hr />
				<?php
			}
		}		
		$WC_List_Grid = new WC_List_Grid();
	}
}