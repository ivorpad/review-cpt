<?php

/**
 *
 * @link              ivorpad.com
 * @since             0.0.1
 * @package           Review_Cpt
 *
 * @wordpress-plugin
 * Plugin Name:       ThemeForest Proofing CPT
 * Plugin URI:        themeforest.net
 * Description:       ThemeForest Proofing Page custom plugin.
 * Version:           0.1.2
 * Author:            Ivor Padilla
 * Author URI:        ivorpad.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       envato
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Register Custom Post Type
function proofing_post_type() {

  $labels = array(
    'name'                  => _x( 'Review Snippets', 'Post Type General Name', 'envato' ),
    'singular_name'         => _x( 'Review Snippet', 'Post Type Singular Name', 'envato' ),
    'menu_name'             => __( 'Snippets', 'envato' ),
    'name_admin_bar'        => __( 'Snippets', 'envato' ),
    'archives'              => __( 'Snippets Archives', 'envato' ),
    'attributes'            => __( 'Snippets Attributes', 'envato' ),
    'parent_item_colon'     => __( 'Parent Snippet:', 'envato' ),
    'all_items'             => __( 'All Snippets', 'envato' ),
    'add_new_item'          => __( 'Add New Snippet', 'envato' ),
    'add_new'               => __( 'Add New', 'envato' ),
    'new_item'              => __( 'New Snippet', 'envato' ),
    'edit_item'             => __( 'Edit Snippet', 'envato' ),
    'update_item'           => __( 'Update Snippet', 'envato' ),
    'view_item'             => __( 'View Snippet', 'envato' ),
    'view_items'            => __( 'View Snippets', 'envato' ),
    'search_items'          => __( 'Search Snippet', 'envato' ),
    'not_found'             => __( 'Not found', 'envato' ),
    'not_found_in_trash'    => __( 'Not found in Trash', 'envato' ),
    'featured_image'        => __( 'Featured Image', 'envato' ),
    'set_featured_image'    => __( 'Set featured image', 'envato' ),
    'remove_featured_image' => __( 'Remove featured image', 'envato' ),
    'use_featured_image'    => __( 'Use as featured image', 'envato' ),
    'insert_into_item'      => __( 'Insert into item', 'envato' ),
    'uploaded_to_this_item' => __( 'Uploaded to this item', 'envato' ),
    'items_list'            => __( 'Items list', 'envato' ),
    'items_list_navigation' => __( 'Items list navigation', 'envato' ),
    'filter_items_list'     => __( 'Filter items list', 'envato' ),
  );
  $args = array(
    'label'                 => __( 'Review Snippet', 'envato' ),
    'description'           => __( 'ThemeForest Snippets', 'envato' ),
    'labels'                => $labels,
    'supports'              => array( 'title', 'editor', 'author', 'revisions', 'page-attributes', ),
    'taxonomies'            => array( 'category', ' post_tag' ),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => false,
    'can_export'            => true,
    'has_archive'           => false,   
    'exclude_from_search'   => true,
    'publicly_queryable'    => true,
    'capability_type'       => 'page',
    'show_in_rest'          => true,
    'rest_controller_class' => 'WP_REST_Posts_Controller',
  );
  register_post_type( 'themeforest_snippets', $args );

}
add_action( 'init', 'proofing_post_type', 0 );


function tf_review_snippets_taxonomy() {  
    register_taxonomy(  
        'snippet_categories', 
        'themeforest_snippets',
        array(  
            'hierarchical' => true,  
            'label' => 'Category',
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => 'themes',
                'with_front' => false
            )
        )  
    );  
}  
add_action( 'init', 'tf_review_snippets_taxonomy');

// Remove autop from the Snippets.

remove_filter('the_content','wpautop');

//decide when you want to apply the auto paragraph

add_filter('the_content','tf_review_custom_formatting');

function tf_review_custom_formatting($content){
if(get_post_type()=='themeforest_snippets') 
    return $content;//no autop
else
 return wpautop( $content );
}

// Remove 100 max per_page posts from custom endpoint.
add_filter( 'rest_endpoints', function( $endpoints ){
    if ( ! isset( $endpoints['/wp/v2/themeforest_snippets'] ) ) {
        return $endpoints;
    }
    unset( $endpoints['/wp/v2/themeforest_snippets'][0]['args']['per_page']['maximum'] );
    return $endpoints;
});
