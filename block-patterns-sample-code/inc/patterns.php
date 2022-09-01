<?php

function jc_block_patterns()
{

    register_block_pattern(
        'jc-block-patterns/jc-content-upgrade',
        array(
            'title' => __('Content Upgrade', 'jc-block-patterns'),

            'description' => _x('A simple set of blocks to encourage people to join the membership', 'jc-block-patterns'),

            'content' => "<!-- wp:heading {\"textAlign\":\"center\"} -->\r\n<h2 class=\"has-text-align-center\">TEASER HEADING</h2>\r\n<!-- /wp:heading -->\r\n\r\n<!-- wp:paragraph {\"align\":\"center\"} -->\r\n<p class=\"has-text-align-center\">TEASER TEXT. Join the <a href=\"https://buildsomething.club\">Build Something Club</a>.</p>\r\n<!-- /wp:paragraph -->\r\n\r\n<!-- wp:image {\"align\":\"center\",\"id\":52,\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->\r\n<figure class=\"wp-block-image aligncenter size-full\"><img src=\"http://127.0.0.1/trullion/wp-content/uploads/2022/07/Group-2133.png\" alt=\"\" class=\"wp-image-52\"/></figure>\r\n<!-- /wp:image -->\r\n\r\n<!-- wp:buttons {\"align\":\"wide\",\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\",\"orientation\":\"horizontal\"}} -->\r\n<div class=\"wp-block-buttons alignwide\"><!-- wp:button -->\r\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link\" href=\"https://buildsomething.club\">Join for just $5/mo</a></div>\r\n<!-- /wp:button --></div>\r\n<!-- /wp:buttons -->\r\n\r\n<!-- wp:create-block/banner-block /-->",

            'categories' => array('buttons'),
        )
    );

}

add_action('init', 'jc_block_patterns');