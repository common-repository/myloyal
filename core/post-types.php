<?php

namespace myLoyal\core;

class Post_Types {

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return ${ClassName} An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    public function __construct() {
    	add_action( 'init', [ $this, 'register_post_type_taxonomies' ] );
    }

    public function register_post_type_taxonomies() {
	    $labels = array(
		    'name'                  => _x( 'Badges', 'Post type general name', 'textdomain' ),
		    'singular_name'         => _x( 'Badge', 'Post type singular name', 'textdomain' ),
		    'menu_name'             => _x( 'Badges', 'Admin Menu text', 'textdomain' ),
		    'name_admin_bar'        => _x( 'Badge', 'Add New on Toolbar', 'textdomain' ),
		    'add_new'               => __( 'Add New', 'textdomain' ),
		    'add_new_item'          => __( 'Add New Badge', 'textdomain' ),
		    'new_item'              => __( 'New Badge', 'textdomain' ),
		    'edit_item'             => __( 'Edit Badge', 'textdomain' ),
		    'view_item'             => __( 'View Badge', 'textdomain' ),
		    'all_items'             => __( 'All Badges', 'textdomain' ),
		    'search_items'          => __( 'Search Badges', 'textdomain' ),
		    'parent_item_colon'     => __( 'Parent Badges:', 'textdomain' ),
		    'not_found'             => __( 'No badges found.', 'textdomain' ),
		    'not_found_in_trash'    => __( 'No badges found in Trash.', 'textdomain' ),
		    'featured_image'        => _x( 'Badge Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
		    'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		    'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		    'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		    'archives'              => _x( 'Badge archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
		    'insert_into_item'      => _x( 'Insert into badge', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
		    'uploaded_to_this_item' => _x( 'Uploaded to this badge', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
		    'filter_items_list'     => _x( 'Filter badges list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
		    'items_list_navigation' => _x( 'Badges list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
		    'items_list'            => _x( 'Badges list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
	    );

	    $args = array(
		    'labels'             => $labels,
		    'public'             => true,
		    'publicly_queryable' => true,
		    'show_ui'            => true,
		    'show_in_menu'       => false,
		    'query_var'          => true,
		    'rewrite'            => array( 'slug' => 'badge' ),
		    'capability_type'    => 'post',
		    'has_archive'        => true,
		    'hierarchical'       => false,
		    'menu_position'      => null,
		    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	    );

	    register_post_type( 'loyal_badge', $args );

	    $labels = array(
		    'name' => _x( 'Achievements', 'taxonomy general name' ),
		    'singular_name' => _x( 'Achievement', 'taxonomy singular name' ),
		    'search_items' =>  __( 'Search Achievements' ),
		    'all_items' => __( 'All Achievements' ),
		    'parent_item' => __( 'Parent Achievement' ),
		    'parent_item_colon' => __( 'Parent Achievement:' ),
		    'edit_item' => __( 'Edit Achievement' ),
		    'update_item' => __( 'Update Achievement' ),
		    'add_new_item' => __( 'Add New Achievement' ),
		    'new_item_name' => __( 'New Achievement Name' ),
		    'menu_name' => __( 'Achievements' ),
	    );

		// Now register the taxonomy
	    register_taxonomy('achievements',array('loyal_badge'), array(
		    'labels' => $labels,
		    'show_ui' => true,
		    'show_in_menu'  => true,
		    'show_admin_column' => false,
		    'query_var' => true,
		    'rewrite'       => array( 'slug' => 'achievements' ),
		    'hierarchical'  => true,
	    ));
    }
}