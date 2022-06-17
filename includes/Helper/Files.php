<?php
/**
 * Handles files and folders
 *
 * @package kreativabyran/kb-wp-tools
 */

namespace KB_WP_Tools\Helper;

use WP_Filesystem_Direct;

class Files {

	public static function setup():void {
		Files::maybe_create_core_file_from_template( Files::get_base_path(), '.htaccess' );
	}

	public static function get_base_path( ?string $sub_path = null ) {
		return $sub_path ? ABSPATH . 'kb-wp-tools/' . $sub_path . '/' : ABSPATH . 'kb-wp-tools/';
	}

	public static function get_kb_wp_tools_path() {
		return plugin_dir_path( dirname( __FILE__, 2 ) );
	}

	/**
	 * Copy file
	 *
	 * @param string    $source      Path to the source file.
	 * @param string    $destination Path to the destination file.
	 *
	 * @return bool True on success, false on failure.
	 */
	public static function copy_file( string $source, string $destination ):bool {
		if ( ! file_exists( $source ) ) {
			return false;
		}

		if ( ! file_exists( dirname( $destination ) ) ) {
			mkdir( dirname( $destination ), 0777, true );
		}

		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

		$wp_filesystem = new WP_Filesystem_Direct( null );

		return $wp_filesystem->copy( $source, $destination );
	}

	/**
	 * Copy kb-wp-tools template file.
	 *
	 * @param string    $template_file_name      Name of the template file, excluding the ".template"-part.
	 * @param string    $destination_dir         Path to the destination file.
	 *
	 * @return bool True on success, false on failure.
	 */
	public static function copy_template_file( string $template_file_name, string $destination_dir ):bool {
		$source = self::get_kb_wp_tools_path() . "template-files/$template_file_name.template";
		$destination = trailingslashit( $destination_dir ) . $template_file_name;
		return self::copy_file( $source, $destination );
	}

	/**
	 * Check if core file exists, create if not.
	 * @param string $path      Path to the file.
	 * @param string $file_name Name of the file.
	 *
	 * @return bool True if file already exists or was successfully created, false on failure.
	 */
	public static function maybe_create_core_file_from_template( string $path, string $file_name ):bool {
		if ( ! file_exists( trailingslashit( $path ) . $file_name ) ) {
			return self::copy_template_file( $file_name, $path );
		}

		return true;
	}
}