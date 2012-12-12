<?php
/**
 * @package Revelstoke Weather
 * @since   December 11, 2012
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 */
/*
Plugin Name: Revelstoke Weather
Plugin URI: https://github.com/DerekMarcinyshyn/revelstoke-weather
Description: Revelstoke Weather is a WordPress plugins displaying Revelstoke weather from Environment Canada.
Author: Derek Marcinyshyn
Author URI: http://derek.marcinyshyn.com
Version: 1.0
License: GPLv2

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Exit if called directly
defined( 'ABSPATH' ) or die( "Cannot access pages directly." );

// Plugin version
define( 'REVELSTOKE_WEATHER_VERSION', '1.0');

// Plugin
define( 'REVELSTOKE_WEATHER_PLUGIN', __FILE__ );

// Plugin directory
define( 'REVELSTOKE_WEATHER_DIRECTORY', dirname( plugin_basename(__FILE__) ) );

// Plugin path
define( 'REVELSTOKE_WEATHER_PATH', WP_PLUGIN_DIR . '/' . REVELSTOKE_WEATHER_DIRECTORY );

// App path
define( 'REVELSTOKE_WEATHER_APP_PATH', REVELSTOKE_WEATHER_PATH . '/app' );

// Lib path
define( 'REVELSTOKE_WEATHER_LIB_PATH', REVELSTOKE_WEATHER_PATH . '/lib' );

// URL
define( 'REVELSTOKE_WEATHER_URL', WP_PLUGIN_URL . '/' . REVELSTOKE_WEATHER_DIRECTORY );

// Icons
define( 'REVELSTOKE_WEATHER_LARGE_ICONS', REVELSTOKE_WEATHER_URL . '/assets/img/icons-large/' );
define( 'REVELSTOKE_WEATHER_SMALL_ICONS', REVELSTOKE_WEATHER_URL . '/assets/img/icons-small/' );


// Require main class
require_once( REVELSTOKE_WEATHER_APP_PATH . '/code/Block/App.php' );

// Require widgets class
require_once( REVELSTOKE_WEATHER_APP_PATH . '/code/View/Widget_Sidebar_Weather.php' );

// Require updater class
include_once( REVELSTOKE_WEATHER_LIB_PATH . '/vendor/updater/updater.php' );

// ====================================
// = Initialize and setup application =
// ====================================

global  $revelstoke_weather_app;

// Main class app initialization in App::__construct()
use RevelstokeWeather\App;
$revelstoke_weather_app = App::get_instance();