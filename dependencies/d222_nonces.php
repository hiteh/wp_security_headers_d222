<?php

if( !interface_exists( 'wp_nonce_interface' ) ) {
	// WP nonces interface
	interface wp_nonce_interface {
		public function wpCreateNonce( string $init );
	}
}

if( ! class_exists( 'D222_Nonces' ) ) {
	// Nonces
	class D222_Nonces implements wp_nonce_interface {
		
		// Public stats
		public $amount = 0;
		public $issued_amount = 0;
		public $used_amount = 0;
		public $used = [];
		public $issued = [];
		public $type = '';

		// Nonces available
		private $pool = [];
		
		// Constructor
		public function __construct( string $type, int $amt ) {
			$this->amount = $amt;
			$this->type = $type;

			if( $this->type && $this->amount > 0 ) {
				switch ( $this->type ) {
					case 'base64':
						for( $i = 0; $i < $this->amount; $i++ ) {
							$this->pool[] = $this->base64();
						}
						break;
					case 'wp_nonce':
						for( $i = 0; $i < $this->amount; $i++ ) {
							$this->pool[] = $this->wp_nonce();
						}
						break;
					default:
						break;
				}
			}
		}
		
		// Get base64 string
		protected function base64() {
			return base64_encode( bin2hex( random_bytes(16) ) );
		}
		
		// Get wp nonce (wrapper)
		protected function wp_nonce() {
			return $this->wpCreateNonce( bin2hex( random_bytes(16) ) );
		}
		
		// Calls the wp_create_nonce method to get wp nonce. 
		public function wpCreateNonce( string $init ) {
			return wp_create_nonce( $init );
		}

		// Issue nonce
		public function issue() {
			if( $this->amount > 0 ) {
				if ( $this->issued_amount < $this->amount ) {
					$this->issued_amount++;
				}
				$nonce = array_shift( $this->pool );
				$this->issued[] = $nonce;
				return $nonce;
			}
		}
		// Use nonce
		public function use() {
			if( $this->issued_amount > 0 ) {
				if ( $this->used_amount < $this->issued_amount ) {
					$this->used_amount++;
				}
				$nonce = array_shift( $this->issued );
				$this->used[] = $nonce;
				return $nonce;
			}
		}
	}
}

