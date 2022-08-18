<?php

//Filter and remove blocks from the content loop - If single block exists on page or post don't show it with the other blocks
function remove_blocks()
{
    // Check if we're inside the main loop in a post or page
    if ((is_single() || is_page()) && in_the_loop() && is_main_query()) {
        //parse the blocks so they can be run through the foreach loop
        $blocks = parse_blocks(get_the_content()); // get an array of all blocks in the content
        foreach ($blocks as $block) {
            //look to see if your block is in the post content -> if yes continue past it and skip it, if no then render block as normal
            if ('core/paragraph' === $block['blockName']) {
                continue;
            } else {
                echo render_block($block);
            }
        }
    }
}
//add_filter('the_content', 'remove_blocks');



/** How to Use it:

 * We can use this function in several ways:
  1. by adding it inside functions.php to all site content (see line 20): "add_filter('the_content', 'remove_blocks');"
    (we can also limit it to sepsific post types or spesific  pages suce as the Front-page)
*
*
  2. By calling this function before using "the_content" inside a page template (we will NOT call it in functions.php with "add_filter"): 
  
 */
?>

<section class="hp-weather">
	<?php
		if (have_posts()) {
			while (have_posts()): the_post();
				add_filter('the_content', 'remove_blocks');
				the_content();
			endwhile;
		}
	?>

</section> 
       


