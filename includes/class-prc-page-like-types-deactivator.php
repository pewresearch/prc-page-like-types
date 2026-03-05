<?php

class PRC_Page_Like_Types_Deactivator {

	public static function deactivate() {
		flush_rewrite_rules();

		wp_mail(
			DEFAULT_TECHNICAL_CONTACT,
			'PRC Page Like Types Deactivated',
			'The PRC Page Like Types plugin has been deactivated on ' . get_site_url()
		);
	}
}
