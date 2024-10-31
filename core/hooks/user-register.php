<?php
namespace myLoyal\core\hooks;

use myLoyal\core\Action_Runner;
use myLoyal\core\Instance_User;
use myLoyal\instance\Log;

class User_Register{

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
    	//$user_id, $userdata,
	    add_action( $hook, function( $user_id, $userdata ) use ( $hook, $rules_array ) {

		    foreach ( $rules_array as $k => $rule ) {

			    if ( $rule['case'] == 'when_user_create_user' ) {
				    //check if the creator is the registerer himself, if so, return
				    if( get_current_user_id() == $user_id ) {
					    continue;
				    }
			    } elseif ( $rule['case'] == 'when_user_register' ) {
				    if( get_current_user_id() != $user_id ) {
					    continue;
				    }
			    }

			    //check role for created user
			    if ( empty( array_intersect( ['all', $userdata['role'] ], $rule['role'] ) ) ) {
				    continue;
			    }

			    $point = Action_Runner::instance()->calculate_point( $rule );
			    Action_Runner::instance()->set_point( $rule, $point, $user_id );
		    }
	    });

    }
}