<?php

// we call this file via the "ourRenderCallback" function in functions.php line 185, it allow us to use our attributes and render the php and html for the client side:
// we used the same HTML from the editor but we change all jsx syntax to php, e.g "className" in jsx =>to "class" is php
if (!$attributes['imgURL']) {
    $attributes['imgURL'] = get_theme_file_uri('/images/library-hero.jpg');
}

?>

<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url('<?php echo $attributes['imgURL'] ?>')"></div>
    <div class="page-banner__content container t-center c-white">
        <?php echo $content; // echo the content of the nested blocks inside our banner block ("genericbutton.js","genericheading.js")  ?>
    </div>
</div>