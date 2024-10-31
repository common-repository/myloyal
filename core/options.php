<?php
namespace myLoyal\core;

class Options{

	protected $options = [
		'myloyal' => null, //sections = rule_data, badges
		//'rule_data' => null,
		'myloyal_pages' => null, //[ 'user_profile' => id ]
	];

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
	 * @param null $option_name
	 * @param null $parent
	 * @param null $default
	 *
	 * @return mixed
	 */
    public function get_option( $option_name = null, $parent = null, $default = null ) {

    	if ( $parent ) {

    		if ( ! in_array( $parent, array_keys( $this->options ) ) ) return false;

    		if ( ! $this->options[$parent] ) {
			    $this->options[$parent] = get_option( $parent );
		    }

		    if ( $option_name && isset( $this->options[$parent][$option_name] ) ) {
			    return $this->options[$parent][$option_name];
		    } else {
    			return $default;
		    }

		    return $this->options[$parent];
	    }

    	if ( ! $this->options[$option_name] ) {

    		$data = get_option( $option_name );

    		if ( ! $data && $default ) {
			    $data = $default;
		    }

		    $this->options[$option_name] = $data;
	    }

	    return $this->options[$option_name];
    }

	/**
	 * @param $option_name
	 * @param null $parent
	 * @param $value
	 */
    public function set_option( $option_name, $parent = null, $value ) {
    	if ( $parent ) {
    		$option_data = $this->get_option( $parent );
    		$option_data[$option_name] = $value;
    		$this->options[$parent] = $option_data;
		    update_option( $parent, $option_data );

	    } elseif ( ! $parent ) {
    		$option_data = $value;
    		$this->options[$option_name] = $option_data;
		    update_option( $option_name, $value );
	    }
    }

	/**
	 * @param $option_name
	 * @param null $parent
	 */
    public function delete_option( $option_name, $parent = null ) {
    	if ( $parent ) {
    		$option_data = $this->get_option( null, $parent );

    		if ( isset( $option_data[$option_name] ) ) {
    			unset( $option_data[$option_name] );
    			$this->set_option( $parent, null, $option_data );
		    }
	    } else {
		    delete_option( $option_name );
		    $this->set_option( $option_name, null, null );
	    }
    }
}