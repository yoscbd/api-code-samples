<?php
/**
 * Plugin Name: Weather Shortcode Plugin

 * Description: ybd plugin .
 * Version: 1.0
 * Author: ybd

 */

add_shortcode('weather', 'ybd_weather_shortcode'); // [weather location="London,uk"]

function ybd_weather_shortcode($atts)
{
    $params = shortcode_atts(array( // if you need a default value, set it here
        'location' => 'london,uk',
    ), $atts);

    $cache_key = 'ybd_forecast5';
    $forecast_str = get_transient($cache_key);

    // if no data in the cache
    // if ($forecast_str === false) {

    // build the URL for wp_remote_get() function
    $forecast = wp_remote_get(add_query_arg(array(
        'q' => $params['location'], // City,Country code
        'APPID' => '016a21ad4aca7fca4e3224e7ca18393a', // do not forget to set your API key here
        'units' => 'metric', // if I want to show temperature in Degrees Celsius
    ), 'http://api.openweathermap.org/data/2.5/weather'));

    if (!is_wp_error($forecast) && wp_remote_retrieve_response_code($forecast) == 200) {

        //  print_r($forecast); // use it to see all the returned values!

        $forecast = json_decode(wp_remote_retrieve_body($forecast));
        $forecast_str = '<h3>Weather Forcat for ' . $forecast->name . '</h3> ';
        $forecast_str .= '<p>' . $forecast->main->temp . ' °С </p> ';
        $forecast_str .= '<p>Humidity: ' . $forecast->main->humidity . ':</p> ';
        $forecast_str .= '<p>Wind speed: ' . $forecast->wind->speed . 'KM </p>';
        $forecast_str .= '<p class="credit">Data source: <a href="https://openweathermap.org/api">openweathermap</a> </p>';
        // $forecast_str .= '<p class="cta"> <button id="weather-cta">update data</button> </p>';
        /*        $forecast_str .= '<select name="cities" id="cities">
        <option value="tel-aviv,il">Tel-Aviv</option>
        <option value="london,uk">London</option>
        <option value="paris,fr">Paris</option>
        </select>'; */
        //icon:

        $forecast_icon = $forecast->weather[0]->icon;
        $icon_url = 'http://openweathermap.org/img/wn/' . $forecast_icon . '@2x.png';
        $icon_img = '<img src="' . $icon_url . '" loading="lazy">';

/*         echo '<pre>';
var_dump($forecast);
echo '</pre>'; */

        set_transient($cache_key, $forecast_str, 0); // 2 hours cache
    } else {

        return; // you can use print_r( $forecast ); here for debugging
    }

    // }

    //Generate the html for displaying weather forcast:

//Generate the html for displaying weather forcast:

    $obj_to_return = '<div class="weather"><div class="forcast">' . $forecast_str . '</div>';
    $obj_to_return .= '<div class="icon">' . $icon_img . '</div></div>';
    return $obj_to_return;
}