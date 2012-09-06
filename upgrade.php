<?php
global $wpdb;

// *************************************************************************************************** //
// Upgrade to 0.2
	if (get_option('rb_license_version') == "0.1") { 

		// Example Add Fields
		//$results = $wpdb->query("ALTER TABLE ". table_license_license ." ADD LicenseTest TEXT");
		
		// Example Change Fields
		//$results = $wpdb->query("ALTER TABLE ". table_license_license ." CHANGE LicenseTest LicenseTestNew TEXT");
		
		// Updating version number!
		update_option('rb_license_version', "0.2");
	}


// Ensure directory is setup
if (!is_dir(rb_license_UPLOADPATH)) {
	mkdir(rb_license_UPLOADPATH, 0755);
	chmod(rb_license_UPLOADPATH, 0777);
}
?>