<?php
namespace myLoyal\core;

class Admin_Menu {

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
    	add_action( 'admin_menu', [ $this, 'add_menu_item' ] );
    	add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts_styles' ] );
    }

    public function add_menu_item() {
    	add_menu_page( 'myLoyal', 'myLoyal', 'administrator', 'myloyal', [ $this, 'menu_page' ] );
    	add_submenu_page( 'myloyal', __( 'Point Rule', 'myloyal'), __( 'Point Rule', 'myloyal'), 'administrator', 'point_rules', [ $this, 'menu_page' ] );
    	add_submenu_page( 'myloyal', __( 'Badge', 'myloyal' ), __( 'Badge', 'myloyal' ), 'administrator', 'edit.php?post_type=loyal_badge' );
    	add_submenu_page( 'myloyal', __( 'Achievements', 'myloyal' ), __( 'Achievements', 'myloyal' ), 'administrator', 'edit-tags.php?taxonomy=achievements&post_type=loyal_badge' );
    }

    public function menu_page() {
    	if( isset( $_GET['page'] ) ) {
    		$template = str_replace( '_', '-', $_GET['page'] ) . '.php';
    		include_once MYLOYAL_PATH . 'templates/admin/' .$template;
	    }
    }

    public function admin_enqueue_scripts_styles( $hook ) {
    	global $pagenow;
        if ( get_admin_page_parent() == 'myloyal' || ( $pagenow == 'post.php' && get_post_type() == 'loyal_badge' ) ) {
    		?>
		    <script>
			    myloyal_vuedata = {
				    data: {},
				    methods: {},
				    created: function () {

				    },
				    mounted: function () {

				    }
                };
		    </script>
		    <?php

		    if ( ( isset( $_GET['page'] ) && $_GET['page'] == 'point_rules' )
            || ( $pagenow == 'post.php' && get_post_type() == 'loyal_badge' )
            ) {
		    	wp_enqueue_style( 'myloyal-css', MYLOYAL_ASSETS_URL . 'css/app.css' );
		    	wp_enqueue_script( 'myloyal-app', MYLOYAL_ASSETS_URL . 'js/app.js', ['jquery'], false, true );
		    }
	    }
    	?>

<?php
    }
}