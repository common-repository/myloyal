<?php
namespace myLoyal\core;

class Point_Processor{

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
    }

	/**
	 * @param $id
	 * @param $comment
	 * @param $hook
	 * @param $rules_array
	 */
    function process_on_edit_comment( $id, $comment, $hook, $rules_array ) {
	}

	/**
	 * @param $hook
	 * @param $rules_array
	 */
	public function wp_head() {
	}

}