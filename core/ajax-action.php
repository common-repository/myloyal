<?php
namespace myLoyal\core;

class Ajax_Action {

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
    	add_action( 'wp_ajax_myloyal_save_rule_data', [ $this, 'save_rule_data' ] );
    	add_action( 'wp_ajax_myloyal_save_badge_settings', [ $this, 'save_badge_settings' ] );

    	//reset point
	    add_action( 'wp_ajax_myloyal_reset_point', [ $this, 'reset_point'] );
    }

    public function save_rule_data() {
    	//\myLoyal\pri($_POST);
    	$rule_data = $_POST['rule_data'];
	    $saving_data = [];

	    foreach ( $rule_data as $k => $each ) {
	    	$saving_data[$each['case']][] = $each;
	    }
	    Options::instance()->set_option( 'rule_data',  'myloyal',$saving_data );
	    //\myLoyal\pri( Options::instance()->get_option('rule_data', 'myloyal' ) );
	    exit;
    }

    public function save_badge_settings() {
    	$new_badge_settings = $_POST['badge_settings'];
    	$post_id = $_POST['post_id'];

    	//first, take saved badge settings, and modify badges from option with it to clean up previous data
	    $instance_post = new Instance_Post( $post_id );
	    $badge_settings = $instance_post->get_meta( 'badge_settings' );
	    !is_array( $badge_settings ) ? $badge_settings = [] : '';
	    if ( isset( $badge_settings['target_point'] ) ) {
		    $badges = Options::instance()->get_option( 'badges', 'myloyal' );
		    !is_array( $badges ) ? $badges = [] : '';
		    if ( isset( $badges[ $badge_settings['target_point'] ] ) ) {
		    	foreach ( $badges[ $badge_settings['target_point'] ] as $term => $badge_ids ) {
		    		if ( in_array( $post_id, $badge_ids ) ) {
					    unset( $badges[ $badge_settings['target_point'] ][$term][ array_search( $post_id , $badges[ $badge_settings['target_point'] ][$term] ) ] );
					    if ( empty( $badges[ $badge_settings['target_point'] ][$term] ) ) {
						    unset( $badges[ $badge_settings['target_point'] ][$term] );
						    if ( empty( $badges[ $badge_settings['target_point'] ] ) ) {
						    	unset( $badges[ $badge_settings['target_point'] ] );
						    }
					    }
				    }
			    }
		    }
		    Options::instance()->set_option( 'badges', 'myloyal', $badges );
	    }

	    $instance_post->set_meta( 'badge_settings', $new_badge_settings );
	    wp_send_json_success([
	    	'msg' => 'Settings saved successfully !'
	    ]);
    }

    public function reset_point() {
    	if ( ! Functions::instance()->current_user_can( 'reset_points' ) ) wp_send_json_error([ 'msg' => __( 'You cannot reset point !', 'myloyal' )]);
    	$new_point = intval( sanitize_text_field( $_POST['new_point'] ) );
    	$user_id = $_POST['user_id'];
    	$instance_user = new Instance_User( $user_id );
	    $old_point = $instance_user->get_meta( 'points' );
	    $result = $instance_user->set_meta( 'points', $new_point );
	    if( $result ) {
		    do_action( 'myloyal_after_set_point', $result, $new_point, $old_point, $instance_user );
		    wp_send_json_success( [
		    	'msg' => 'Point is reset !'
		    ]);
	    }
	    wp_send_json_error( ['msg' => __( 'Something went wrong !', 'myloyal' )] );
    }
}