<?php

defined( 'ABSPATH' ) or die();

if( ! interface_exists( 'd222_nonce_interfece') ) {
	// D222_Nonces interface
	interface d222_nonce_interfece {
		public function createNonces( string $type, int $amount );
		public function issueNonce( string $for );
		public function useNonce( string $by );
	}
}

if( ! class_exists( 'D222_Headers' ) ) {

	// Headers
	class D222_Headers implements d222_nonce_interfece {
		
		// Constants
		const XXSSPROTECTION = 'X-XSS-Protection';
		const XCONTENTTYPEOPTIONS = 'X-Content-Type-Options';
		const XFRAMEOPTIONS = 'X-Frame-Options';
		const REFERRERPOLICY = 'Referrer-Policy';
		const XUACOMPATIBLE = 'X-UA-Compatible';
		const FEATUREPOLICY = 'Feature-Policy';
		const CONTENTSECURITYPOLICY = 'Content-Security-Policy';
		const STRICTTRANSPORTSECURITY = 'Strict-Transport-Security';
		const COLON = ':';

		// Variables
		private $nonces = [];
		
		protected $x_content_type_options = "";
		protected $x_frame_options = "";
		protected $referrer_policy = "";
		protected $x_ua_compatible = "";
		protected $feature_policy = "";
		protected $content_security_policy = "";
		protected $x_xss_protection = "";
		protected $strict_transport_security = "";
		
		// Constructor
		public function __construct( array $headers = [] ) {
			if( is_array( $headers ) && count( $headers ) > 0 ) {
				foreach ( $headers as $key => $value ) {
					switch ( strtolower($key) ) {
						case 'strict-transport-security':
							if( is_string( $value ) ) {
								$this->strict_transport_security .= $value;
							}
							break;
						case 'x-content-type-options':
							if( is_string( $value ) ) {
								$this->x_content_type_options .= $value;
							}
							break;
						case 'x-xss-protection':
							if( is_string( $value ) ) {
								$this->x_xss_protection .= $value;
							}
							break;
						case 'x-frame-options':
							if( is_string( $value ) ) {
								$this->x_frame_options .= $value;
							}
							break;
						case 'referrer-policy':
							if( is_string( $value ) ) {
								$this->referrer_policy .= $value;
							}
							break;
						case 'x-ua-compatible':
							if( is_string( $value ) && 'IE=edge' === $value ) {
								$this->x_ua_compatible .= $value;
							}
							break;
						case 'feature-policy':
							if( is_array( $value ) ) {
								foreach ( $value as $directive => $data ) {
									if( is_string( $directive ) ) {
										$this->feature_policy .= $directive . " " . $data  . ";";	
									}
								}
							}
							break;
						case 'content-security-policy':
							if( is_array( $value ) ) {
								foreach ( $value as $directive => $data ) {
									if( is_string( $data ) ) {
										
										$nonces = "";
										
										if( isset( $value['nonces'] ) && array_key_exists( $directive, $value['nonces'] ) ) {	
									
											$amt = $value['nonces'][$directive];
											$ncs = $this->createNonces( 'base64', $amt );
											$this->nonces[$directive] = $ncs;

											for ( $i = 0; $i < $amt; $i++ ) { 
												$nonces .= " 'nonce-" . $this->issueNonce( $directive ) . "'";   
											}
										}

										$this->content_security_policy .= $directive . " " . $data . $nonces . ";";	
									}
								}
							}
							break;
						default:
							break;
					}
				}
			}
		}

		// Use nonce
		public function useNonce( string $for ) {
			if ( array_key_exists( $for, $this->nonces ) ) {
				return $this->nonces[$for]->use();
			}
		}

		// Issue nonce
		public function issueNonce( string $for ) {
			if ( array_key_exists( $for, $this->nonces ) ) {
				return $this->nonces[$for]->issue();
			}
		}

		// Create nonces
		public function createNonces(  string $type, int $amount ) {
			return new D222_Nonces( $type, $amount );
		}

		// Send headers
		public function send() {
			header( self::XXSSPROTECTION . self::COLON . $this->x_xss_protection );
			header( self::XCONTENTTYPEOPTIONS . self::COLON . $this->x_content_type_options );
			header( self::XFRAMEOPTIONS . self::COLON . $this->x_frame_options );
			header( self::REFERRERPOLICY . self::COLON . $this->referrer_policy );
			header( self::XUACOMPATIBLE . self::COLON . $this->x_ua_compatible );
			header( self::FEATUREPOLICY . self::COLON . $this->feature_policy );
			header( self::CONTENTSECURITYPOLICY . self::COLON . $this->content_security_policy );
			header( self::STRICTTRANSPORTSECURITY . self::COLON . $this->strict_transport_security );
		}
	}
}