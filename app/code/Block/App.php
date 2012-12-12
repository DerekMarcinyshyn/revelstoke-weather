<?php

namespace RevelstokeWeather;

/**
 * Widgets class for displaying Revelstoke Weather
 *
 * PHP version 5
 *
 * Copyright (c) 2012 Derek Marcinyshyn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    Revelstoke Weather
 * @author     Derek Marcinyshyn <derek@marcinyshyn.com>
 * @copyright  Copyright (c) 2012 Derek Marcinyshyn
 * @version    1.0
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @link       https://github.com/DerekMarcinyshyn/revelstoke-weather
 */

if ( ! class_exists( 'App' ) ) :

    class App {

        /**
         * _instance class variable
         *
         * Class instance
         *
         * @var null | object
         */
        private static $_instance = NULL;

        static function get_instance() {
            if( self::$_instance === NULL ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /**
         * Constructor
         *
         * Default constructor -- application initialization
         */
        private function __construct() {

            // register the ripper snow widget
            add_action( 'widgets_init', function(){ return register_widget( 'RevelstokeWeather\Widget_Sidebar_Weather' ); } );

            // add css
            add_action( 'init', array( $this, 'revelstoke_weather_css' ) );

            // check for update
            add_action( 'admin_init', array( $this, 'revelstoke_weather_updater' ) );
        }

        /**
         * revelstoke_weather_css function
         *
         * Add CSS rules
         */
        function revelstoke_weather_css() {
            wp_register_style( 'revelstoke-sidebar-weather-css', REVELSTOKE_WEATHER_URL . '/assets/css/revelstoke-sidebar-weather.css', false, REVELSTOKE_WEATHER_VERSION );
            wp_enqueue_style( 'revelstoke-sidebar-weather-css' );
        }

        /**
         * revelstoke_weather_updater class
         *
         * Check GitHub to see if there is an update available
         */
        function revelstoke_weather_updater() {
            define( 'REVELSTOKE_WEATHER_FORCE_UPDATE', true );

            if ( is_admin() ) {
                $config = array(
                    'slug'                  => REVELSTOKE_WEATHER_DIRECTORY . '/revelstoke-weather.php',
                    'proper_folder_name'    => 'revelstoke-weather',
                    'api_url'               => 'https://api.github.com/repos/DerekMarcinyshyn/revelstoke-weather',
                    'raw_url'               => 'https://raw.github.com/DerekMarcinyshyn/revelstoke-weather/master',
                    'github_url'            => 'https://github.com/DerekMarcinyshyn/revelstoke-weather',
                    'zip_url'               => 'https://github.com/DerekMarcinyshyn/revelstoke-weather/zipball/master',
                    'sslverify'             => false,
                    'requires'              => '3.0',
                    'tested'                => '3.5',
                    'readme'                => 'README.md',
                    'access_token'          => '',
                );

                new \Revelstoke_Weather_Updater( $config );
            }
        }

        /**
         * @param $city
         * @return \SimpleXMLElement|string
         */
        public static function load_ec( $city ) {
            $ec_url = 'http://dd.weatheroffice.gc.ca/citypage_weather/xml/' . $city;
            $ec_headers = @get_headers($ec_url);

            if ( preg_match( "|200|", $ec_headers[0] ) ) {
                $ec_weather = @simplexml_load_file( $ec_url );
                return $ec_weather;
            } else {
                return 'Sorry weather data not available.';
            }
        }
    }

endif; // end if class_exists