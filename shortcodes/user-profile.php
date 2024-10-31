<?php
namespace myLoyal\shortcodes;

use myLoyal\core\Instance_User;

class User_Profile{

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
    	add_shortcode( 'myloyal_user_profile', [ $this, 'user_profile' ] );
    }

    public function user_profile( $atts, $content ) {
	    $a = shortcode_atts(array(
		    'user_id' => get_current_user_id(),
		), $atts);

	    //earned points, badges, logs
	    ob_start();
	    ?>
	    <h4>
		    <?php echo 'User : '.get_userdata( $a['user_id'] )->user_nicename; ?>
	    </h4>
	    <table>
		    <tr>
			    <th><?php _e( 'Points Earned', 'myloyal' ); ?></th>
			    <td><?php echo (new Instance_User( $a['user_id'] ) )->get_meta( 'points' ); ?></td>
		    </tr>
		    <tr>
			    <th><?php _e( 'Badges Earned', 'myloyal' ); ?></th>
			    <td><?php
				    $badges = (new Instance_User( $a['user_id'] ) )->get_badges();
				    $badges_str = '';
				    foreach ( $badges as $b => $badge ) {
				    	$badges_str .= $badge->post_title;
				    }
				    echo trim( $badges_str, ',' );
				    ?></td>
		    </tr>
	    </table>
<?php
	    return ob_get_clean();
    }
}