<?php 
/*
  Plugin Name: RB License
  Plugin URI: http://rbplugin.com/wordpress/license/
  Description: A license plugin for WordPress.
  Author: Rob Bertholf, rob@bertholf.com
  Author URI: http://rbplugin.com/
  Text Domain: rb-license
  Version: 0.1
*/
$rb_license_VERSION = "0.1";


// *************************************************************************************************** //
	
	// Kick it off
		if (!session_id())
		session_start();
		
		if ( ! isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '2.8', '<') ) { // if less than 2.8 
			echo "<div class=\"error\" style=\"margin-top:30px;\"><p>This plugin requires WordPress version 2.8 or newer.</p></div>\n";
		return;
		}
	
	// Avoid direct calls to this file, because now WP core and framework has been used
		if ( !function_exists('add_action') ) {
			header('Status: 403 Forbidden');
			header('HTTP/1.1 403 Forbidden');
			exit();
		}
		
	// Plugin Definitions
		define("rb_license_VERSION", $rb_license_VERSION); // e.g. 1.0
		define("rb_license_BASENAME", plugin_basename(__FILE__) );  // rb-license/rb-license.php
		$rb_license_WPURL = get_bloginfo("wpurl"); // http://domain.com/wordpress
		$rb_license_WPUPLOADARRAY = wp_upload_dir(); // Array  $rb_license_WPUPLOADARRAY['baseurl'] $rb_license_WPUPLOADARRAY['basedir']
		define("rb_license_BASEDIR", get_bloginfo("wpurl") ."/". PLUGINDIR ."/". dirname( plugin_basename(__FILE__) ) ."/" );  // http://domain.com/wordpress/wp-content/plugins/rb-license/
		define("rb_license_UPLOADDIR", $rb_license_WPUPLOADARRAY['baseurl'] );  // http://domain.com/wordpress/wp-content/uploads/license/
		define("rb_license_UPLOADPATH", $rb_license_WPUPLOADARRAY['basedir'] ); // /home/content/99/6048999/html/domain.com/wordpress/wp-content/uploads/profile-media/
		define("rb_license_TEXTDOMAIN", basename(dirname( __FILE__ )) ); //   rb-license
	
	// Call Language Options
		add_action("init", "rb_license_loadtranslation");
			function rb_license_loadtranslation() {
				load_plugin_textdomain( rb_license_TEXTDOMAIN, false, basename( dirname( __FILE__ ) ) . "/translation/"); 
			}
		
	// Set Table Names
		if (!defined("table_license_license"))
			define("table_license_license", "rb_license_license");
		if (!defined("table_license_product"))
			define("table_license_product", "rb_license_product");

	// Call default functions
		include_once(dirname(__FILE__).'/functions.php');
	
	// Does it need a diaper change?
		include_once(dirname(__FILE__).'/upgrade.php');

// *************************************************************************************************** //
// Creating tables on plugin activation

	function rb_license_install() {
		// Required for all WordPress database manipulations
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		// Set Default Options
			$rb_license_options_arr = array(
				"rb_license_option_name1" => "",
				"rb_license_option_name2" => 1,
				"rb_license_option_name3" => true
				);

		// Update the options in the database
			update_option("rb_license_options", $rb_license_options_arr);
			
		// Hold the version in a seprate option
			add_option("rb_license_version", $rb_license_VERSION);


		/****************  Create Tables in Database ***************/
	
		// Table License
		if ($wpdb->get_var("show tables like '". table_license_license ."'") != table_license_license) { // No, Create
			$sql = "CREATE TABLE ". table_license_license ." (
				LicenseAutoID BIGINT(20) NOT NULL AUTO_INCREMENT,
				LicenseTitle VARCHAR(255),
				LicenseVersion VARCHAR(255),
				LicenseText TEXT,
				LicenseType INT(10) NOT NULL DEFAULT '0',
				LicenseImage VARCHAR(255),
				LicenseURL VARCHAR(255),
				LicenseClientName VARCHAR(255),
				LicenseClientEmail VARCHAR(255),
				LicenseDateCreated TIMESTAMP DEFAULT NOW(),
				LicenseDateUpdated TIMESTAMP,
				LicenseActive INT(10) NOT NULL DEFAULT '0',
				PRIMARY KEY (LicenseAutoID)
				);";
			dbDelta($sql);
		}
		
		// Table Type (Type)
		if ($wpdb->get_var("show tables like '". table_license_product ."'") != table_license_product) { // No, Create
			$sqlTypePayment = "CREATE TABLE ". table_license_product ." (
				ProductID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProductTitle VARCHAR(255),
				ProductPrice DECIMAL(12,2),
				ProductVersion VARCHAR(50),
				PRIMARY KEY (ProductID)
				);";
			dbDelta($sqlTypePayment);
			// Populate table with initial values
			$results = $wpdb->query("INSERT INTO " . table_license_product . " (ProductTitle, ProductVersion) VALUES ('RB Agency','1.0')");
			$results = $wpdb->query("INSERT INTO " . table_license_product . " (ProductTitle, ProductVersion) VALUES ('RB Agency Interact','1.0')");
		}

	}
	
	//Activate Install Hook
	register_activation_hook(__FILE__,'rb_license_install');


// *************************************************************************************************** //
// Register Administrative Settings

	if ( is_admin() ){
	
		/****************  Add Options Page Settings Group ***************/
		
		add_action('admin_init', 'rb_license_register_settings');
			// Register our Array of settings
			function rb_license_register_settings() {
				register_setting('rb-license-settings-group', 'rb_license_options'); //, 'rb_license_options_validate'
			}
			
			// Validate/Sanitize Data
			function rb_license_options_validate($input) {
				// Our first value is either 0 or 1
				//$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
				
				// Say our second option must be safe text with no HTML tags
				//$input['sometext'] =  wp_filter_nohtml_kses($input['sometext']);
				
				//return $input;
			}	
	
		
		/****************  Settings in Plugin Page ***********************/
	
		add_action( 'plugins_loaded', 'rb_license_init' );
			// Initialize Settings
			function rb_license_init() {
			  if ( is_admin() ){
				add_action('admin_menu', 'rb_license_addsettingspage');
			  }
			}
			function rb_license_on_load() {
				add_filter( 'plugin_action_links_' . rb_license_BASENAME, 'rb_license_filter_plugin_meta', 10, 2 );  
			}
			
			// Add Link to Admin Client
			function rb_license_filter_plugin_meta($links, $file) {
				if (empty($links))
					return;
				/* create link */
				if ( $file == rb_license_BASENAME ) {
					array_unshift(
						$links,
						sprintf( '<a href="tools.php?page=%s">%s</a>', rb_license_BASENAME, __('Settings') )
					);
				}
				return $links;
			}
			
			function rb_license_addsettingspage() {
				if ( !current_user_can('update_core') )
					return;
				$pagehook = add_management_page( __("Manage Licenses", rb_license_TEXTDOMAIN), __("Licenses", rb_license_TEXTDOMAIN), 'update_core', rb_license_BASENAME, 'rb_license_menu_settings', '' );
				add_action( 'load-plugins.php', 'rb_license_on_load' );
			}

		
	
		/****************  Activate Admin Client Hook ***********************/

		add_action('admin_menu','set_rb_license_menu');
			//Create Admin Menu
			function set_rb_license_menu(){
				add_menu_page( __("License Manager", rb_license_TEXTDOMAIN), __("License Plugin", rb_license_TEXTDOMAIN), 1,"rb_license_menu","rb_license_menu_dashboard","div");
				add_submenu_page("rb_license_menu", __("Overview", rb_license_TEXTDOMAIN), __("Overview", rb_license_TEXTDOMAIN), 1,"rb_license_menu", "rb_license_menu_dashboard");
				add_submenu_page("rb_license_menu", __("Manage Licenses", rb_license_TEXTDOMAIN), __("License", rb_license_TEXTDOMAIN), 7,"rb_license_license","rb_license_menu_license");
				add_submenu_page("rb_license_menu", __("Edit Settings", rb_license_TEXTDOMAIN), __("Settings", rb_license_TEXTDOMAIN), 7,"rb_license_settings","rb_license_menu_settings");
			}
	
			//Pages
			function rb_license_menu_dashboard(){
				include_once('admin/overview.php');
			}
			function rb_license_menu_license(){
				include_once('admin/license.php');
			}
			function rb_license_menu_settings(){
				include_once('admin/settings.php');
			}

	
		/****************  Add Custom Meta Box to Pages/Posts  *********/
	
		add_action('admin_menu', 'rb_license_add_custom_box');
			// Add Custom Meta Box to Posts / Pages
			function rb_license_add_custom_box() {
			   if( function_exists( 'add_meta_box' )) {
				// Add to Posts
				add_meta_box( 'rb_license_sectionid', __( 'Insert Shortcode', rb_license_TEXTDOMAIN), 
							'rb_license_inner_custom_box', 'post', 'advanced' );
				// Add to Pages
				add_meta_box( 'rb_license_sectionid', __( 'Insert Shortcode', rb_license_TEXTDOMAIN), 
							'rb_license_inner_custom_box', 'page', 'advanced' );
			   } else {
				add_action('dbx_post_advanced', 'rb_license_old_custom_box' );
				add_action('dbx_page_advanced', 'rb_license_old_custom_box' );
			  }
			}
		   
			// Prints the inner fields for the custom post/page section
			function rb_license_inner_custom_box() {
				// Use nonce for verification
				echo '<input type="hidden" name="rb_license_noncename" id="rb_license_noncename" value="'. wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
			
				echo "<div class=\"submitbox\" id=\"add_ticket_box\">\n";
				// Add Javascript
				echo "<script type=\"text/javascript\">\n";
				echo "	function rb_license_insertshortcode(){\n";
				echo "		var $rblicense = jQuery.noConflict();\n";
				echo "		str='';\n";

				echo "		rb_license_recordid = $rbagency('#rb_license_recordid').val();\n";
				echo "		if(rb_license_recordid != '')\n";
				echo "		  str+=' rb_license_recordid = \"'+rb_license_recordid+'\"';\n";

				echo "		rb_license_type = $rbagency('#rb_license_type').val();\n";
				echo "		if(rb_license_type != '')\n";
				echo "		  str+=' rb_license_type = \"'+rb_license_type+'\"';\n";
			
				echo "		send_to_editor('[license_detail'+str+']');return;\n";
				echo "	}\n";
				// Second Insert Button
				echo "	function rb_license_insertshortcodenoval(){\n";
				echo "		send_to_editor('[license_list]');return;\n";
				echo "	}\n";
				echo "</script>\n";

				echo "<table>\n";
				echo "	<tr><td>". __("Record ID", rb_license_TEXTDOMAIN) .":</td><td><input type=\"text\" id=\"rb_license_recordid\" name=\"rb_license_age_start\" value=\"18\" /></td></tr>\n";
				echo "	<tr><td>Type:</td><td><select id=\"rb_license_type\" name=\"rb_license_type\">\n";
						global $wpdb;
						$profileDataTypes = mysql_query("SELECT * FROM ". table_license_product ."");
						echo "<option value=\"\">". __("Any Profile Type", rb_license_TEXTDOMAIN) ."</option>\n";
						while ($dataType = mysql_fetch_array($profileDataTypes)) {
							echo "<option value=\"". $dataType["ProductID"] ."\">". $dataType["ProductTitle"] ."</option>";
						}
						echo "</select></td></tr>\n";
				echo "</table>\n";
				echo "<p><input type=\"button\" onclick=\"rb_license_insertshortcode()\" value=\"". __("Insert Shortcode With Attributes", rb_license_TEXTDOMAIN) ."\" /></p>\n";
				echo "<p><input type=\"button\" onclick=\"rb_license_insertshortcodenoval()\" value=\"". __("Insert Shortcode No Attribute", rb_license_TEXTDOMAIN) ."\" /></p>\n";
				echo "</div>\n";
			}
			
			/* Prints the edit form for pre-WordPress 2.5 post/page */
			function rb_license_old_custom_box() {
				echo '<div class="dbx-b-ox-wrapper">' . "\n";
				echo '<fieldset id="rb_license_fieldsetid" class="dbx-box">' . "\n";
				echo "<div class=\"dbx-h-andle-wrapper\"><h3 class=\"dbx-handle\">". __("Profile", rb_license_TEXTDOMAIN) ."</h3></div>";   
				echo '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';
				// output editing form
				rb_license_inner_custom_box();
				// end wrapper
				echo "</div></div></fieldset></div>\n";
			}
			
	} // End Admin Actions



// *************************************************************************************************** //
// Add Widgets

	// View Record Detail
	add_action('widgets_init', create_function('', 'return register_widget("rb_license_widget_detail");'));
	  class rb_license_widget_detail extends WP_Widget {
			
		// Setup
		function rb_license_widget_detail() {
			$widget_ops = array('classname' => 'rb_license_widget_detail', 'description' => __("Displays record detail", rb_license_TEXTDOMAIN) );
			$this->WP_Widget('rb_license_widget_detail', __("RB License Detail", rb_license_TEXTDOMAIN), $widget_ops);
		}
	
		// What Displays
		function widget($args, $instance) {		
			extract($args, EXTR_SKIP);
			echo $before_widget;
			$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
				if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };		
			$count = $instance['recordid'];
				if ( empty( $recordid ) ) { $recordid = 1; };		
				
			if (function_exists('rb_license_detail')) { 
				$atts = array('recordid' => $recordid);
				rb_license_detail($atts); 
			} else {
				echo "Invalid Function";
			}
			echo $after_widget;
		}
	
		// Update
		function update($new_instance, $old_instance) {				
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['recordid'] = strip_tags($new_instance['recordid']);
			return $instance;
		}
	
		// Form
		function form($instance) {				
			$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title = esc_attr($instance['title']);
			$count = esc_attr($instance['count']);
			
			echo "<p><label for=\"". $this->get_field_id('title') ."\">\"". __('Title:') ."\" <input class=\"widefat\" id=\"". $this->get_field_id('title') ."\" name=\"". $this->get_field_name('title') ."\" type=\"text\" value=\"". $title ."\" /></label></p>\n";
			echo "<p><label for=\"". $this->get_field_id('recordid') ."\">\"". __('Show Record ID:') ."\" <input id=\"". $this->get_field_id('recordid') ."\" name=\"". $this->get_field_name('recordid') ."\" type=\"text\" value=\"". $recordid ."\" /></label></p>\n";
		}
		
	  } // End Widget Class


// *************************************************************************************************** //
// Add Short Codes

	// Add [license_list] shortcode
	add_shortcode("license_list","rb_license_shortcode_list");
		function rb_license_shortcode_list($atts, $content = null){
			ob_start();
			rb_license_list($atts);
			$output_string = ob_get_contents();;
			ob_end_clean();
			return $output_string;
		}

	// Add [license_detail id=1] shortcode
	add_shortcode("license_detail","rb_license_shortcode_detail");
		function rb_license_shortcode_detail($atts, $content = null){
			ob_start();
			rb_license_detail($atts);
			$output_string = ob_get_contents();;
			ob_end_clean();
			return $output_string;
		}



// *************************************************************************************************** //
// Intercept Post Data (Rarely used)
	/*
		//Intercept to check for submitted data via one of the forms or from PayPal
		add_action('init', rb_license_eval_postdata);
			function rb_license_eval_postdata() {
				if (isset($_POST['rb_license_step1']) || isset($_POST['rb_license_step2'])) {
					include("LICENSE.php");
				}
				if (isset($_GET['rb_license_step3'])) {
					include("LICENSE.php");
				}
			}
	*/


/****************************************************************/
//Uninstall
	function rb_license_uninstall() {
		// Required for all WordPress database manipulations
		global $wpdb;
		
		register_uninstall_hook(__FILE__, 'rb_license_uninstall_action');
			function rb_license_uninstall_action() {
				//delete_option('create_my_taxonomies');
			}
	
		// Drop the tables
		$wpdb->query("DROP TABLE " . table_license_license);
		$wpdb->query("DROP TABLE " . table_license_product);

	
		// Final Cleanup
		delete_option('rb_license_options');
			
		$thepluginfile="rb-license/rb-license.php";
		$current = get_settings('active_plugins');
		array_splice($current, array_search( $thepluginfile, $current), 1 );
		update_option('active_plugins', $current);
		do_action('deactivate_' . $thepluginfile );
	
		echo "<div style=\"padding:50px;font-weight:bold;\"><p>". __("Almost done...", rb_license_TEXTDOMAIN) ."</p><h1>". __("One More Step", rb_license_TEXTDOMAIN) ."</h1><a href=\"plugins.php?deactivate=true\">". __("Please click here to complete the uninstallation process", rb_license_TEXTDOMAIN) ."</a></h1></div>";
		die;
	}
?>