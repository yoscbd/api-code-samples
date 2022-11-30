<?php
/*
Plugin Name: My Subscription Form
Plugin URI: http://danielpataki.com
Description: 3. final version after converting to OOP and allow the developer to chose a service provider
Author: Daniel Pataki
Author URI: http://danielpataki.com
Version: 1.0.3
 */

//1. We are creating a providers array, first item: "provider" is the one we chode to use and the
//   providers array contain the settings\data for echo provider we add, in this case mailchimp and madmimi:
$config = array(
    'provider' => 'mailchimp',
    'providers' => array(
        'mailchimp' => array(
            'api_key' => 'bdbdbdb4b4b4b4bdbdb4b4b-us6',
        ),
        'madmimi' => array(
            'api_key' => 'wiefsbjkHJBwhlabHJbflh34bwf',
        ),
    ),
);

class MySubscriptionForm
{
    // create an instance for the provider and providers array we will later use in the constractor:
    public $providers;
    public $provider;

    public function __construct($config)
    {
        // add the instances form the $config array ousite our class:
        $this->providers = $config['providers'];
        $this->provider = $config['provider'];

        // add al filters, scripts, styles and Ajax functions:
        add_filter('the_content', array($this, 'form'));
        add_action('wp_enqueue_scripts', array($this, 'assets'));
        add_action('wp_ajax_msf_form_submit', array($this, 'submissionHandler'));
        add_action('wp_ajax_nopriv_msf_form_submit', array($this, 'submissionHandler'));
    }

    public function form($content)
    {
        if (!is_singular()) {
            return $content;
        }

        wp_enqueue_style('msf-style');

        $nonce_field = wp_nonce_field('msf_form_submit', 'msf_nonce', true, false);

        if (!empty($_GET['msf_success'])) {
            $msf_display = '<div class="msf-success">Thanks for subscribing</div>';
        } else {

            $defaults = array(
                'email' => '<input type="email" placeholder="Email" required="required" name="email">',
            );

            $msf_fields = apply_filters('msf/form_fields', $defaults);

            $msf_display = '
				<h4>Get Free Awesome!</h4>
			    <form method="post" class="msf-form" action="' . admin_url('admin-ajax.php') . '">
					' . implode("", $msf_fields) . '
			        <input type="submit" value="Subscribe Now">
					<input type="text" name="name">
					<input type="hidden" name="action" value="msf_form_submit">
			        ' . $nonce_field . '
			    </form>
			';
        }

        return $content . $msf_display;
    }

    public function assets()
    {
        wp_register_style('msf-style', plugin_dir_url(__FILE__) . 'msf-styles.css');
    }

    //2. Creating a function that will handle mailchimp in case we call the mailchimp handler
    public function mailchimpHandler()
    {
        if (!empty($_POST['name']) || !isset($_POST['msf_nonce']) || !wp_verify_nonce($_POST['msf_nonce'], 'msf_form_submit')
        ) {
            die();
            return;
        }

        wp_remote_post('https://us6.api.mailchimp.com/3.0/lists/bdbbdbdbd/members', array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode('mywebsite' . ':' . $this->providers['mailchimp']['api_key']),
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

    //3. ***Here we would add another function named "madmimiHandler()" that will handle madmimi in case we call the madmimi handler - *we hanvn't included it on this code example***

    //4. Creating a form submission function that will use the provider we are chising:
    public function submissionHandler()
    {

        // Get the "provider" we set in the $config array (in our case it is set to "mailchimp")
        // Our submission handler checks if there is a method to process the action.
        // It looks for the name of the provider appended with “Handler”.
        // If the method is not found it displays a notification. Otherwise it executes the method.
        // if the selected provider is "mailchimp" it will get "mailchimp" from the "provider" array and add it the "Handler" to create the name
        // of the method we should use: "mailchimp" + "Handler" = mailchimpHandler OR
        // if we chose madmimi: "madmimi" + "Handler" = madmimiHandler:

        if (method_exists($this, $this->provider . 'Handler')) { //1  make sure we have a method for this provider such as " mailchimpHandler() ". see:https://www.php.net/manual/en/function.method-exists.php or https://electrictoolbox.com/check-class-method-exists-php/
            call_user_func(array($this, $this->provider . 'Handler')); //2 .call the method we want to use here, in our case "mailchimpHandler() " see: https://www.phptutorial.info/?call-user-func
        } else {
            echo $this->provider . 'Handler does not exist'; //3 .trow ann erro if method dosnt exsist (in our code madmimiHandler dosnt exist)
        }
    }

}

new MySubscriptionForm($config);