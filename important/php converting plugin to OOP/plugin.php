<?php
/*
Plugin Name: My Subscription Form
Plugin URI: http://danielpataki.com
Description: 1. before converting to OOP (this is the simple version of the plugin).
Author: Daniel Pataki
Author URI: http://danielpataki.com
Version: 1.0.0
 */

// 1. Add this form to the page content using "the_content" filter:
add_filter('the_content', 'msf_post_form');
function msf_post_form($content)
{
    if (!is_singular()) { // Make sure this is a post OR a custom post page
        return $content; // if this is not a single simply return the content.
    }

    wp_enqueue_style('msf-style'); // Add the script/style to the page at this point only

    $nonce_field = wp_nonce_field('msf_form_submit', 'msf_nonce', true, false); // crate a nonce filed for verification

    if (!empty($_GET['msf_success'])) { // check if we are in the first load of the page or if this page is already submitted and contain the "msf_success" parameter
        $msf_display = '<div class="msf-success">Thanks for subscribing</div>';
    } else {
        $msf_display = '
			<h4>Get Free Awesome!</h4>
		    <form method="post" class="msf-form" action="' . admin_url('admin-ajax.php') . '">
		        <input type="email" required="required" name="email">
		        <input type="submit" value="Subscribe Now">
				<input type="text" name="name">
				<input type="hidden" name="action" value="msf_form_submit">
		        ' . $nonce_field . '
		    </form>

		';
    }

    return $content . $msf_display; // add our generated for to the end of the content.
}

//2. Register our script\style so we can enqueue it later on (see above "wp_enqueue_style('msf-style')" line 19)
add_action('wp_enqueue_scripts', 'msf_assets');
function msf_assets()
{
    wp_register_style('msf-style', plugin_dir_url(__FILE__) . 'msf-styles.css');
}

// 3. Adding our ajax functionality for logged-in \ logged-out users:
add_action('wp_ajax_msf_form_submit', 'msf_form_submit');
add_action('wp_ajax_nopriv_msf_form_submit', 'msf_form_submit');

function msf_form_submit()
{
    //Security check:
    // "name" is hidden field in the form and dosnt supposed to get any value from he user, if it contain any alue we can tell somthing fisshy is going on
    // We also check we got the nonce and that it is valid/verified
    if (!empty($_POST['name']) || !isset($_POST['msf_nonce']) || !wp_verify_nonce($_POST['msf_nonce'], 'msf_form_submit')
    ) {
        die();
        return;
    }

    // 4. Send a request to a 3rd party, in this case lets say we want to add the form email to mailchimp list
    wp_remote_post('https://us6.api.mailchimp.com/3.0/lists/bbcd6546db/members', array(
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('mywebsite' . ':' . 'cd64539dd19283cdcc637f2ccddcd45-us6'),
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode(array(
            'email_address' => $_POST['email'],
            'status' => 'subscribed',
        )),

    ));

    $url = add_query_arg('msf_success', 'true', $_SERVER['HTTP_REFERER']);
    wp_redirect($url);

    die();

}