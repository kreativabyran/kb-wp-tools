<?php
/**
 * Handles debugging
 *
 * @package kreativabyran/kb-wp-tools
 */

namespace KB_WP_Tools;
use KB_WP_Tools\Helper\Files;

/**
 * Debugging class
 */
class Debug {

	/**
	 * Debugging setup
	 */
	public static function setup():void {
		if ( self::is_debugging( 'SHORTCODE' ) ) {
			add_shortcode( 'kb-wp-tools', array( __CLASS__, 'shortcode' ) );
		}
	}

	/**
	 * Check if debug env var is set and truthy
	 *
	 * @param   string|null $debug_envvar  Debug env var key without DEBUG_ prefix.
	 *
	 * @return  bool                         If requested debugging is active.
	 */
	public static function is_debugging( ?string $debug_envvar ):bool {
		if ( ! isset( $debug_envvar ) ) {
			return true;
		}

		return Env::get_debug( $debug_envvar );
	}


	/**
	 * Debugging shortcode
	 *
	 * @return  string  Shortcode HTML.
	 */
	public static function shortcode():string {
		Files::maybe_create_core_file_from_template( Files::get_base_path(), 'shortcode.php' );
		ob_start();

		include_once Files::get_base_path() . 'shortcode.php';

		return ob_get_clean();
	}
}
