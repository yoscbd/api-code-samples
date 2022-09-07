<?php


//example 1: register a new field to the REST api:
//https://www.udemy.com/course/become-a-wordpress-developer-php-javascript/learn/lecture/7837914#overview
function ybd_custom_rest()
{
    register_rest_field(

        'post', 'ybdName', array('get_callback' => function () {return 'This is my amazing field';})
//    register_rest_field( <POST TYPE>, <NEW FIELD NAME>, <ARRAY THAT RETURN A FUNCTION THAT WILL GENERATE THE CONTENT FOR THE NEW FIELD> )

    );
}

add_action('rest_api_init', 'ybd_custom_rest');

//end example 1.


//example 2: register a new field to the REST api and asign the value of post auther in to it:
function ybd_custom_rest()
{
    register_rest_field(

        'post', 'ybdName', array('get_callback' => function () {return get_the_author();})
//    register_rest_field( <POST TYPE>, <NEW FIELD NAME>, <ARRAY THAT RETURN A FUNCTION THAT WILL GENERATE THE CONTENT FOR THE NEW FIELD> )

    );
}

add_action('rest_api_init', 'ybd_custom_rest');

//end example 2.


//example 3: register a new custom REST api url:

add_action('rest_api_init', 'ybdCustomRest');

function ybdCustomRest()
{
    register_rest_route('ybd/v1', 'ybdData', array(
        'methods' => WP_REST_SERVER::READABLE, // smae and bbeteer way as using 'methods' => 'GET'
        'callback' => 'ybdResults',
    ));
}

function ybdResults()
{
    // -Note that worpdress will handle the converting
    //  of our data to valid json format so we dont need to use convertion functions suce as "json_encode()"

    //   return array("Volvo", "BMW", "Toyota");

    return array(
        "dog" => "wooff",
        "cat" => "miyauu",
    );

}
//end example 3.



//example 4: register a new custom REST api url and make it return custom post data for "threats" custom post type:
//https://www.udemy.com/course/become-a-wordpress-developer-php-javascript/learn/lecture/7909620#overview Time:12:00
add_action('rest_api_init', 'ybdRegisterPostTitleAndLink');

function ybdRegisterPostTitleAndLink()
{
    register_rest_route('ybd/v1', 'threats-short-light-data', array(
        'methods' => WP_REST_SERVER::READABLE, // same and bbeteer way as using 'methods' => 'GET'
        'callback' => 'ybdResults',
    ));
}

function ybdResults()
{
    $threats = new WP_Query(array(
        'post_type' => 'threats',
    ));

    $professorResults = array(); // create an empty array so later in the while loop we can push items in to it and return it in the end

    while ($threats->have_posts()) {
        $threats->the_post(); // this line is for getting all of the thata from each post (same as $post -> the_post() in a regular wp_query loop)
        array_push($professorResults, array( // push a new array to our "professorResults" array, this result with a single "professorResults" array that will hold an array for each post.
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
        ));
    }

    return $professorResults;

}

//example 4: allow user to add url parameters for custom query for "threats" custom post type:

//A.firt we will register our custom rout:
add_action('rest_api_init', 'ybdRegisterPostTitleAndLink');

function ybdRegisterPostTitleAndLink()
{
    register_rest_route('ybd/v1', 'threats-short-light-data', array(
        'methods' => WP_REST_SERVER::READABLE, // same and bbeteer way as using 'methods' => 'GET'
        'callback' => 'ybdResults',
    ));
}


//B.second we wiil define the data we would like to retun for this routh:
function ybdResults($data) // we are including the $data object so we can use it later

{
    $threats = new WP_Query(array(
        'post_type' => 'threats',
        's' => sanitize_text_field($data['term']),  // - make sure to sanitize our retrived data for security and serach for  a parameter called "term" and pull all threats custom posts that have the term value in thier title or content
    ));

    $professorResults = array(); // create an empty array so later in the while loop we can push items in to it and return it in the end

    while ($threats->have_posts()) {
        $threats->the_post(); // this line is for getting all of the thata from each post (same as $post -> the_post() in a regular wp_query loop)
        array_push($professorResults, array( // push a new array to our "professorResults" array, this result with a single "professorResults" array that will hold an array for each post.
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
        ));
    }

    return $professorResults;

}

//example 4 end



//example 5:  get seperated orginaized results for several post types, in this case for regular posts, regular pages and threats custom post
add_action('rest_api_init', 'ybdRegisterSearch');

function ybdRegisterSearch()
{
    register_rest_route('ybd-custom-data/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'ybdSearchResults',
    ));
}

function ybdSearchResults($data)
{
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'threats'),
        's' => sanitize_text_field($data['term']),
    ));

    // here we are creating 2 empty arrays, one is for posts and pages data, the other one is for threats custom post type data
    $results = array(
        'generalInfo' => array(),
        'threats' => array(),

    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post(); //  this line is for getting all of the thata from each post (same as $post -> the_post() in a regular wp_query loop)

        // now we wiil loop trow our result and first chck what is the current post type - page \ core post \ threats custom post...
        if (get_post_type() == 'post' or get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ));
        }

        if (get_post_type() == 'threats') {
            array_push($results['threats'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ));
        }

    }

    return $results;



    // this will return with and json object with all founded data per post type,
    // we can do this: "http://127.0.0.1/cymulate/wp-json/ybd-custom-data/v1/search?term=hello%20world"
    //it will retuen this json obj:

/* {
--Our generalInfo array for post and pages:
"generalInfo": [
{
"title": "Hello world!",
"permalink": "http://127.0.0.1/cymulate/hello-world/"
},
{
"title": "test5",
"permalink": "http://127.0.0.1/cymulate/test5/"
}
],
--Our threats array for threats cusotm post type:
"threats": [
{
"title": "11Crimea Manifesto deploys VBA Rat using double attack vectors thisismyterm hello world",
"permalink": "http://127.0.0.1/cymulate/threats/11crimea-manifesto-deploys-vba-rat-using-double-attack-vectors/"
}
]
} */

}

//example 5 END


//example 6: create a dynamic routh for getting threaqts post type by thier "threat-level" taxonomy (named "Prioritize") we defined when registering our taxonomy-   (register_taxonomy('Prioritize',[...])

//A.firt we will register our custom rout:

add_action('rest_api_init', 'ybdRegisterPostTitleAndLink'); // ust the "rest_api_init" action

function ybdRegisterPostTitleAndLink()
{
    register_rest_route('ybd/v1', 'immediate-threats', array(
        'methods' => WP_REST_SERVER::READABLE, // same and bbeteer way as using 'methods' => 'GET'
        'callback' => 'ybdResults',
    ));

}

//B.second we wiil define the data we would like to retun for this routh:
function ybdResults($data) // we are including the $data object so we can use it later

{
    $threats = new WP_Query(array(
        'post_type' => 'threats',
        'tax_query' => array(
            array(
                'taxonomy' => 'Prioritize',
                'terms' => $data['threat-level'], // get the term "threat-level" parameter value entered by the user in the url (http://127.0.0.1/cymulate/wp-json/ybd/v1/immediate-threats?threat-level=5)
                'field' => 'term_id',
            ),
        ),
    ));

    $threatsResults = array(); // create an empty array so later in the while loop we can push items in to it and return it in the end

    while ($threats->have_posts()) {
        $threats->the_post(); // this line is for getting all of the thata from each post (same as $post -> the_post() in a regular wp_query loop)
        array_push($threatsResults, array( // push a new array to our "threatsResults" array, this result with a single "threatsResults" array that will hold an array for each post.
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
        ));
    }

    return $threatsResults;

}


//example 6 END


////example 7: How to properly add custom entities in Gutenberg in order to allow Gutenberg and react to use custom REST api we have created 

//php code goes here:

add_action('rest_api_init', 'ybdRegisterPostTitleAndLink');

function ybdRegisterPostTitleAndLink()
{
    register_rest_route('ybd/v2', 'post', array(
        'methods' => WP_REST_SERVER::READABLE, // same and bbeteer way as using 'methods' => 'GET'
        'callback' => 'ybdResults',
    ));
}

function ybdResults()
{

    $threats = new WP_Query(array(
        'post_type' => 'post',
    ));

    $queryResults = array(); // create an empty array so later in the while loop we can push items in to it and return it in the end

    while ($threats->have_posts()) {

        $threats->the_post();
        // this line is for getting all of the thata from each post (same as $post -> the_post() in a regular wp_query loop)
        array_push($queryResults, array( // push a new array to our "professorResults" array, this result with a single "professorResults" array that will hold an array for each post.
            'ybdtitle' => get_the_title(),
            'ybdpermalink' => get_the_permalink(),
        ));
    }

    return $queryResults;

}


// and here is the react code for our edit.js:

// add a new entetie to be later called by "getEntityRecords()":

/*     wp.data.dispatch('core').addEntities([
        {
            name: 'post',           // route name
            kind: 'ybd/v2', // namespace
            baseURL: '/ybd/v2/post', // API path without /wp-json
            key: "ybdtitle" // we must provide one uniqe key otherwise we will only get the last item value...
        }
    ]);



    const threats = useSelect(
        (select) => {

            return select('core').getEntityRecords('ybd/v2', 'post')
        },
        [numberOfPosts] // any change in the attributes listed here will triger the useSelect function
    );

    console.log(threats) */