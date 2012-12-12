<?php

namespace RevelstokeWeather;

/**
 * Widgets class for displaying Revelstoke Weather in the sidebar
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

if ( ! class_exists( 'Widget_Sidebar_Weather' ) ) :

    class Widget_Sidebar_Weather extends \WP_Widget {

        /**
         * Register the widget with WordPress.
         */
        public function __construct() {
            parent::__construct(
                'revelstoke_sidebar_weather',
                'Revelstoke Weather',
                array( 'description' => __( 'Revelstoke Weather and Forecast', 'revelstoke_weather') ) );
        }

        /**
         * Back-end widget form
         *
         * @param array $instance
         * @return string|void
         */
        public function form( $instance ) {
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = __( 'Revelstoke Weather', 'revelstoke_weather' );
            }
            ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance
         * @param array $old_instance
         *
         * @return array|void
         */
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = strip_tags( $new_instance['title'] );

            return $instance;
        }

        /**
         * Front-end display of the widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args
         * @param array $instance
         */
        public function widget( $args, $instance ) {
            extract( $args);

            $title = apply_filters( 'widget_title', $instance['title'] );

            echo $before_widget;

            if ( ! empty( $title ) )
                echo $before_title . $title . $after_title;

            echo __( $this->get_revelstoke_sidebar_weather(), 'revelstoke_weather' );

            echo $after_widget;
        }

        /**
         * Get the xml data feed and parse the data and return an html object
         *
         * @return string
         */
        private function get_revelstoke_sidebar_weather() {
            // get the weather forecast from Environment Canada
            $wx = App::load_ec('BC/s0000679_e.xml');

            $html = '';
            $html .= '<div class="revelstoke-weather-forecast-container">';
            $html .= '<div class="revelstoke-weather-station">' . $wx->currentConditions->station . '</div>';
            $html .= '<div class="revelstoke-weather-time">' . $wx->dateTime[1]->textSummary . '</div>';

            $html .= '<div class="revelstoke-weather-sky">';

            // check if there is an icon code
            if ( $wx->currentConditions->iconCode == '' ) {
                $html .= '<div class="revelstoke-weather-large-icon"><img src="' . REVELSTOKE_WEATHER_LARGE_ICONS . '29.png" width="93" height="93" /></div>';
            } else {
                $html .= '<div class="revelstoke-weather-large-icon"><img src="' . REVELSTOKE_WEATHER_LARGE_ICONS . $wx->currentConditions->iconCode .'.png" width="93" height="93" /></div>';
            }

            $html .= '<div class="revelstoke-weather-temp">'. $wx->currentConditions->temperature . '&deg;C</div>';
            $html .= '<div class="clear"></div>';
            $html .= '<div class="revelstoke-weather-sky-text">' . $wx->currentConditions->condition . '</div>';
            $html .= '</div>';
            /**
            $html .= '<p class="wx-details">Wind: ' . $wx->currentConditions->wind->speed . 'km/h' . $wx->currentConditions->wind->direction . '<br />';
            $html .= 'Dewpoint: ' . $wx->currentConditions->dewpoint . '&deg;C<br />';
            $html .= 'Barometer: ' . $wx->currentConditions->pressure . 'kPa<br />';
            $html .= 'Relative Humidity: ' . $wx->currentConditions->relativeHumidity . '%<br />';
            $html .= 'Visibility: ' . $wx->currentConditions->visibility . 'km</p></td>';
            $html .= '</tr></table>';
            */

            $html .= '<h3>Extended Forecast</h3>';

            // loop through the extended forecast
            //$forecast_node = $wx->xpath( 'forecastGroup/forecast' );
            //$forecast_count = count( $forecast_node );
            // TODO: could create a setting for how many forecasts to show
            $forecast_count = 3;

            for ( $i = 0; $i < $forecast_count; $i++ ) {
                $html .= '<div class="revelstoke-weather-extended-forecast">';
                $html .= '<p class="revelstoke-weather-forecast-period">' . $wx->forecastGroup->forecast[$i]->period . '</p>';
                $html .= '<p class="revelstoke-weather-forecast-icon"><img src="' . REVELSTOKE_WEATHER_SMALL_ICONS . $wx->forecastGroup->forecast[$i]->abbreviatedForecast->iconCode . '.png" />';
                $html .= '<span class="revelstoke-weather-forecast-text">' . $wx->forecastGroup->forecast[$i]->textSummary . '</span></p>';
                $html .= '</div>';
            }

            $html .= '</div>';

            return $html;
        }

    }

endif; // end if class_exists