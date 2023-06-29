<?php

/**
 * Plugin main file.
 * @wordpress-plugin
 * Plugin Name:       Kntnt Crossword
 * Plugin URI:        https://www.kntnt.com/
 * GitHub Plugin URI: https://github.com/Kntnt/kntnt-crossword
 * Description:       Adds a shortcode outputting a crossword created by Crossword Compiler.
 * Version:           1.1.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Kntnt\Crossword;

defined( 'WPINC' ) && new Plugin;

class Plugin {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_resources' ] );
		add_filter( 'no_texturize_shortcodes', [ $this, 'no_texturize_crossword_shortcode' ] );
		add_shortcode( 'crossword', [ $this, 'crossword_shortcode' ] );
	}

	public function register_resources() {
		wp_register_script( 'kntnt-crossword-raphael', plugins_url( 'vendor/CrosswordCompilerApp/raphael.js', __FILE__ ), [ 'jquery' ], '1.1.0' );
		wp_register_script( 'kntnt-crossword-crossword-compiler', plugins_url( 'vendor/CrosswordCompilerApp/crosswordCompiler.js', __FILE__ ), [ 'kntnt-crossword-raphael' ], '1.1.0' );
	}

	public function no_texturize_crossword_shortcode( $shortcodes ) {
		$shortcodes[] = 'crossword';
		return $shortcodes;
	}

	public function crossword_shortcode( $atts, $content, $tag ) {

		if ( $content ) {

			// Enqueue assets.
			wp_enqueue_script( 'kntnt-crossword-raphael' );
			wp_enqueue_script( 'kntnt-crossword-crossword-compiler' );

			// Variables to be used to output the crossword
			$crossword_id = uniqid();
			$crossword_data = self::decode_and_escape( $content );
			error_log( $crossword_data );
			$crossword_plugin_url = plugins_url( '', __FILE__ );

			// Output the crossword
			ob_start();
			include "includes/kntnt-crossword.php";
			$content = ob_get_clean();

		}

		return $content;

	}

	private static function decode_and_escape( $string ) {
		$string = html_entity_decode( $string, ENT_XML1 | ENT_QUOTES | ENT_SUBSTITUTE );
		$string = str_replace( [ '\\', '"', "'", '&' ], [ '\\\\', '\"', "\'", '&amp;', ], $string );
		return $string;
	}

}
