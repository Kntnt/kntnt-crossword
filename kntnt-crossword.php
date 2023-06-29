<?php

/**
 * Plugin main file.
 * @wordpress-plugin
 * Plugin Name:       Kntnt Crossword
 * Plugin URI:        https://www.kntnt.com/
 * GitHub Plugin URI: https://github.com/Kntnt/kntnt-crossword
 * Description:       Adds a shortcode outputting a crossword created by Crossword Compiler.
 * Version:           1.0.0
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
		add_shortcode( 'crossword', [ $this, 'crossword_shortcode' ] );
	}

	public function register_resources() {
		wp_register_script( 'kntnt-crossword-raphael', plugins_url( 'vendor/CrosswordCompilerApp/raphael.js', __FILE__ ), [ 'jquery' ], '1.0.0' );
		wp_register_script( 'kntnt-crossword-crossword-compiler', plugins_url( 'vendor/CrosswordCompilerApp/crosswordCompiler.js', __FILE__ ), [ 'kntnt-crossword-raphael' ], '1.0.0' );
	}

	public function crossword_shortcode( $atts, $content, $tag ) {

		if ( $content ) {

			// Enqueue assets.
			wp_enqueue_script( 'kntnt-crossword-raphael' );
			wp_enqueue_script( 'kntnt-crossword-crossword-compiler' );

			// Variables to be used to output the crossword
			$crossword_id         = uniqid();
			$crossword_plugin_url = plugins_url( '', __FILE__ );

			// Output the crossword
			ob_start();
			include "includes/kntnt-crossword.php";
			$content = ob_get_clean();

		}

		return $content;

	}

	// A more forgiving version of WP's shortcode_atts().
	private function shortcode_atts( $pairs, $atts, $shortcode = '' ) {

		$atts = (array) $atts;
		$out  = [];
		$pos  = 0;
		while ( $name = key( $pairs ) ) {
			$default = array_shift( $pairs );
			if ( array_key_exists( $name, $atts ) ) {
				$out[ $name ] = $atts[ $name ];
			} else if ( array_key_exists( $pos, $atts ) ) {
				$out[ $name ] = $atts[ $pos ];
				++ $pos;
			} else {
				$out[ $name ] = $default;
			}
		}

		if ( $shortcode ) {
			$out = apply_filters( "shortcode_atts_{$shortcode}", $out, $pairs, $atts, $shortcode );
		}

		return $out;

	}

}
