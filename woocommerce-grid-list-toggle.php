<?php
/*
Plugin Name: WooCommerce Grid / List toggle
Description: Adds a grid/list view toggle to product archives
Version: 1.2.1
Author: jameskoster
Author URI: http://jameskoster.co.uk
Requires at least: 4.0
Tested up to: 4.9.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: woocommerce-grid-list-toggle
Domain Path: /languages/
*/

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/**
	 * Localisation
	 **/
	load_plugin_textdomain( 'woocommerce-grid-list-toggle', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	/**
	 * WC_List_Grid class
	 **/
	if ( ! class_exists( 'WC_List_Grid' ) ) {

		class WC_List_Grid {

			public function __construct() {
				// Hooks
  			add_action( 'wp' , array( $this, 'setup_gridlist' ) , 20);


				// Delete old option
				delete_option( 'wc_glt_default' );

				/**
				 * Setup Customizer
				 */
 				add_action( 'customize_register', array( $this, 'wc_glt_customize' ), 10 );
			}

			/*-----------------------------------------------------------------------------------*/
			/* Class Functions */
			/*-----------------------------------------------------------------------------------*/

			/**
			 * Add the settings / controls
			 * @param  array $wp_customize settings & controls
			 * @return array               settings & controls
			 */
			public function wc_glt_customize( $wp_customize ) {
				$wp_customize->add_setting(
					'wc_glt_default_format',
					array(
						'default'           => 'grid',
						'capability'        => 'manage_woocommerce',
						'sanitize_callback' => array( $this, 'sanitize_grid_list_default' ),
					)
				);

				$wp_customize->add_control(
					'wc_glt_default_format',
					array(
						'label'       => __( 'Grid / List default', 'woocommerce-grid-list-toggle' ),
						'description' => __( 'Choose which format products should display in by default.', 'woocommerce-grid-list-toggle' ),
						'section'     => 'woocommerce_product_catalog',
						'settings'    => 'wc_glt_default_format',
						'type'        => 'select',
						'priority'    => 20,
						'choices'     => array(
							'grid'          => __( 'Grid', 'woocommerce-grid-list-toggle' ),
							'list'          => __( 'List', 'woocommerce-grid-list-toggle' ),
						),
					)
				);
			}

			/**
			 * Sanitize the grid/list default format.
			 *
			 * @param string $value 'grid', or 'list'.
			 * @return string
			 */
			public function sanitize_grid_list_default( $value ) {
				$options = array( 'grid', 'list' );

				return in_array( $value, $options, true ) ? $value : '';
			}

			// Setup
			function setup_gridlist() {
				if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
					add_action( 'wp_enqueue_scripts', array( $this, 'setup_scripts_styles' ), 20);
					add_action( 'wp_enqueue_scripts', array( $this, 'setup_scripts_script' ), 20);
					add_action( 'woocommerce_before_shop_loop', array( $this, 'gridlist_toggle_button' ), 30);
					add_action( 'woocommerce_after_shop_loop_item', array( $this, 'gridlist_buttonwrap_open' ), 9);
					add_action( 'woocommerce_after_shop_loop_item', array( $this, 'gridlist_buttonwrap_close' ), 11);
					add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_excerpt', 5);
					add_action( 'woocommerce_after_subcategory', array( $this, 'gridlist_cat_desc' ) );
				}
			}

			// Scripts & styles
			function setup_scripts_styles() {
				wp_enqueue_style( 'grid-list-layout', plugins_url( '/assets/css/style.css', __FILE__ ) );
				wp_enqueue_style( 'grid-list-button', plugins_url( '/assets/css/button.css', __FILE__ ) );
				wp_enqueue_style( 'dashicons' );
			}

			function setup_scripts_script() {
				wp_enqueue_script( 'cookie', plugins_url( '/assets/js/jquery.cookie.min.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_script( 'grid-list-scripts', plugins_url( '/assets/js/jquery.gridlistview.min.js', __FILE__ ), array( 'jquery' ) );
				add_action( 'wp_footer', array( $this, 'gridlist_set_default_view' ) );
			}

			// Toggle button
			function gridlist_toggle_button() {

				$grid_view = __( 'Grid view', 'woocommerce-grid-list-toggle' );
				$list_view = __( 'List view', 'woocommerce-grid-list-toggle' );

				$output = sprintf( '<nav class="gridlist-toggle"><a href="#" id="grid" title="%1$s"><span class="dashicons dashicons-grid-view"></span> <em>%1$s</em></a><a href="#" id="list" title="%2$s"><span class="dashicons dashicons-exerpt-view"></span> <em>%2$s</em></a></nav>', $grid_view, $list_view );

				echo apply_filters( 'gridlist_toggle_button_output', $output, $grid_view, $list_view );
			}

			// Button wrap
			function gridlist_buttonwrap_open() {
				echo apply_filters( 'gridlist_button_wrap_start', '<div class="gridlist-buttonwrap">' );
			}
			function gridlist_buttonwrap_close() {
				echo apply_filters( 'gridlist_button_wrap_end', '</div>' );
			}

			function gridlist_set_default_view() {
				$default = get_theme_mod( 'wc_glt_default_format', 'grid' );
				?>
					<script>
					if ( 'function' == typeof(jQuery) ) {
						jQuery(document).ready(function($) {
							if ($.cookie( 'gridcookie' ) == null) {
								$( 'ul.products' ).addClass( '<?php echo $default; ?>' );
								$( '.gridlist-toggle #<?php echo $default; ?>' ).addClass( 'active' );
							}
						});
					}
					</script>
				<?php
			}

			function gridlist_cat_desc( $category ) {
				global $woocommerce;
				echo apply_filters( 'gridlist_cat_desc_wrap_start', '<div itemprop="description">' );
					echo $category->description;
				echo apply_filters( 'gridlist_cat_desc_wrap_end', '</div>' );

			}
		}

		$WC_List_Grid = new WC_List_Grid();
	}
}
