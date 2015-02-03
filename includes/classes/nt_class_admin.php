<?php
if ( ! class_exists( 'notifique_me_admin' ) ) :

class notifique_me_admin extends notifique_me_Abstract {


	protected function load() {
		add_action( 'admin_menu', array( &$this, 'admin_menu' ), 8 );	
	}


	public static function get_object( $class = null ) {
		return parent::get_object( __CLASS__ );
	}


	public function admin_menu() {

   		add_menu_page( 'custom menu title', 'Notifique-me', 'manage_options', 'notifiqueme/view/index.php', '', plugins_url( 'notifiqueme/images/icon.png' ), 6 );

	}


	public function install() {
		global $wpdb;
	
			// Delete obsolete options
			$this->delete_option( 'page_id'     );
			$this->delete_option( 'show_page'   );
			$this->delete_option( 'initial_nag' );
			$this->delete_option( 'permalinks'  );
			$this->delete_option( 'flush_rules' );

			// Move options to their own rows
			foreach ( $this->get_options() as $key => $value ) {
				if ( in_array( $key, array( 'active_modules' ) ) )
					continue;

				if ( ! is_array( $value ) )
					continue;

				update_option( "notifique-me_{$key}", $value );

				$this->delete_option( $key );
			}

			// Maybe create login page?
			if ( $page ) {
				// Make sure the page is not in the trash
				if ( 'trash' == $page->post_status )
					wp_untrash_post( $page->ID );

				update_post_meta( $page->ID, '_tml_action', 'login' );
			}
		
	}

}
endif; // Class exists