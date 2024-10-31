<?php
namespace myLoyal\instance;

class Log{

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

    public function log( $desc, $case = null, $user_id = null, $type = 'db' ) {
	    $data = [
		    'user_id' => $user_id,
		    'case_str' => $case,
		    'description' => $desc,
		    'created_at' => time(),
		    'updated_at' => time()
	    ];

    	if ( $type == 'db' ) {
    		\myLoyal\models\Log::insert( $data );
	    }
    }
}