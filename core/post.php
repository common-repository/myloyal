<?php
namespace myLoyal\core;

class Post{

	protected $meta = [
		'badge_settings' => [
			'key' => 'badge_settings',
			'value' => null
		]
	];

	protected $id;

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
	}

	public function delete_meta( $meta ) {
		if( !isset( $this->meta[$meta] ) ) return;
		delete_user_meta( $this->id, $this->meta[$meta]['key'] );
	}
}