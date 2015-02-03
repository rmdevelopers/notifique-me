<?php

class token {

	private $key, $iv;

	function __construct( $pass ) {
		$this->key = hash( 'sha256', $pass, true );
		$this->iv = mcrypt_create_iv(32);
	}

	function encryptToken( $input ) {
		return urlencode( base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $this->key, $input, MCRYPT_MODE_ECB, $this->iv ) ) );
	}

	function decryptToken( $input ) {
		return urldecode( trim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $this->key, base64_decode( $input ), MCRYPT_MODE_ECB, $this->iv ) ) );
	}

}