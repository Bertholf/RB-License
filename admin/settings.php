<?php
global $wpdb;

// *************************************************************************************************** //
// Top Client

    echo "<div class=\"wrap\">\n";
    echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
    echo "  <h2>". __("Settings", rb_license_TEXTDOMAIN) . "</h2>\n";
    echo "  <strong>\n";
    echo "  	<a class=\"button-primary\" href=\"". admin_url("admin.php?page=". $_GET["page"] ."&ConfigID=0") ."\">". __("Overview", rb_license_TEXTDOMAIN) . "</a> | \n";
    echo "  	<a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET["page"] ."&ConfigID=1") ."\">". __("Features", rb_license_TEXTDOMAIN) . "</a> | \n";
    echo "  	<a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET["page"] ."&ConfigID=2") ."\">". __("Types", rb_license_TEXTDOMAIN) . "</a> | \n";
    echo "  	<a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET["page"] ."&ConfigID=99") ."\">". __("Uninstall", rb_license_TEXTDOMAIN) . "</a>\n";
    echo "  </strong>\n";
	echo "  <hr />\n";
  
if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) {
	if($_REQUEST['action'] == 'douninstall') {
		rb_license_uninstall();
	}
}

if(!isset($_REQUEST['ConfigID']) && empty($_REQUEST['ConfigID'])){ $ConfigID=0;} else { $ConfigID=$_REQUEST['ConfigID']; }

if ($ConfigID == 0) {
	
// *************************************************************************************************** //
// Overview Page

    echo "	  <h3>Overview</h3>\n";
    echo "      <ul>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=0\">". __("Overview", rb_license_TEXTDOMAIN) . "</a></li>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=1\">". __("Features", rb_license_TEXTDOMAIN) . "</a></li>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=2\">". __("Types", rb_license_TEXTDOMAIN) . "</a></li>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=99\">". __("Uninstall", rb_license_TEXTDOMAIN) . "</a></li>\n";
    echo "      </ul>\n";

	echo "<hr />\n";
}
elseif ($ConfigID == 1) {

// *************************************************************************************************** //
// Manage Settings

    echo "<h3>". __("Settings", rb_license_TEXTDOMAIN) . "</h3>\n";

		echo "<form method=\"post\" action=\"options.php\">\n";
		settings_fields( 'rb-license-settings-group' ); 
        //do_settings( 'rb-license-settings-group' );
		$rb_license_options_arr = get_option('rb_license_options');

		// If Value 1 has no value, add a value... now thats value!
		$rb_license_option_name1 = $rb_license_options_arr['rb_license_option_name1'];
			if (empty($rb_license_option_name1)) { $rb_license_option_name1 = "Default Value 1"; }

		// Begin
		echo "<table class=\"form-table\">\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">"; _e('Database Version', rb_license_TEXTDOMAIN); echo "</th>\n";
		echo "   <td><input name=\"rb_license_version\" value=\"". rb_license_VERSION ."\" disabled /></td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Option 1', rb_license_TEXTDOMAIN); echo "</th>\n";
		echo "   <td><input name=\"rb_license_options[rb_license_option_name1]\" value=\"". $rb_license_option_name1 ."\" class=\"rbformitem rbformtext\" /></td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Option 2', rb_license_TEXTDOMAIN); echo "</th>\n";
		echo "   <td><input name=\"rb_license_options[rb_license_option_name2]\" value=\"". $rb_license_options_arr['rb_license_option_name2'] ."\" class=\"rbformitem rbformtext\" /></td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Option 3', rb_license_TEXTDOMAIN); echo "</th>\n";
		echo "   <td><input name=\"rb_license_options[rb_license_option_name3]\" value=\"". $rb_license_options_arr['rb_license_option_name3'] ."\" class=\"rbformitem rbformtext\" /></td>\n";
		echo " </tr>\n";
		
		/* Options 
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" colspan=\"2\"><h3>". __('Location Options', rb_license_TEXTDOMAIN); echo "</h3></th>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Default Country', rb_license_TEXTDOMAIN) ."</th>\n";
		echo "   <td><input name=\"rb_license_options[rb_license_option_locationcountry]\" value=\"". $rb_license_option_locationcountry ."\" class=\"rbforminput\" /></td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Server Timezone', rb_license_TEXTDOMAIN) ."</th>\n";
		echo "   <td>\n";
		echo "     <select name=\"rb_license_options[rb_license_option_locationtimezone]\" class=\"rbformitem rbformselect\">\n";
		echo "       <option value=\"+12\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +12) ."> UTC+12</option>\n";
		echo "       <option value=\"+11\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +11) ."> UTC+11</option>\n";
		echo "       <option value=\"+10\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +10) ."> UTC+10</option>\n";
		echo "       <option value=\"+9\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +9) ."> UTC+9</option>\n";
		echo "       <option value=\"+8\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +8) ."> UTC+8</option>\n";
		echo "       <option value=\"+7\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +7) ."> UTC+7</option>\n";
		echo "       <option value=\"+6\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +6) ."> UTC+6</option>\n";
		echo "       <option value=\"+5\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +5) ."> UTC+5</option>\n";
		echo "       <option value=\"+4\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +4) ."> UTC+4</option>\n";
		echo "       <option value=\"+3\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +3) ."> UTC+3</option>\n";
		echo "       <option value=\"+2\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +2) ."> UTC+2</option>\n";
		echo "       <option value=\"+1\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], +1) ."> UTC+1</option>\n";
		echo "       <option value=\"0\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], 0) ."> UTC 0</option>\n";
		echo "       <option value=\"-1\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -1) ."> UTC-1</option>\n";
		echo "       <option value=\"-2\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -2) ."> UTC-2</option>\n";
		echo "       <option value=\"-3\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -3) ."> UTC-3</option>\n";
		echo "       <option value=\"-4\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -4) ."> UTC-4</option>\n";
		echo "       <option value=\"-5\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -5) ."> UTC-5</option>\n";
		echo "       <option value=\"-6\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -6) ."> UTC-6</option>\n";
		echo "       <option value=\"-7\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -7) ."> UTC-7</option>\n";
		echo "       <option value=\"-8\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -8) ."> UTC-8</option>\n";
		echo "       <option value=\"-9\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -9) ."> UTC-9</option>\n";
		echo "       <option value=\"-10\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -10) ."> UTC-10</option>\n";
		echo "       <option value=\"-11\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -11) ."> UTC-11</option>\n";
		echo "       <option value=\"-12\" ". selected($rb_license_options_arr['rb_license_option_locationtimezone'], -12) ."> UTC-12</option>\n";
		echo "     </select> (<a href=\"http://www.worldtimezone.com/index24.php\" target=\"_license\">Find</a>)\n";
		echo "   </td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Show Checkbox', rb_license_TEXTDOMAIN) ."</th>\n";
		echo "   <td>\n";
		echo "     <input type=\"checkbox\" name=\"rb_license_options[rb_license_option_showcheckbox]\" value=\"1\" "; checked($rb_license_options_arr['rb_license_option_showcheckbox'], 1); echo "/> Example Checkbox<br />\n";
		echo "   </td>\n";
		echo " </tr>\n";
		*/
		
		
		echo "</table>\n";
		echo "<input type=\"submit\" class=\"button-primary\" value=\""; _e('Save Changes'); echo "\" />\n";
		
		echo "</form>\n";


}	 // End	
elseif ($ConfigID == 2) {
// *************************************************************************************************** //
// Manage Types

	/** Identify Labels **/
	define("LabelPlural", __("Types", rb_license_TEXTDOMAIN));
	define("LabelSingular", __("Type", rb_license_TEXTDOMAIN));

  /* Initial Registration [RESPOND TO POST] ***********/ 
  if ( isset($_POST['action']) ) {
	
		$ProductID 	= $_POST['ProductID'];
		$ProductTitle 	= $_POST['ProductTitle'];
		$ProductVersion 		= $_POST['ProductVersion'];

		// Error checking
		$error = "";
		$have_error = false;
		if(trim($ProductTitle) == ""){
			$error .= "<b><i>". __(LabelSingular ." name is required", rb_license_TEXTDOMAIN) . ".</i></b><br>";
			$have_error = true;
		}

		$action = $_POST['action'];
		switch($action) {
	
		// Add
		case 'addRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", rb_license_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
			} else {
		
				// Create Record
				$insert = "INSERT INTO ". table_license_product ." (ProductTitle, ProductVersion) VALUES ('" . $wpdb->escape($ProductTitle) . "', '" . $wpdb->escape($ProductVersion) . "')";
				$results = $wpdb->query($insert);
				$lastid = $wpdb->insert_id;
				
				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>added</strong> successfully! You may now %1$s Load Information to the record", rb_license_TEXTDOMAIN), LabelSingular, "<a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&LoginTypeID=". $lastid ."\">") .".</a></p><p>".$error."</p></div>"); 
			}

		break;
	
		// Manage
		case 'editRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", rb_license_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
			} else {
				$update = "UPDATE ". table_license_product ." 
							SET 
								ProductTitle='". $wpdb->escape($ProductTitle) ."', 
								ProductVersion='". $wpdb->escape($ProductVersion) ."'
							WHERE ProductID='$ProductID'";
				$updated = $wpdb->query($update);

				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>updated</strong> successfully", rb_license_TEXTDOMAIN), LabelSingular) ."!</p><p>".$error."</p></div>"); 
		
				// Clear It
				$ProductID	= 0;
				$ProductTitle		= "";
				$ProductVersion		= "";
			}
		break;

		// Delete bulk
		case 'deleteRecord':
			foreach($_POST as $ProductID) {
			  if (is_numeric($ProductID)) {
				// Verify Record
				$queryDelete = "SELECT ProductID, ProductTitle FROM ". table_license_product ." WHERE ProductID =  \"". $ProductID ."\"";
				$resultsDelete = mysql_query($queryDelete);
				while ($dataDelete = mysql_fetch_array($resultsDelete)) {
			
					// Remove Record
					$delete = "DELETE FROM " . table_license_product . " WHERE ProductID = \"". $ProductID ."\"";
					$results = $wpdb->query($delete);
					
					echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['ProductTitle'] ."</strong> deleted successfully", rb_license_TEXTDOMAIN) ."!</p></div>\n";
						
				} // while
			  } // it was numeric
			} // for each
		break;

		} // Switch
		
  } // Action Post
  elseif ($_GET['action'] == "deleteRecord") {
	
	$ProductID = $_GET['ProductID'];

	  if (is_numeric($ProductID)) {
		// Verify Record
		$queryDelete = "SELECT ProductID, ProductTitle FROM ". table_license_product ." WHERE ProductID =  \"". $ProductID ."\"";
		$resultsDelete = mysql_query($queryDelete);
		while ($dataDelete = mysql_fetch_array($resultsDelete)) {
	
			// Remove Record
			$delete = "DELETE FROM " . table_license_product . " WHERE ProductID = \"". $ProductID ."\"";
			$results = $wpdb->query($delete);
			
			echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['ProductTitle'] ."</strong> deleted successfully", rb_license_TEXTDOMAIN) ."!</p></div>\n";
				
		} // is there record?
	  } // it was numeric
  }
  elseif ($_GET['action'] == "editRecord") {

		$action = $_GET['action'];
		$ProductID = $_GET['ProductID'];
		
		if ( $ProductID > 0) {

			$query = "SELECT * FROM ". table_license_product ." WHERE ProductID='$ProductID'";
			$results = mysql_query($query) or die (__('Error, query failed', rb_license_TEXTDOMAIN));
			$count = mysql_num_rows($results);
			while ($data = mysql_fetch_array($results)) {
				$ProductID	=$data['ProductID'];
				$ProductTitle		=stripslashes($data['ProductTitle']);
			} 
		
  			echo "<p><a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET["page"] ."&ConfigID=2") ."\">". __("Create New ". LabelSingular, rb_license_TEXTDOMAIN) ."</a></p>\n";
			echo "<h3 class=\"title\">". sprintf(__("Edit %1$s", rb_license_TEXTDOMAIN), LabelPlural) ."</h3>\n";
			echo "<p>". sprintf(__("Fill in the form below to add a new record %1$s", rb_license_TEXTDOMAIN), LabelPlural) .". <strong>". __("Required fields are marked", rb_license_TEXTDOMAIN) ." *</strong></p>\n";
		}
  } else {
		
			$ProductID	= 0;
			$ProductTitle		= "";
			$ProductVersion		= "";
			
			echo "<h3>". sprintf(__("Create New %1$s", rb_license_TEXTDOMAIN), LabelPlural) ."</h3>\n";
			echo "<p>". __("Make changes in the form below to edit a ", rb_license_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", rb_license_TEXTDOMAIN) ." *</strong></p>\n";
  }
  
	echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
	echo "<table class=\"form-table\">\n";
	echo "<tbody>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Title", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"ProductTitle\" name=\"ProductTitle\" value=\"". $ProductTitle ."\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Version", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"ProductVersion\" name=\"ProductVersion\" value=\"". $ProductVersion ."\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Price", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"ProductPrice\" name=\"ProductPrice\" value=\"". $ProductPrice ."\" /></td>\n";
	echo "    </tr>\n";
	echo "  </tbody>\n";
	echo "</table>\n";

	if ( $ProductID > 0) {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"ProductID\" value=\"". $ProductID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Update Record", rb_license_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} else {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Create Record", rb_license_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} 
	echo "</form>\n";
	

	echo "<hr />\n";
	echo "<h3 class=\"title\">". __("All Records", rb_license_TEXTDOMAIN) ."</h3>\n";
	
		/******** Sort Order ************/
		$sort = "";
		if (isset($_GET['sort']) && !empty($_GET['sort'])){
			$sort = $_GET['sort'];
		}
		else {
			$sort = "ProductTitle";
		}
		
		/******** Direction ************/
		$dir = "";
		if (isset($_GET['dir']) && !empty($_GET['dir'])){
			$dir = $_GET['dir'];
			if ($dir == "desc" || !isset($dir) || empty($dir)){
			   $sortDirection = "asc";
			   } else {
			   $sortDirection = "desc";
			} 
		} else {
			   $sortDirection = "desc";
			   $dir = "asc";
		}
	
		echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";	
		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "<thead>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\"manage-column column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=ProductTitle&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Title", rb_license_TEXTDOMAIN) ."</a></th>\n";
		echo "    </tr>\n";
		echo "</thead>\n";
		echo "<tfoot>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\" columnmanage-column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Title", rb_license_TEXTDOMAIN) ."</th>\n";
		echo "    </tr>\n";
		echo "</tfoot>\n";
		echo "<tbody>\n";
	
		$query = "SELECT * FROM ". table_license_product ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", rb_license_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			$ProductID	=$data['ProductID'];
		echo "    <tr>\n";
		echo "        <th class=\"check-column\" scope=\"row\"><input type=\"checkbox\" class=\"administrator\" id=\"". $ProductID ."\" name=\"". $ProductID ."\" value=\"". $ProductID ."\" /></th>\n";
		echo "        <td class=\"column\">". stripslashes($data['ProductTitle']) ."\n";
		echo "          <div class=\"row-actions\">\n";
		echo "            <span class=\"edit\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;ProductID=". $ProductID ."&amp;ConfigID=". $ConfigID ."\" title=\"". __("Edit this Record", rb_license_TEXTDOMAIN) . "\">". __("Edit", rb_license_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;ProductID=". $ProductID ."&amp;ConfigID=". $ConfigID ."\"  onclick=\"if ( confirm('". __("You are about to delete this ". LabelSingular, rb_license_TEXTDOMAIN) . ".\'". __("Cancel", rb_license_TEXTDOMAIN) . "\' ". __("to stop", rb_license_TEXTDOMAIN) . ", \'". __("OK", rb_license_TEXTDOMAIN) . "\' ". __("to delete", rb_license_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"". __("Delete this Record", rb_license_TEXTDOMAIN) . "\">". __("Delete", rb_license_TEXTDOMAIN) . "</a> </span>\n";
		echo "          </div>\n";
		echo "        </td>\n";
		echo "    </tr>\n";
		}
		mysql_free_result($results);
		if ($count < 1) {
		echo "    <tr>\n";
		echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
		echo "        <td class=\"column\" colspan=\"2\"><p>". __("There aren't any records loaded yet", rb_license_TEXTDOMAIN) . "!</p></td>\n";
		echo "    </tr>\n";
		}
		echo "</tbody>\n";
		echo "</table>\n";
		echo "<p class=\"submit\">\n";
		echo "    <input type=\"hidden\" name=\"action\" value=\"deleteRecord\" />\n";
		echo "    <input type=\"submit\" name=\"submit\" value=\"". __("Delete", rb_license_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "</p>\n";
		
   		echo "</form>\n";


}	 // End	
elseif ($ConfigID == 99) {
	
	echo "    <h3>". __("Uninstall", rb_license_TEXTDOMAIN) ."</h3>\n";
	echo "    <div>". __("Are you sure you want to uninstall?", rb_license_TEXTDOMAIN) ."</div>\n";
	echo "	<div><a href=\"?page=". $_GET["page"] ."&action=douninstall\">". __("Yes! Uninstall", rb_license_TEXTDOMAIN) ."</a></div>\n";

}	 // End	
echo "</div>\n";
?>