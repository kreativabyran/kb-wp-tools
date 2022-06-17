<?php
/**
 * Setup environment variables.
 *
 * @package kreativabyran/kb-wp-tools
 */

namespace KB_WP_Tools;

use KB_WP_Tools\Helper\Files;
use KB_WP_Tools\Helper\Setup;

/**
 * Class Env
 */
class Env {

	/**
	 * Setup env.
	 */
	public static function setup():void {
		if ( ! class_exists( '\Dotenv\Dotenv' ) ) {
			return;
		}

		if ( ! Files::maybe_create_core_file_from_template( Files::get_base_path(), '.env' ) ) {
			return;
		}

		$dotenv = \Dotenv\Dotenv::createImmutable( Files::get_base_path() );
		$dotenv->load();
	}


	/**
	 * Get specific config element.
	 *
	 * @param   string   $key            Name of specific config element to get.
	 * @param   mixed $default_value     A value to use if env var isn't set. If this is null and key isn't set an error is thrown.
	 *
	 * @return  mixed                    Env element.
	 */
	public static function get( string $key, $default_value ) {
		Setup::maybe( array( 'Env' ) );

		$key = strtoupper( $key );

		return $_ENV[ $key ] ?? $default_value;
	}

	public static function get_all():array {
		return $_ENV;
	}

	/**
	 * Shorthand to get bool value for env var.
	 *
	 * @param string $key             See function get() above.
	 * @param   mixed  $default_value   See function get() above.
	 *
	 * @return  bool                    Bool value of env var.
	 */
	public static function get_bool( string $key = '', $default_value = null ): bool {
		$env = self::get( $key, $default_value );

		if ( is_string( $env ) ) {
			$env = strtolower( $env );
		}

		if (
			1 === $env ||
			true === $env ||
			'1' === $env ||
			'true' === $env ||
			'yes' === $env ||
			'on' === $env
		) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Shorthand for getting debug env vars, defaulting to false.
	 *
	 * @param string $key              Debug variable (without 'DEBUG_' prefix).
	 * @param   mixed  $default_value  See function get() above.
	 *
	 * @return  bool                   See function get() above.
	 */
	public static function get_debug( string $key, $default_value = false ): bool {
		return self::get_bool( 'DEBUG_' . $key, $default_value );
	}
}
