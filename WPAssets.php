<?php
/**
 *  WPAssets
 *  Copyright (C) 2022  Simon Lagerlöf
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
namespace WPAssets;

/**
 * WP Assets.
 * 
 * Class for managing WordPress theme or plugin assets.
 *
 * @package WPAssets
 * @author Simon Lagerlöf <contact@smn.codes>
 * @link https://gitlab.com/smncd/wp-assets
 * @license GNU Public License v3 or later http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright 2022  Simon Lagerlöf
 * @version 0.1.0
 */
class WPAssets {

  /**
   * Handle prefix.
   * 
   * @var string
   */
  protected static string $handle_prefix = 'wpassets';

  /**
   * Register stylesheet.
   * 
   * @param string $handle
   * @param string $src
   * @param array|null $dependencies
   * @param string|null $version
   * @param string|null $media
   * 
   * @return void
   * 
   * @see https://developer.wordpress.org/reference/functions/wp_register_style/
   * @since @next
   */
  public static function register_style(  
    string $handle, 
    string $src, 
    array $dependencies = [], 
    string $version = null, 
    string $media = 'all' 
  ):void {
    $dependencies = self::dependencies( $src, $dependencies );

    wp_register_style( $handle, $src, $dependencies, $version, $media );
  }

  /**
   * Register script.
   * 
   * @param string $handle
   * @param string $src
   * @param array|null $dependencies
   * @param string|null $version
   * @param bool $in_footer
   * 
   * @return void
   * 
   * @see https://developer.wordpress.org/reference/functions/wp_register_script/
   * @since @next
   */
  public static function register_script(  
    string $handle, 
    string $src, 
    array|null $dependencies = [], 
    string $version = null, 
    bool $in_footer = true
  ):void {
    $dependencies = self::dependencies( $src, $dependencies );

		wp_register_script( $handle, $src, $dependencies, $version, $in_footer );
  }

  /**
   * Enqueue stylesheet.
   * 
   * @param string $handle
   * @param string $src
   * @param array|null $dependencies
   * @param string|null $version
   * @param string|null $media
   * 
   * @return void
   * 
   * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
   * @since @next
   */
  public static function enqueue_style(  
    string $handle, 
    string $src, 
    array $dependencies = [], 
    string $version = null, 
    string $media = 'all' 
  ):void {
    $handle = static::$handle_prefix . '-' . $handle;

    add_action( 
      'wp_enqueue_scripts', 
      function() use ( $handle, $src, $dependencies, $version, $media ) {
        self::register_style( $handle, $src, $dependencies, $version, $media );
		    wp_enqueue_style( $handle );
	  } 
    );
  }

  /**
   * Enqueue script.
   * 
   * @param string $handle
   * @param string $src
   * @param array|null $dependencies
   * @param string|null $version
   * @param bool $in_footer
   * 
   * @return void
   * 
   * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
   * @since @next
   */
  public static function enqueue_script(  
    string $handle, 
    string $src, 
    array|null $dependencies = [], 
    string $version = null, 
    bool $in_footer = true
  ):void {
    $handle = static::$handle_prefix . '-' . $handle;

    add_action( 
      'wp_enqueue_scripts', 
      function() use ( $handle, $src, $dependencies, $version, $in_footer ) {
        self::register_script( $handle, $src, $dependencies, $version, $in_footer );
		    wp_enqueue_script( $handle );
	  } 
    );
  }

  /**
   * Enqueue editor stylesheet.
   * 
   * @param string $handle
   * @param string $src
   * 
   * @see https://developer.wordpress.org/reference/hooks/enqueue_block_editor_assets/
   * @since @next
   */
  public static function enqueue_editor_style( string $handle, string $src ):void {
    add_action( 'enqueue_block_editor_assets', function() use ( $handle, $src ) {
      $handle = static::$handle_prefix . '-editor-' . $handle;

      self::register_style( $handle, $src );
      wp_enqueue_style( $handle );
    } );
  }

  /**
   * Enqueue editor script.
   * 
   * @param string $handle
   * @param string $src
   * 
   * @see https://developer.wordpress.org/reference/hooks/enqueue_block_editor_assets/
   * @since @next
   */
  public static function enqueue_editor_script( string $handle, string $src ):void {
    add_action( 'enqueue_block_editor_assets', function() use ( $handle, $src ) {
      $handle = static::$handle_prefix . '-editor-' . $handle;

      self::register_script( $handle, $src );
      wp_enqueue_script( $handle );
    } );
  }

  /**
   * Check for and return asset dependencies.
   * 
   * @param string $src
   * @param array|null $dependencies
   * 
   * @return array
   * 
   * @since @next
   */
  private static function dependencies( string $src, array|null $dependencies ):array {
    if (isset($dependencies) && is_array($dependencies) && isset($dependencies[0]) ) return $dependencies;

    $script_asset = self::script_asset( $src );

    return isset($script_asset['dependencies']) ? $script_asset['dependencies'] : [];
  }

  /**
   * Check for and return script asset.
   * 
   * Mainly useful for Gutenberg blocks and plugins.
   * 
   * @param string $src
   * 
   * @return array
   * 
   * @since @next
   */
  private static function script_asset( string $src ): array {
    if ( pathinfo( $src, PATHINFO_EXTENSION ) !== 'js' ) return [];

    $script_asset = pathinfo( $src, PATHINFO_DIRNAME ) . 'index.asset.php';

    if ( file_exists($script_asset) ) {
      $script_asset = require $script_asset;

      return $script_asset;
    } else {
      return [];
    }
  }
}
