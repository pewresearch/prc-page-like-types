<?php

class PRC_Page_Like_Types_Activator {

	public static function activate() {
		flush_rewrite_rules();

		wp_mail(
			DEFAULT_TECHNICAL_CONTACT,
			'PRC Page Like Types Activated',
			'The PRC Page Like Types plugin has been activated on ' . get_site_url()
		);
	}
}
