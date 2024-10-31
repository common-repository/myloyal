<?php
namespace myLoyal\core;

use As247\WpEloquent\Support\Facades\DB;
use myLoyal\models\Badge_User;

class Instance_User{

	protected $meta = [
		'points' => [
			'key' => 'myloyal_points',
			'value' => null
		]
	];

	public $id;

	public function get_id() {
		return $this->id;
	}

	public function __construct( $id ) {
		$this->id = $id;
	}

    public function get_meta( $meta ) {
    	if ( !isset($this->meta[$meta]) )return false;
    	if ( !$this->meta[$meta]['value'] ) {
    		$this->meta[$meta]['value'] = get_user_meta( $this->id, $this->meta[$meta]['key'], true );
	    }
	    return $this->meta[$meta]['value'];
    }

    public function set_meta( $meta, $value ) {
		if( !isset( $this->meta[$meta] ) ) return;
		update_user_meta( $this->id, $this->meta[$meta]['key'], $value );
	    $this->meta[$meta]['value'] = $value;
	    return true;
    }

    public function delete_meta( $meta ) {
	    if( !isset( $this->meta[$meta] ) ) return;
	    delete_user_meta( $this->id, $this->meta[$meta]['key'] );
    }

	/**
	 * Get achieved badges
	 *
	 * @param array $term_id
	 * @param string $fields
	 *
	 * @return null
	 */
    public function get_badges( $term_id = [], $fields = '*' ) {
	    $badges = Badge_User::where('user_id', $this->get_id() );
		if ( ! empty( $term_id ) ) {
			$badges = $badges->whereIn( 'term_id', $term_id );
		}
		$badge_ids = $badges->pluck( 'badge_id' );
	    $badges = null;
		if ( !empty( $badge_ids ) ) {
		    $badges = DB::table('posts')->whereIn('ID', $badge_ids )->get( $fields );
	    }
	    return $badges;
    }
}