<?php
namespace myLoyal\core\hooks;

use myLoyal\core\Action_Runner;
use myLoyal\core\Instance_User;
use myLoyal\instance\Log;

class Edit_Comment{

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
	    add_action( $hook, function ( $comment_id, $comment ) use ( $hook, $rules_array ) {
		    $comment = (object)$comment;

		    foreach ( $rules_array as $k => $rule ) {
			    //
			    $parent_post = get_post( $comment->comment_post_ID );
			    $commenter =  get_user_by( 'email', $comment->comment_author_email );

			    //check post
			    if( isset( $rule['sel_post'] ) && $rule['sel_post'] == 'sel_post' ) {
				    if ( !isset( $rule['sel_post_ids'] ) ) continue;
				    if ( !in_array( $comment->comment_post_ID, $rule['sel_post_ids'] ) ) continue;
			    }
			    //check post type
			    if( isset( $rule['post_type'] ) ) {
				    if( !is_array( $rule['post_type'] ) ) continue;
				    if( empty( array_intersect( ['all', $parent_post->post_type ], $rule['post_type'] ) ) ) continue;
			    }

			    //check post status
			    if( isset( $rule['post_status'] ) ) {
				    if( !is_array( $rule['post_status'] ) ) continue;
				    if( empty( array_intersect( ['all', $parent_post->post_status ], $rule['post_status'] ) ) ) continue;
			    }
			    //check post author constrain for user and role
			    if( isset( $rule['post_author_constrain'] ) ) {
				    if ( $rule['constrain_author_list'] == 'sel_user' ) {
					    if ( !isset( $rule['constrain_author_ids'] ) || !is_array( $rule['constrain_author_ids'] ) ) continue;

					    $author_roles =  get_userdata( $parent_post->post_author )->roles;
					    if ( $rule['post_author_constrain'] == 'include' ) {
						    if( !in_array( $parent_post->post_author, $rule['constrain_author_ids'] ) ) continue;
						    if ( isset( $rule['constrain_author_role'] ) && is_array( $rule['constrain_author_role'] ) ) {
							    if ( empty( array_intersect( array_push( $author_roles, 'all' ), $rule['constrain_author_role'] ) ) ) continue;
						    }
					    } elseif ( $rule['post_author_constrain'] == 'exclude' ) {
						    if( in_array( $parent_post->post_author, $rule['constrain_author_ids'] ) ) continue;
						    if ( isset( $rule['constrain_author_role'] ) && is_array( $rule['constrain_author_role'] ) ) {
							    if ( !empty( array_intersect( array_push( $author_roles, 'all' ), $rule['constrain_author_role'] ) ) ) continue;
						    }
					    }
				    }
			    }
			    //check commenter constrain for commenter and commenter role
			    if( isset( $rule['commenter_constrain'] ) ) {
				    if ( $rule['constrain_commenter_list'] == 'sel_user' ) {
					    if ( !isset( $rule['constrain_commenter_ids'] ) || !is_array( $rule['constrain_commenter_ids'] ) ) continue;
					    if ( $rule['commenter_constrain'] == 'include' ) {
						    if( !in_array( $parent_post->post_author, $rule['constrain_commenter_ids'] ) ) continue;
						    if ( isset( $rule['constrain_commenter_role'] ) && is_array( $rule['constrain_commenter_role'] ) ) {
							    if ( !isset( $commenter->roles ) ) continue;
							    if ( empty( array_intersect( array_push( $commenter->roles, 'all' ), $rule['constrain_commenter_role'] ) ) ) continue;
						    }
					    } elseif ( $rule['commenter_constrain'] == 'exclude' ) {
						    if( in_array( $parent_post->post_author, $rule['constrain_commenter_ids'] ) ) continue;
						    if ( isset( $rule['constrain_commenter_role'] ) && is_array( $rule['constrain_commenter_role'] ) ) {
							    if ( isset( $commenter->roles ) ) {
								    if ( !empty( array_intersect( array_push( $commenter->roles, 'all' ), $rule['constrain_commenter_role'] ) ) ) continue;
							    }

						    }
					    }
				    }
			    }
			    //actions
			    $point = Action_Runner::instance()->calculate_point( $rule );
			    Action_Runner::instance()->set_point( $rule, $point, $commenter->ID );
		    }
	    }, 10, 2 );
    }
}