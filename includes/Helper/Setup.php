<?php
/**
 * Handles setup for all classes
 *
 * @package kreativabyran/kb-wp-tools
 */

namespace KB_WP_Tools\Helper;

class Setup {

	private static array $is_setup = array();

	public static function maybe( array $classes = array() ):void {
		// Always setup helpers first.
		self::maybe_setup( 'Helper\Files' );

		foreach ( $classes as $class ) {
			self::maybe_setup( $class );
		}
	}

	protected static function maybe_setup( string $class_name ):void {
		$class = "\KB_WP_Tools\\$class_name";
		if ( class_exists( $class) && ! self::is_setup( $class_name ) ) {
			call_user_func( array( $class, 'setup' ) );
			self::$is_setup[ $class_name ] = true;
		}
	}

	private static function is_setup( $class_name ):bool {
		if ( ! isset( self::$is_setup[ $class_name ] ) ) {
			self::$is_setup[ $class_name ] = false;
		}
		return self::$is_setup[ $class_name ];
	}

}