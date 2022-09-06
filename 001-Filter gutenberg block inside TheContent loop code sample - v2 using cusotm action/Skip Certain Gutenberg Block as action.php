<?php

//Filter and remove blocks from the content loop - If single block exists on page or post don't show it with the other blocks
function remove_selected_blocks($selectedBlockToRemove)
{
    global $post; // get the post global so we can later access "the_content"

    if (!empty($selectedBlockToRemove)) { // make sure we have any block to remove in our "blocks to remove" list

        //parse the blocks so they can be run through the foreach loop
        $blocks = parse_blocks(get_the_content()); // get an array of all blocks in the content - This function converts the HTML comments and markup stored in post_content into an array of parsed block objects, E.G: <!-- wp:paragraph {"fontSize":"large"} -->

        foreach ($blocks as $block) {
            //look to see if your block is in the_content -> if yes continue past it and skip it, if no then render block as normal

            if (in_array($block['blockName'], $selectedBlockToRemove)) { // is current block name in our blocks to remove array?
                continue; //skip rendering this block
            } else {
                echo render_block($block); // render the blck as it is not in '$selectedBlockToRemove' list array
            }

        }

    }
}

// this is the array list of all block we want to filter out of the content:
$selectedBlockToRemove = array(
    'core/paragraph',
    'ourplugin/weather-api-block',
    'core/buttons',

);

add_action('_ybd_remove_blocks', // adding our action hook
    function () use ($selectedBlockToRemove) { // telling our action to use our selectedBlockToRemove argument

        remove_selected_blocks($selectedBlockToRemove); // calling our "remove_selected_blocks()" funciton and pasing the argument (an array of blocks names to be removed)
    });