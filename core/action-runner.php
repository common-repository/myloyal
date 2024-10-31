<?php
namespace myLoyal\core;

use As247\WpEloquent\Support\Facades\DB;
use myLoyal\instance\Log;
use myLoyal\models\Badge_User;

class Action_Runner{

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
    	$this->run_actions();
    }

    public function run_actions() {
    	global $myloyal_point_rules, $myloyal_case_hooks;
	    $rule_data = Options::instance()->get_option( 'rule_data', 'myloyal' );

	    foreach ( $rule_data as $case => $rules_array ) {
		    $hook = $myloyal_case_hooks[$case];
		    $classname = 'myloyal\\core\\hooks\\'.ucwords( $hook,  '_' );
		    if ( class_exists( $classname ) ) {
			    $classname::instance()->run( $hook, $rules_array );
		    }
		    do_action( 'myloyal_process_point', $hook, $rules_array, $classname );
	    }
    }

	/**
	 * @param $rule
	 *
	 * @return int
	 */
    public function calculate_point( $rule ) {
	    switch ( $rule['action'] ) {
		    case 'add':
			    $multiplier = +1;
			    break;
		    case 'deduct':
			    $multiplier = -1;
			    break;
		    default:
			    $multiplier = +1;
			    break;
	    }
	    return $point = $multiplier * $rule['point'];
    }

	/**
	 * @param $rule
	 * @param $point
	 * @param null $hook
	 * @param array $extra
	 */
	public function set_point( $rule, $point, $user_id ) {
		//target actor
		$result = null;
		$user = new Instance_User( $user_id );
		$old_point = $user->get_meta( 'points' );
		$new_point = $old_point + $point;
		if ( $new_point < 0 ) {
			$new_point = 0;
		}
		$result = $user->set_meta( 'points', $new_point );
		Log::instance()->log( $point. ' earned.', $rule['case'], $user->get_id() );
		do_action( 'myloyal_after_set_point', $result, $new_point, $old_point, $user );
	}
}

/*
Array
(
	[when_user_create_comment] =>
		Array
		(
			[0] => Array
			(
				[case] => when_user_create_comment
				[action] => add
				[point] => 1
	            [target_actor] => own
				[exception_for] => no
				[applicable_for] => all
				[post_type] => all
	         )

	     )

)*/