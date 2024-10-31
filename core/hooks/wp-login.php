<?php
namespace myLoyal\core\hooks;

use myLoyal\core\Action_Runner;
use myLoyal\core\Instance_User;
use myLoyal\instance\Log;

class Wp_Login{

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
	    add_action( $hook, function ( $user_login, $user ) use ( $hook, $rules_array ) {
		    foreach ( $rules_array as $k => $rule ) {
			    $bool = apply_filters( 'myloyal_before_calculate_point', true, $rule, $hook );
			    $point = 0;
			    if ( $bool ) {
				    $point = Action_Runner::instance()->calculate_point( $rule );
			    }
			    $bool = apply_filters( 'myloyal_after_calculate_point', $bool, $rule, $hook );

			    if ( $bool ) {
				    $updatable = 1;

				    if ( $rule['user_inclusion_type'] == 'include' ) {
					    if ( $rule['applicable_for-user_type'] == 'users' ) {
						    if ( !in_array( $user->ID, $rule['applicable_for-users'] ) ) {
							    continue;
						    }
					    }
				    } else if ( $rule['user_inclusion_type'] == 'exclude' ) {
					    if ( $rule['applicable_for-user_type'] == 'users' ) {
						    if ( in_array( $user->ID, $rule['applicable_for-users'] ) ) {
							    continue;
						    }
					    }
				    }
				    //
				    if ( $rule['role_inclusion_type'] == 'include' ) {
					    if ( $rule['applicable_for-role_type'] == 'roles' ) {
						    if ( empty( array_intersect( get_userdata( $user->ID )->roles, $rule['applicable_for-roles'] ) ) ) {
							    continue;
						    }
					    }
				    } else if ( $rule['role_inclusion_type'] == 'exclude' ) {
					    if ( $rule['applicable_for-role_type'] == 'roles' ) {
						    if ( !empty( array_intersect( get_userdata( $user->ID )->roles, $rule['applicable_for-roles'] ) ) ) {
							    continue;
						    }
					    }
				    }

				    Action_Runner::instance()->set_point( $rule, $point, $user->ID );
			    }
		    }
	    }, 10, 2);
    }
}