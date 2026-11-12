<?php
/**
 * Plugin Name: Marrison Addon JetEngine
 * Plugin URI: https://github.com/marrisonlab/marrison-addon-jetengine
 * Description: Modular extensions for JetEngine listings, queries, dynamic fields, and WooCommerce products.
 * Version: 1.0.1
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Angelo Marra
 * Author URI: https://marrisonlab.com
 * Text Domain: marrison-addon-jet
 * Domain Path: /languages
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Update URI: https://github.com/marrisonlab/marrison-addon-jetengine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Marrison_Addon_Jet {
	const VERSION = '1.0.1';
	const OPTION = 'marrison_addon_jet_modules';
	const PAGE = 'marrison_addon_jet';

	private const MODULES = [
		'listing_title'            => [ 'listing-title', 'Listing_Title', 'Elementor' ],
		'listing_columns'          => [ 'listing-columns', 'Listing_Columns', 'Elementor' ],
		'query_fallback'            => [ 'query-fallback', 'Query_Fallback', 'Elementor' ],
		'dynamic_svg'               => [ 'dynamic-svg', 'Dynamic_SVG', '' ],
		'recently_viewed_products'  => [ 'recently-viewed-products', 'Recently_Viewed_Products', 'WooCommerce' ],
		'read_more'                 => [ 'read-more', 'Read_More', 'Elementor' ],
		'ticker'                    => [ 'ticker', 'Ticker', 'Elementor' ],
	];

	private $started = [];

	public function boot() {
		// Register the bundled catalogs before JetEngine's early init.
		add_action( 'init', [ $this, 'load_translations' ], -1000 );
		add_action( 'elementor/loaded', [ $this, 'boot_modules' ] );
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
		add_action( 'admin_post_marrison_addon_jet_save', [ $this, 'save_modules' ] );
		add_action( 'update_option_' . self::OPTION, [ $this, 'modules_updated' ], 10, 2 );
		add_action( 'add_option_' . self::OPTION, [ $this, 'modules_added' ], 10, 2 );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'settings_link' ] );
		$this->boot_modules();
	}

	public function load_translations() {
		load_plugin_textdomain( 'marrison-addon-jet', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function boot_modules() {
		$enabled = get_option( self::OPTION, [] );
		foreach ( self::MODULES as $id => [ $file, $class, $dependency ] ) {
			if ( empty( $enabled[ $id ] ) || isset( $this->started[ $id ] ) || $this->unavailable_reason( $id, $dependency ) ) {
				continue;
			}
			require_once __DIR__ . '/includes/modules/class-marrison-addon-' . $file . '.php';
			$class = 'Marrison_Addon_Jet_' . $class;
			$this->started[ $id ] = true;
			new $class();
		}
	}

	private function unavailable_reason( $id, $dependency ) {
		if ( ! self::is_jet_engine_active() ) {
			return 'JetEngine';
		}
		if ( 'Elementor' === $dependency && ! did_action( 'elementor/loaded' ) && ! class_exists( '\\Elementor\\Plugin' ) ) {
			return 'Elementor';
		}
		if ( 'WooCommerce' === $dependency && ! self::is_woocommerce_active() ) {
			return 'WooCommerce';
		}
		// Both editions retain the original widget, macro, and callback IDs.
		if ( is_callable( [ 'Marrison_Addon', 'is_module_enabled' ] ) && Marrison_Addon::is_module_enabled( $id ) ) {
			return 'original';
		}
		return '';
	}

	private function module_labels() {
		return [
			'listing_title' => [ __( 'Listing Grid Title', 'marrison-addon-jet' ), __( 'Add a title and style controls to the JetEngine Listing Grid.', 'marrison-addon-jet' ) ],
			'listing_columns' => [ __( 'Listing Columns', 'marrison-addon-jet' ), __( 'Let visitors choose the number of columns in a Listing Grid.', 'marrison-addon-jet' ) ],
			'query_fallback' => [ __( 'Query Fallback', 'marrison-addon-jet' ), __( 'Use a second Posts Query when the main Listing Grid query returns too few items.', 'marrison-addon-jet' ) ],
			'dynamic_svg' => [ __( 'Dynamic SVG', 'marrison-addon-jet' ), __( 'Render SVG media inline through JetEngine Dynamic Field callbacks.', 'marrison-addon-jet' ) ],
			'recently_viewed_products' => [ __( 'Recently Viewed Products', 'marrison-addon-jet' ), __( 'Use recently viewed WooCommerce product IDs in a JetEngine query.', 'marrison-addon-jet' ) ],
			'read_more' => [ __( 'Read More', 'marrison-addon-jet' ), __( 'Add an expandable excerpt to JetEngine Dynamic Field and Elementor Text Editor widgets.', 'marrison-addon-jet' ) ],
			'ticker' => [ __( 'Content Ticker', 'marrison-addon-jet' ), __( 'Display scrolling content from a JetEngine query or manually entered items.', 'marrison-addon-jet' ) ],
		];
	}

	public function register_menu() {
		add_menu_page(
			__( 'Marrison Addon JetEngine', 'marrison-addon-jet' ),
			__( 'Marrison Addon JetEngine', 'marrison-addon-jet' ),
			'manage_options',
			self::PAGE,
			[ $this, 'render_settings' ],
			plugins_url( 'assets/icon.svg', __FILE__ )
		);
	}

	public function settings_link( $links ) {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'admin.php?page=' . self::PAGE ) ) . '">' . esc_html__( 'Settings', 'marrison-addon-jet' ) . '</a>' );
		return $links;
	}

	public function render_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$enabled = get_option( self::OPTION, [] );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Marrison Addon JetEngine', 'marrison-addon-jet' ); ?></h1>
			<?php if ( isset( $_GET['save_failed'] ) ) : ?>
				<div class="notice notice-error"><p><?php esc_html_e( 'Module settings could not be saved.', 'marrison-addon-jet' ); ?></p></div>
			<?php elseif ( isset( $_GET['updated'] ) ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Module settings saved.', 'marrison-addon-jet' ); ?></p></div>
			<?php endif; ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="marrison_addon_jet_save">
				<?php wp_nonce_field( 'marrison_addon_jet_save' ); ?>
				<table class="widefat striped">
					<thead><tr><th scope="col"><?php esc_html_e( 'Module', 'marrison-addon-jet' ); ?></th><th scope="col"><?php esc_html_e( 'Description', 'marrison-addon-jet' ); ?></th></tr></thead>
					<tbody>
					<?php foreach ( $this->module_labels() as $id => [ $title, $description ] ) : ?>
						<?php $reason = $this->unavailable_reason( $id, self::MODULES[ $id ][2] ); ?>
						<tr>
							<th scope="row"><label><input type="checkbox" name="modules[]" value="<?php echo esc_attr( $id ); ?>" <?php checked( ! empty( $enabled[ $id ] ) ); ?> <?php disabled( '' !== $reason && empty( $enabled[ $id ] ) ); ?>> <?php echo esc_html( $title ); ?></label></th>
							<td>
								<?php echo esc_html( $description ); ?>
								<?php if ( $reason ) : ?>
									<p class="description">
									<?php
									if ( 'original' === $reason ) {
										esc_html_e( 'This module is running in the original Marrison Addon. Disable it there to use it here.', 'marrison-addon-jet' );
									} else {
										/* translators: %s: required plugin name. */
										echo esc_html( sprintf( __( 'Requires an active installation of %s.', 'marrison-addon-jet' ), $reason ) );
									}
									?>
									</p>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	public function save_modules() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to manage these settings.', 'marrison-addon-jet' ) );
		}
		check_admin_referer( 'marrison_addon_jet_save' );
		$selected = isset( $_POST['modules'] ) && is_array( $_POST['modules'] ) ? wp_unslash( $_POST['modules'] ) : [];
		$old = get_option( self::OPTION, [] );
		$enabled = [];
		foreach ( self::MODULES as $id => [ , , $dependency ] ) {
			if ( in_array( $id, $selected, true ) && ( ! empty( $old[ $id ] ) || ! $this->unavailable_reason( $id, $dependency ) ) ) {
				$enabled[ $id ] = 1;
			}
		}
		$saved = $enabled === $old || update_option( self::OPTION, $enabled ) || $enabled === get_option( self::OPTION );
		wp_safe_redirect( admin_url( 'admin.php?page=' . self::PAGE . ( $saved ? '&updated=1' : '&save_failed=1' ) ) );
		exit;
	}

	public function modules_added( $option, $value ) {
		$this->modules_updated( [], $value );
	}

	public function modules_updated( $old, $value ) {
		foreach ( self::MODULES as $id => [ , , $dependency ] ) {
			if ( ( 'Elementor' === $dependency || 'dynamic_svg' === $id ) && empty( $old[ $id ] ) !== empty( $value[ $id ] ) ) {
				self::clear_elementor_cache();
				return;
			}
		}
	}

	public static function clear_elementor_cache() {
		if ( class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}
	}

	public static function plugin_file() {
		return __FILE__;
	}

	public static function asset_version( $path ) {
		$file = __DIR__ . '/' . $path;
		return defined( 'WP_DEBUG' ) && WP_DEBUG && is_file( $file ) ? self::VERSION . '.' . filemtime( $file ) : self::VERSION;
	}

	public static function is_jet_engine_active() {
		return function_exists( 'jet_engine' ) || class_exists( 'Jet_Engine' );
	}

	public static function is_woocommerce_active() {
		return class_exists( 'WooCommerce' ) || function_exists( 'WC' );
	}
}

add_action( 'plugins_loaded', [ new Marrison_Addon_Jet(), 'boot' ] );
register_deactivation_hook( __FILE__, [ 'Marrison_Addon_Jet', 'clear_elementor_cache' ] );

require_once __DIR__ . '/includes/class-marrison-addon-jet-updater.php';
new Marrison_Addon_Jet_Updater( __FILE__ );
