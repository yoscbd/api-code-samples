<?php
/*
Plugin Name: My Subscription Form
Plugin URI: http://danielpataki.com
Description: 2. After converting to OOP, first version.
Author: Daniel Pataki
Author URI: http://danielpataki.com
Version: 1.0.1
 */

$config = array(
    'api_key' => 'cd64539dd19283cdcc637f2ccddcd45-us6',
);

// create our main class:
class MySubscriptionForm
{
    public $api_key;

/*
1. Our constractor funtion:
A. fetch the api_key from our config array

B. Add our content filter, ***notice that  when you use hooks in classes you can’t simply specify the name of the function,
you need to use the array( $this, 'name_of_function' )

C. Optional example of adding a filter we created later in this code that allow us to add form fields, in this case "age" field
see $msf_fields = apply_filters('msf/form_fields', $defaults); later on when we fetch the fields array called "$defaults" and allow it to be
modified with add_filter()

D. Register the scripts\style we need, ***notice we just registering them and not enqueueing them yet, that will be done later on in the code in
so we can call \ enqueue the registered scripts\styles only where they are nedded.

E. Register our Ajax functions for logged-in & logged-out users

 */

    public function __construct($config)
    {
        $this->api_key = $config['api_key']; //A
        add_filter('the_content', array($this, 'form')); //B
        //add_filter('msf/form_fields', array($this, 'age_field_extension')); //C
        add_action('wp_enqueue_scripts', array($this, 'assets')); //D
        add_action('wp_ajax_msf_form_submit', array($this, 'submissionHandler')); //E logged in
        add_action('wp_ajax_nopriv_msf_form_submit', array($this, 'submissionHandler')); //E Logged-out
    }

    public function form($content)
    {
        if (!is_singular()) {
            return $content;
        }

        wp_enqueue_style('msf-style'); // this is where we enqueue the style\script we have registered in secion 1-D at the __construct funciton

        $nonce_field = wp_nonce_field('msf_form_submit', 'msf_nonce', true, false);

        if (!empty($_GET['msf_success'])) {
            $msf_display = '<div class="msf-success">Thanks for subscribing</div>';
        } else {

            // We have crating a form fields array named "defaults", we will use it later to actually add fields to the form
            $defaults = array(
                'email' => '<input type="email" placeholder="Email" required="required" name="email">',
            );

            // This is where we add a filter that will allow us or other developer to add new fields to the "defaults" arary:
            $msf_fields = apply_filters('msf/form_fields', $defaults);

            // Creating the form HTML,** Notice the use of the implode() function that adds all form fields from our "defaults" array
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

    //This is an optional example of using the filter we have created to add an "age" field to the form we call it on section 1-C in the __construct function,
    // $fields will fetch the "defaults" array and we will push our new "age" input to the fields\default array:
    public function age_field_extension($fields)
    {
        // print_r($fields);
        $fields[] = '<input type="text" placeholder="Age" name="age">';
        echo "<hr/>";
        // print_r($fields);
        return $fields;
    }

    public function submissionHandler()
    {
        if (!empty($_POST['name']) || !isset($_POST['msf_nonce']) || !wp_verify_nonce($_POST['msf_nonce'], 'msf_form_submit')
        ) {
            die();
            return;
        }

        wp_remote_post('https://us6.api.mailchimp.com/3.0/lists/bbcd6546db/members', array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode('mywebsite' . ':' . $this->api_key),
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

}

new MySubscriptionForm($config);