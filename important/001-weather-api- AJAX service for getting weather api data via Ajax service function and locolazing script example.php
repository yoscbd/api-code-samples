<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

// create admin ajax action for client side api requests:
add_action('wp_ajax_weather_api', 'weather_handler');
add_action('wp_ajax_nopriv_weather_api', 'weather_handler');
//$selected_location = ;
$selected_location = "london,uk";

function weather_handler()
{
    check_ajax_referer('reg-nonce', 'nonce');
    $params = array(
        'location' => isset($_POST['selectedlocation']) ? $_POST['selectedlocation'] : "london,uk",
    );

    // build the URL for wp_remote_get() function
    $forecast = wp_remote_get(add_query_arg(array(
        'q' => $params['location'], // City,Country code
        'APPID' => '016a21ad4aca7fca4e3224e7ca18393a', // do not forget to set your API key here
        'units' => 'metric', // if I want to show temperature in Degrees Celsius
    ), 'http://api.openweathermap.org/data/2.5/weather'));

    if (!is_wp_error($forecast) && wp_remote_retrieve_response_code($forecast) == 200) {
        $forecast = json_decode(wp_remote_retrieve_body($forecast));
        $forecast_str = '<h3>Weather Forcat for ' . $forecast->name . '</h3> ';
        $forecast_str .= '<p>' . $forecast->main->temp . ' °С </p> ';
        $forecast_str .= '<p>Humidity: ' . $forecast->main->humidity . ':</p> ';
        $forecast_str .= '<p>Wind speed: ' . $forecast->wind->speed . 'KM </p>';
        $forecast_str .= '<p class="credit">Data source: <a href="https://openweathermap.org/api">openweathermap</a> </p>';
        //   $forecast_str .= '<p class="cta"> <button id="weather-cta">update data</button> </p>';
        /*    $forecast_str .= '<select name="cities" id="cities">
        <option value="tel-aviv,il">Tel-Aviv</option>
        <option value="london,uk">London</option>
        <option value="paris,fr">Paris</option>
        </select>'; */

        //icon:
        $forecast_icon = $forecast->weather[0]->icon;
        $icon_url = 'http://openweathermap.org/img/wn/' . $forecast_icon . '@2x.png';
        $icon_img = '<img src="' . $icon_url . '" loading="lazy">';

        $obj_to_return = '<div class="weather"><div class="forcast">' . $forecast_str . '</div>';
        $obj_to_return .= '<div class="icon">' . $icon_img . '</div></div>';
        echo $obj_to_return;
        wp_die();
    } else {

        echo 'error establishing connection to openweathermap.org, please try later';
        wp_die();
    }

}

/************* Important note for implementaion *****************

Dont forget to locolize the main script for the plugin\theme
 and add the "nonce" over there like so

**/

//1. THIS will add the script we would like to use and later locolize
function wpdocs_theme_name_scripts() {
	wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );


//2. THIS is how we locolize the script and add php variables that will be acceable form js:

  wp_localize_script('understrap-scripts', 'understrap_script_vars', array( //  wp_localize_script('<SCRIPT NAME AS REGISTERED BY WP_ENVOKE>', '<MAIN VARIABLE NAME THAT HOLD ALL OTHER VARS>'
            'alert' => __('Hey2! You have clicked the button!', 'pippin'), // this is just a sample
            'ajax_url' => admin_url('admin-ajax.php'), // get the sjax url for ajax service function
            'nonce' => wp_create_nonce('reg-nonce'), // create the noce to validate any data submited to our site
            'root_url' => get_site_url(), // site url to be used later both in localhost and live env
        )
        );