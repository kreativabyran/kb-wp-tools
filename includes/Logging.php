<?php
/**
 * Handles debugging
 *
 * @package kreativabyran/kb-wp-tools
 */

namespace KB_WP_Tools;

use KB_WP_Tools\Helper\Files;

class Logging {

	/**
	 * Logs message to server
	 *
	 * @param   mixed  $message              Message to log.
	 * @param   string $folder               Name of folder log sub folder.
	 * @param   string $file                 Will be used to set filename.
	 * @param   null|string $debug_constant  Optional name of env var that has to be true for logging to happen.
	 */
	public static function log( $message, string $folder = 'main', string $file = 'log', ?string $debug_constant = null ):void {
		if (
			Debug::is_debugging( 'LOG' ) &&
			Debug::is_debugging( $debug_constant )
		) {
			$log_path = Files::get_base_path() . 'logs/' . $folder . '/';

			if ( ! is_dir( Files::get_base_path() . 'logs' ) ) {
				mkdir( Files::get_base_path() . 'logs', 0777, true );
			}

			if ( ! is_dir( $log_path ) ) {
				mkdir( $log_path, 0777, true );
			}

			if ( ! is_string( $message ) ) {
				$message = print_r( $message, true );
			}

			$log = wp_date( 'Y-m-d H:i:s' ) . PHP_EOL .
			       $message . PHP_EOL .
			       '-------------------------' . PHP_EOL . PHP_EOL;

			file_put_contents( $log_path . $file . '_' . wp_date( 'Y-m-d' ) . '.log', $log, FILE_APPEND );
		}
	}

	/**
	 * Log and maybe send debug message.
	 *
	 * @param   string $message          Message to log/send.
	 * @param   string $folder           Name of folder log sub folder.
	 * @param   string $file             Will be used to set filename.
	 * @param   null|string $message_subject  Email subject.
	 */
	public static function log_and_email( string $message, string $folder = 'main', string $file = 'log', ?string $message_subject = null ):void {
		self::log( $message, $folder, $file );

		$debug_email = Env::get( 'DEBUG_EMAIL', false );

		if ( $debug_email ) {
			$mail_body  = '<h3>Debug message from ' . home_url() . '</h3>';
			$mail_body .= '<p>' . $message . '</p>';
			$mail_body .= '<p>Server time: ' . wp_date( 'Y-m-d H:i:s' ) . '</p>';

			if ( empty( $message_subject ) ) {
				$message_subject = get_bloginfo( 'name' ) . ' - KB WP Tools Debug';
			}

			wp_mail(
				$debug_email,
				$message_subject,
				$mail_body,
				array( 'Content-Type: text/html; charset=UTF-8' )
			);
		}
	}
}