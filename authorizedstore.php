<?php
/*
  Plugin Name: Authorized Store Seal
  Plugin URI: https://www.cminds.com/
  Author URI: https://www.cminds.com/
  Description:  Displays Authorized Store Seal (Settings required to activate).

  Author: CreativeMindsSolutions
  Text Domain: authorizedstore
  Domain Path: /languages
  Version: 1.0.0
 */

class AuthorizedStore {

	private static $instance = null;
	private $messages		 = null;

	public static function instance() {
		if ( !isset( self::$instance ) && !( self::$instance instanceof AuthorizedStore ) ) {
			self::$instance = new AuthorizedStore;
		}

		self::$instance->setup_constants();
		self::$instance->load_files();
		self::$instance->init();
		return self::$instance;
	}

	private function setup_constants() {
		/**
		 * Define Plugin File Name
		 *
		 * @since 1.0
		 */
		if ( !defined( 'AUTHSTORE_PLUGIN_FILE' ) ) {
			define( 'AUTHSTORE_PLUGIN_FILE', __FILE__ );
		}
	}

	private function load_files() {
		include_once plugin_dir_path( __FILE__ ).'widgets.php';
	}

	private function init() {
		include_once plugin_dir_path( __FILE__ ) . 'package/cminds-free.php';

		add_action( 'wp_enqueue_scripts', array( $this, 'load_script' ) );
		add_action( 'wp_head', array( $this, 'custom_style' ), 100 );
		add_action( 'wp_footer', array( $this, 'show_seal_in_corner' ), 0 );

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ), 11 );

		add_shortcode( 'authorizedstore', array( $this, 'show_seal' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
	}

	function register_widget() {
		register_widget( 'Authorizedstore_Widget' );
	}

	public function add_menu() {
		add_menu_page( 'AuthorizedStore', 'AuthorizedStore', 'manage_options', 'authorizedstore_menu', array( $this, 'show_about' ) );
	}

	public function show_about() {
		$result = self::instance()->save_options();
		include_once plugin_dir_path( __FILE__ ).'views/settings.php';
	}

	public function save_options() {
		$messages	 = '';
		$_POST		 = array_map( 'stripslashes_deep', $_POST );
		$post		 = $_POST;
		$options	 = array();

		if ( isset( $post[ "authstore_save" ] ) ) {

			unset( $post[ "authstore_save" ] );

			$test = check_admin_referer( 'update-options' );

			do_action( 'authorizedstore_save_options_before', $post, array( &$messages ) );

			foreach ( $post as $key => $value ) {
				if ( strpos( $key, 'authstore_' ) === 0 ) {
					$options[ $key ] = $value;
				}
			}

			update_option( 'authstore_options', $options, true );

			do_action( 'authorizedstore_save_options_after_on_save', $post, array( &$messages ) );
			$this->messages = __( 'Settings have been saved.', 'authorizedstore' );
		}

		do_action( 'authorizedstore_save_options_after', $post, array( &$messages ) );
		return array( 'messages' => $this->messages );
	}

	public function get_options() {
		$return = get_option( 'authstore_options', array() );
		return $return;
	}

	public function get_option( $key, $default = false ) {
		$options = $this->get_options();
		$return	 = isset( $options[ $key ] ) ? $options[ $key ] : $default;
		return $return;
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'cm-tiny-adblock-detector', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	public function load_script() {
		wp_register_script( 'cm-tiny-adblock-detector-ads', plugin_dir_url( __FILE__ ) . 'showads.js' );
		wp_enqueue_script( 'cm-tiny-adblock-detector', plugin_dir_url( __FILE__ ) . 'cm-tiny-adblock-detector.js', array( 'cm-tiny-adblock-detector-ads' ), false, true );
	}

	public function show_seal( $atts = array() ) {
		$result = '';

		$atts		 = shortcode_atts( array(), $atts, 'authorizedstore' );
		$sealCode	 = $this->get_option( 'authstore_sealCode' );

		if ( !empty( $sealCode ) ) {
			$result = $sealCode;
		}
		return $result;
	}

	public function custom_style() {
		?>
		<style>
			.show-only-when-adblock{display:none}
			.hide-only-when-adblock{display:block}
		</style>
		<?php
	}

	public function adblock_detected_style() {
		?>
		<style>
			.show-only-when-adblock{display:initial}
			.hide-only-when-adblock{display:none}
		</style>
		<?php
	}

	public function show_seal_in_corner() {
		$sealInCornerEnabled = $this->get_option( 'authstore_sealPosition' );
		if ( $sealInCornerEnabled && $sealInCornerEnabled !== 'none' ) {

			switch ( $sealInCornerEnabled ) {
				case 'top-right':
					$additional_style = 'top:0;right:0;';
					if ( is_admin_bar_showing() ) {
						$additional_style = 'top:32px;right:0;';
					}
					break;
				case 'bottom-right':
					$additional_style	 = 'bottom:0;right:0;';
					break;
				case 'bottom-left':
					$additional_style	 = 'bottom:0;left:0;';
					break;
				case 'top-left':
					$additional_style	 = 'top:0;left:0;';
					if ( is_admin_bar_showing() ) {
						$additional_style = 'top:32px;left:0;';
					}
					break;
				default:
					break;
			}
			?>
			<div style="position: fixed; z-index: 99999; <?php echo $additional_style; ?>">
				<?php echo $this->show_seal(); ?>
			</div>
			<?php
		}
		echo do_shortcode( '[cminds_free_author id="authorizedstore"]' );
	}

}

function AuthorizedStoreInit() {
	if ( !class_exists( 'AuthorizedStore' ) ) {
		return;
	}
	return AuthorizedStore::instance();
}

add_action( 'plugins_loaded', 'AuthorizedStoreInit' );
