<?php
namespace myLoyal\core\hooks;

use myLoyal\core\Action_Runner;
use myLoyal\core\Instance_User;
use myLoyal\instance\Log;

class Wp_Head {

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
	    add_action( $hook, function() use ( $hook, $rules_array ) {
		    foreach ( $rules_array as $key => $rule ) {
			    switch ( $rule['case '] ) {
				    case 'when_user_visit_site':

					    $user = null;
					    if ( $rule['target_actor'] == 'own' ) {
						    $user = wp_get_current_user();
					    }
					    if ( !$user ){
						    break;
					    }

					    //page condition
					    if ( $rule['visitable_page'] == 'sel_page') {
						    if ( ! isset( $rule['sel_page'] ) || ! isset( get_queried_object()->ID ) || ! in_array( get_queried_object()->ID, $rule['sel_page'] ) ) {
							    //bail
							    break;
						    }
					    }
					    //check user_type condition
					    if ( $rule['user_type'] == 'logged_id' ) {
						    if ( ! is_user_logged_in() ) break;
						    if ( $rule['role_constrain_type'] == 'include' ) {
							    if ( !in_array( 'all', $rule['user_role'] )
							         && empty( array_intersect( $user->roles, $rule['user_role'] ) ) ) {
								    break;
							    }
						    }
						    if ( $rule['role_constrain_type'] == 'exclude' ) {
							    if ( in_array( 'all', $rule['user_role'] )
							         || ! empty( array_intersect( $user->roles, $rule['user_role'] ) ) ) {
								    break;
							    }
						    }
					    }
					    //target action
					    $point = Action_Runner::instance()->calculate_point( $rule );
					    Action_Runner::instance()->set_point( $rule, $point, $user->ID );
					    break;
			    }
		    }
	    });
    }
}