<?php


//example 1: wordpress gutenberg register custom image size + add rest api support for image including _embed: true / _embedded
function images_sizes_setup(){

       // First we'll add support for featured images
        add_theme_support('post-thumbnails');

        // Then we'll add our 2 custom images
        add_image_size('ybd_img_1', 100, 100, true);
        add_image_size('ybd_img_2', 100, 100);


}



add_action('after_setup_theme', 'images_sizes_setup');




// Also if we are using custom post we should ask 
rest api to also provide the __embed in the args
when fetching data using getEntityRecords:



select('core').getEntityRecords('postType', 'post', {
                per_page: numberOfPosts,
                _embed: true,
                order,
                orderby: orderBy,
                categories: catIDs,
            });

//example 1 END.



