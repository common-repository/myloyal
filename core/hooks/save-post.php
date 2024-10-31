<?php
namespace myLoyal\core\hooks;

use myLoyal\core\Action_Runner;

class Save_Post{

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

    public function run( $hook, $rules_array ) {
	    add_action( $hook, function ( $post_id, $post, $update ) use ( $hook, $rules_array ) {
		    foreach ( $rules_array as $k => $rule ) {
			    $updatable = 1;

			    $user_data = wp_get_current_user();

			    //check if the current user is post author
			    if ( $post->post_author !== $user_data->ID ) {
			    	continue;
			    }

			    //check user
			    if ( isset( $rule['user_type'] ) ) {
				    if ( $rule['user_type'] == 'sel_user' ) {
					    if ( !is_array( $rule['sel_users'] ) || !isset( $rule['sel_users'] ) ) continue;
					    if ( ! in_array( get_current_user_id(), $rule['sel_users'] )  ) {
						    continue;
					    }
				    }
			    }

			    //check role
			    if ( isset( $rule['role_types'] ) ) {
				    if ( !is_array( $rule['role_types']  ) ) continue;
			    	if ( empty( array_intersect( array_push( $user_data->roles, 'all' ), $rule['role_types'] ) ) ) continue;
			    }
			    //check post status
			    if ( isset( $rule['post_statuses'] ) && is_array( $rule['post_statuses'] ) ) {
				    if ( empty( array_intersect( [ 'all', $post->post_status ], $rule['post_statuses'] ) ) ) continue;
			    }
			    //check post type
			    if ( isset( $rule['post_types'] ) && is_array( $rule['post_types'] ) ) {
				    if ( empty( array_intersect( [ 'all', $post->post_type ], $rule['post_types'] ) ) ) continue;
			    }
			    //check is update
			    if ( isset( $rule['is_update'] ) && is_array( $rule['is_update'] ) ) {
				    if ( !$update ) {
					    if ( !in_array( 'false', $rule['is_update'] ) ) continue;
			        } elseif ( $update ) {
					    if ( !in_array( 'true', $rule['is_update'] ) ) continue;
				    }
			    }

			    $point = null;
			    $bool = apply_filters( 'myloyal_before_calculate_point', true, $rule, $hook );
			    if ( $bool ) {
				    $point = Action_Runner::instance()->calculate_point( $rule );
			    }

			    if ( $bool ) {
				    Action_Runner::instance()->set_point( $rule, $point, get_current_user_id() );
			    }
		    }
	    }, 10, 3 );
    }
}