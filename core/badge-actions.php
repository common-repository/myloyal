<?php
namespace myLoyal\core;

use myLoyal\instance\Log;
use myLoyal\models\Badge_User;

class Badge_Actions{

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
	    add_action( 'myloyal_after_set_point', [ $this, 'assign_badge' ], 10, 4 );
    }

	public function assign_badge( $result, $new_point, $old_point, $user ) {
    	if ( !$user ) return;
		//check if any badge applicable
		if ( $result ) {
			$badges = Options::instance()->get_option( 'badges', 'myloyal', [] );

			$to = $from = null;
			$increase = null;

			if ( $new_point > $old_point ) {
				$from = $old_point;
				$to = $new_point;
				$increase = true;
			} elseif ( $new_point < $old_point ) {
				//decrease, detach badge
				$from = $new_point;
				$to = $old_point;
				$increase = false;
			}

			if ( !is_null( $increase ) ) {
				$badge_ids = [];
				foreach ( $badges as $point => $point_data) {
					if ( $point <= $to && $point >= $from ) {
						foreach ( $point_data as $cat_name => $badge_id_array ) {
							$badge_ids = array_merge( $badge_ids, $badge_id_array );
						}
					}
				}

				$badge_ids = array_unique( $badge_ids );

				if ( !empty( $badge_ids ) ) {
					if ( $increase ) {
						//attach badges
						$data = [];
						foreach ( $badge_ids as $k => $badge_id ) {
							$data[] = ['user_id' => $user->get_id(), 'badge_id' => $badge_id, 'term_id' => null ];
						}
						Badge_User::insert($data);
						Log::instance()->log( 'Badges earned.', null, $user->get_id() );
					} else {
						//detach badges
						Badge_User::whereIn( 'user_id', [$user->id] )->whereIn( 'badge_id', $badge_ids)->delete();
						Log::instance()->log( 'Badges removed.', null, $user->get_id() );
					}
				}
			}
			$badges = Options::instance()->get_option( 'badges', 'myloyal', [] );

		}
	}
}