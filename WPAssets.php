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
  private static string $handle_prefix = 'wpassets';

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
		wp_register_script( $handle, $src, $dependencies, $version, $in_footer );
  }
}
