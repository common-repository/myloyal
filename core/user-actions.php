<?php
namespace myLoyal\core;

class User_Actions{

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
	    add_action( 'show_user_profile', [ $this, 'user_profile_fields' ] );
	    add_action( 'admin_footer', [ $this, 'wp_footer'] );
    }

    public function user_profile_fields( $user ) {
    	?>
	    <h3><?php _e( 'myLoyal Info', 'myloyal' ); ?></h3>
        <input type="hidden" name="user_id" value="<?php echo $user->ID; ?>">
        <table class="form-table">
		    <tr>
			    <th><label for="address"><?php _e("Points Achieved", 'myloyal'); ?></label></th>
			    <td>
				    <span class="myloyal-user-points">
                        <?php echo ( new Instance_User( $user->ID ) )->get_meta('points'); ?>
                    </span>
			    </td>
                <?php
                if ( Functions::instance()->current_user_can( 'reset_points' ) )  {
                    ?>
                    <td>
                        <div style="margin-bottom:10px;">
                            <a href="javascript:" class="btn btn-danger myloyal_reset_toggle_handler"><?php esc_attr_e( 'Reset Point', 'myloyal' ); ?></a>
                        </div>
                        <div class="myloyal_new_point_container">
                            <input type="number" name="myloyal_new_point" value="0">
                            <a href="javascript:" class="myloyal_reset_point"><?php _e( 'Reset', 'myloyal' ); ?></a>
                        </div>
                    </td>
                    <?php
                }
                ?>
		    </tr>
	    </table>

<?php
    }

    public function wp_footer() {
        global $pagenow;
        if ( $pagenow !== 'profile.php' ) return;
        ?>
        <style>
            .myloyal_new_point_container {
                display: none;
            }
        </style>
        <script>
	        (function ($) {
            	$('.myloyal_reset_toggle_handler').click(function () {
            		$('.myloyal_new_point_container').toggle();
	            });
            	$('.myloyal_reset_point').click(function () {
		            $.post(
			            ajaxurl,
			            {
				            action: 'myloyal_reset_point',
				            new_point: $('[name="myloyal_new_point"]').val(),
				            user_id: $('[name="user_id"]').val()
			            },
			            function (res) {
				            if ( res.success ) {
					            $('.myloyal-user-points').html( $('[name="myloyal_new_point"]').val() );
				            }
				            $('.myloyal_new_point_container').toggle();
			            }
		            )
	            });
            }(jQuery))
        </script>
<?php
    }
}