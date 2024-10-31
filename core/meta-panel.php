<?php
namespace myLoyal\core;
class Meta_Panel {

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
    	add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes'], 10, 2 );
	    add_action( 'save_post', [ $this, 'save_meta_data' ], 10, 2 );
    }

    public function add_meta_boxes( $post_type, $post ) {
	    if ( $post_type !== 'loyal_badge' ) return;
	    add_meta_box( 'myloyal_badge_point_panel', __( 'Points Settings', 'myloyal'), [ $this, 'render_point_panel'] );
    }

    public function render_point_panel( $post ) {
	    include_once MYLOYAL_PATH . 'templates/admin/partials/badge-meta-panel.php';
    }

    public function save_meta_data( $post_id, $post ) {
    	if ( $post->post_type !== 'loyal_badge' ) return;

	    //set category
    	if( !term_exists( 'Common', 'achievements' ) ) {
		    wp_insert_term( 'Common', 'achievements' );
	    }
	    $terms = wp_get_post_terms( $post_id, 'achievements' );
	    if ( empty( $terms ) ) {
		    wp_set_object_terms( $post_id, 'common', 'achievements' );
		    $terms = wp_get_post_terms( $post_id, 'achievements' );
	    }


    	$badge_settings = (new Instance_Post($post_id))->get_meta('badge_settings' );

	    if ( !is_array( $badge_settings ) ) {
		    $badge_settings = [];
	    }

	    $badges = Options::instance()->get_option( 'badges', 'myloyal' );
	    if( !is_array( $badges ) ) {
	    	$badges = [];
	    }

	    if ( isset( $badge_settings['target_point'] ) ) {
		    $badges[$badge_settings['target_point']]['term_'.$terms[0]->term_id][] = $post_id;
		    $badges[$badge_settings['target_point']]['term_'.$terms[0]->term_id] = array_unique( $badges[$badge_settings['target_point']]['term_'.$terms[0]->term_id] );
	    }

	    Options::instance()->set_option( 'badges', 'myloyal', $badges );
    }
}
/*$settings = [
	'badges' => [
		'100' => [
			'cat_1' => [
				'badge_id_1','badge_id_2',...
			]
		]
	]
]*/