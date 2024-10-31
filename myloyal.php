<?php
/**
 * @since             1.0
 * Plugin Name:       myLoyal
 * Plugin URI:
 * Description:       Manage points, rewards, gamifications, ranks, badges on user activities on your site
 * Version:           1.0.1
 * Author:            CyberCraft
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


namespace myLoyal;

define( 'MYLOYAL_VERSION', 1.0 );
define( 'MYLOYAL_UPDATE_DATA', true );
define( 'MYLOYAL_FILE', __FILE__ );
define( 'MYLOYAL_PLUGIN_BASE', plugin_basename( MYLOYAL_FILE ) );
define( 'MYLOYAL_PATH', plugin_dir_path( MYLOYAL_FILE ) );

if ( defined( 'MYLOYAL_TESTS' ) && MYLOYAL_TESTS ) {
	define( 'MYLOYAL_URL', 'file://' . MYLOYAL_PATH );
} else {
	define( 'MYLOYAL_URL', plugins_url( '/', MYLOYAL_FILE ) );
}

define( 'MYLOYAL_ASSETS_PATH', MYLOYAL_PATH . 'assets/' );
define( 'MYLOYAL_ASSETS_URL', MYLOYAL_URL . 'assets/' );


use myLoyal\core\Action_Runner;
use myLoyal\core\Admin_Menu;
use myLoyal\core\Ajax_Action;
use myLoyal\core\Badge_Actions;
use myLoyal\core\Meta_Panel;
use myLoyal\core\Options;
use myLoyal\core\Point_Processor;
use myLoyal\core\Point_Rule;
use myLoyal\core\Post_Types;
use myLoyal\core\User_Actions;

if ( !function_exists( 'pri' ) ) {
	function pri( $data ) {
		echo '<pre>';print_r($data);echo '</pre>';
	}
}


spl_autoload_register(function ($class_name) {
	$file = strtolower( str_replace( ['\\','_'], ['/','-'], $class_name ) ).'.php';
	$file = str_replace('myloyal', '', $file);
	if ( file_exists( __DIR__ . '/' . $file ) ) {
		include_once __DIR__ . '/' . $file;
	}
});

//
include_once 'vendor/autoload.php';

use As247\WpEloquent\Application;
use myLoyal\migrations\Badge_User;
use myLoyal\migrations\Log;
use myLoyal\shortcodes\User_Profile;

Application::bootWp();


class MyLoyal_Init{

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
	    register_activation_hook( __FILE__, [ $this, 'on_active' ] );
	    $this->includes();
	    User_Profile::instance();
	    Meta_Panel::instance();
	    User_Actions::instance();
	    Badge_Actions::instance();
    	Ajax_Action::instance();
    	Post_Types::instance();
    	Admin_Menu::instance();
	    Point_Processor::instance();
    	Action_Runner::instance();
    	Point_Rule::instance();
    }

    public function includes() {
    	include_once MYLOYAL_PATH . 'core/data-case-hooks.php';
    	add_action( 'admin_init', function () {
		    include_once MYLOYAL_PATH . 'core/data-point-rules.php';
	    });
    }

    public function on_active() {
    	//install db tables
	    (new Badge_User())->run();
	    (new Log())->run();

	    //create a page with shortcode
	    $myloyal_pages = Options::instance()->get_option( 'myloyal_pages' );
	    if ( !$myloyal_pages || !isset( $myloyal_pages['user_profile'] ) ) {
	    	$args = [
	    		'post_type' => 'page',
			    'post_status' => 'publish',
			    'post_title' => 'myLoyal User Profile',
			    'post_content' => '[myloyal_user_profile]'
		    ];
	    	$post_id = wp_insert_post( $args );
	    	$myloyal_pages['user_profile'] = $post_id;
	    	Options::instance()->set_option( 'myloyal_pages', null, $myloyal_pages );
	    }
    }
}

MyLoyal_Init::instance();