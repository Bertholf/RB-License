<?php 
global $wpdb;
define("LabelPlural", "Licenses");
define("LabelSingular", "License");
define("Required", "onfocus=\"this.value=='Required' ? this.value='' : '';\"  onblur=\"this.value=='' ? this.value='Required' : '';\"");

if (isset($_POST['action'])) {

	$LicenseAutoID			=$_POST['LicenseAutoID'];
	$LicenseTitle			=$_POST['LicenseTitle'];
	$LicenseVersion			=$_POST['LicenseVersion'];
	$LicenseText			=$_POST['LicenseText'];
	$LicenseType			=$_POST['LicenseType'];
	$LicenseURL				=$_POST['LicenseURL'];
	$LicenseClientName		=$_POST['LicenseClientName'];
	$LicenseClientEmail		=$_POST['LicenseClientEmail'];
	$LicenseDateCreated		=$_POST['LicenseDateCreated'];
	$LicenseDateUpdated		=$_POST['LicenseDateUpdated'];
	$LicenseActive			=$_POST['LicenseActive'];

	// Error checking
	$error = "";
	$have_error = false;
	if(trim($LicenseTitle) == ""){
		$error .= "<b><i>". LabelSingular ." Name is required</i></b><br>";
		$have_error = true;
	}
	
	$action = $_POST['action'];
	switch($action) {

	// Add
	case 'addRecord':
	
		if($have_error){
        	echo "<div id=\"message\" class=\"error\"><p>". __("Error creating ". LabelSingular, rb_license_TEXTDOMAIN) ."., ". __("please ensure you have filled out all required fields", rb_license_TEXTDOMAIN) .".</p><p>".$error."</p></div>\n"; 
		} else { // Good to go...

			// Create Record
			$insert = "INSERT INTO " . table_license_license .
				" (LicenseTitle, LicenseVersion, LicenseText, LicenseType, LicenseURL, LicenseClientName, LicenseClientEmail, LicenseDateUpdated, LicenseActive)" .
				"VALUES ('" . $wpdb->escape($LicenseTitle) . "','" . $wpdb->escape($LicenseVersion) . "','" . $wpdb->escape($LicenseText) . "','" . $wpdb->escape($LicenseType) . "','" . $wpdb->escape($LicenseURL) . "','" . $wpdb->escape($LicenseName) . "','" . $wpdb->escape($LicenseEmail) . "','" . $wpdb->escape($LicenseDateUpdated) . "','" . $wpdb->escape($LicenseActive) . "')";
		    $results = $wpdb->query($insert);
			$LicenseAutoID = $wpdb->insert_id;

        	echo "<div id=\"message\" class=\"updated\"><p>". __("New ". LabelSingular ." added successfully", rb_license_TEXTDOMAIN) ."!  <a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&LicenseAutoID=". $LicenseAutoID ."\">". __("You may now load information to the record", rb_license_TEXTDOMAIN) ."</a>.</p></div>\n"; 
		}
		rb_display_list();
		exit;
	break;
	
	// Edit
	case 'editRecord':
		if($have_error || empty($LicenseAutoID)){
        	echo "<div id=\"message\" class=\"error\"><p>". __("Error creating ". LabelSingular, rb_license_TEXTDOMAIN) .", ". __("please ensure you have filled out all required fields", rb_license_TEXTDOMAIN) .".</p><p>".$error."</p></div>\n"; 
		} else { // Good to go...

			// Update Record
			$update = "UPDATE " . table_license_license . " SET 
				LicenseTitle='" . $wpdb->escape($LicenseTitle) . "',
				LicenseVersion='" . $wpdb->escape($LicenseVersion) . "',
				LicenseText='" . $wpdb->escape($LicenseText) . "',
				LicenseType='" . $wpdb->escape($LicenseType) . "',
				LicenseURL='" . $wpdb->escape($LicenseURL) . "',
				LicenseClientName='" . $wpdb->escape($LicenseClientName) . "',
				LicenseClientEmail='" . $wpdb->escape($LicenseClientEmail) . "',
				LicenseDateCreated='" . $wpdb->escape($LicenseDateCreated) . "',
				LicenseDateUpdated='" . $wpdb->escape($LicenseDateUpdated) . "',
				LicenseActive='" . $wpdb->escape($LicenseActive) . "'
				WHERE LicenseAutoID=$LicenseAutoID";
			$results = $wpdb->query($update);

		  echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." updated successfully", rb_license_TEXTDOMAIN) ."!</p></div>\n";
		}
		
		rb_display_list();
		exit;
	break;

	// Delete bulk
	case 'deleteRecord':
		foreach($_POST as $LicenseAutoID) {
		  if (is_numeric($LicenseAutoID)) {
			// Verify Record
			$queryDelete = "SELECT LicenseAutoID, LicenseTitle FROM ". table_license_license ." WHERE LicenseAutoID =  \"". $LicenseAutoID ."\"";
			$resultsDelete = mysql_query($queryDelete);
			while ($dataDelete = mysql_fetch_array($resultsDelete)) {
		
				// Remove Record
				$delete = "DELETE FROM " . table_license_license . " WHERE LicenseAutoID = \"". $LicenseAutoID ."\"";
				$results = $wpdb->query($delete);
				
			echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['LicenseTitle'] ."</strong> deleted successfully", rb_license_TEXTDOMAIN) ."!</p></div>\n";
					
			} // is there record?
		  } // it was numeric
		}
		rb_display_list();
		exit;
	break;
	
	}
}
elseif ($_GET['action'] == "deleteRecord") {
	$LicenseAutoID = $_GET['LicenseAutoID'];
	if (is_numeric($LicenseAutoID)) {
		// Verify Record
		$queryDelete = "SELECT LicenseAutoID, LicenseTitle FROM ". table_license_license ." WHERE LicenseAutoID =  \"". $LicenseAutoID ."\"";
		$resultsDelete = mysql_query($queryDelete);
		while ($dataDelete = mysql_fetch_array($resultsDelete)) {
		
			// Remove Record
			$delete = "DELETE FROM " . table_license_license . " WHERE LicenseAutoID = \"". $LicenseAutoID ."\"";
			$results = $wpdb->query($delete);
			
		echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['LicenseTitle'] ."</strong> deleted successfully", rb_license_TEXTDOMAIN) ."!</p></div>\n";
				
		} // is there record?
	} // it was numeric
	rb_display_list();

}
elseif (($_GET['action'] == "editRecord") || ($_GET['action'] == "add")) {
	$action = $_GET['action'];
	$LicenseAutoID = $_GET['LicenseAutoID'];

	rb_display_manage($LicenseAutoID);

} else {
	rb_display_list();
}


/* Manage Record *****************************************************/

function rb_display_manage($LicenseAutoID) {
  global $wpdb;

  echo "<div class=\"wrap\">\n";
  echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
  echo "  <h2>". __("Manage ". LabelSingular, rb_license_TEXTDOMAIN) ."</h2>\n";
  echo "  <p><a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."\">". __("Back to ". LabelSingular ." List", rb_license_TEXTDOMAIN) ."</a></p>\n";

  if ( !empty($LicenseAutoID) && ($LicenseAutoID > 0) ) {

	$query = "SELECT * FROM " . table_license_license . " WHERE LicenseAutoID='$LicenseAutoID'";
	$results = mysql_query($query) or die (__('Error, query failed', rb_license_TEXTDOMAIN));
	$count = mysql_num_rows($results);
	while ($data = mysql_fetch_array($results)) {
		$LicenseAutoID		= $data['LicenseAutoID'];
		$LicenseTitle		= stripslashes($data['LicenseTitle']);
		$LicenseVersion		= stripslashes($data['LicenseVersion']);
		$LicenseText		= stripslashes($data['LicenseText']);
		$LicenseType		= $data['LicenseType'];
		$LicenseURL			= $data['LicenseURL'];
		$LicenseClientName	= $data['LicenseClientName'];
		$LicenseClientEmail	= $data['LicenseClientEmail'];
		$LicenseDateCreated	= $data['LicenseDateCreated'];
		$LicenseDateUpdated = $data['LicenseDateUpdated'];
		$LicenseActive		= $data['LicenseActive'];
	} 
	
	echo "<h3 class=\"title\">". __("Edit", rb_license_TEXTDOMAIN) ." ". LabelSingular ."</h3>\n";
	echo "<p>". __("Make changes in the form below to edit a", rb_license_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", rb_license_TEXTDOMAIN) ."Required fields are marked *</strong></p>\n";

  } else {
		$LicenseAutoID		= 0;
		$LicenseTitle		= "";
		$LicenseText		= "";
		$LicenseType		= 0;
		$LicenseURL		= "";
		$LicenseDateCreated	= "";
		$LicenseDateUpdated	= "";
		$LicenseActive		= 1;

	echo "<h3 class=\"title\">Add New ". LabelSingular ."</h3>\n";
	echo "<p>". __("Fill in the form below to add a new", rb_license_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", rb_license_TEXTDOMAIN) ." *</strong></p>\n";
  } 
	echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
	echo "<table class=\"form-table\">\n";
	echo "<tbody>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Website Name", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"LicenseTitle\" name=\"LicenseTitle\" value=\"". $LicenseTitle ."\" ". Required ." class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Version Installed", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"LicenseVersion\" name=\"LicenseVersion\" value=\"". $LicenseVersion ."\" ". Required ." class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Notes", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><textarea id=\"LicenseText\" name=\"LicenseText\" class=\"rbformitem rbformtextarea\">". $LicenseText ."</textarea></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Authorized URL", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"LicenseURL\" name=\"LicenseURL\" value=\"". $LicenseURL ."\" class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Client Name", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"LicenseClientName\" name=\"LicenseClientName\" value=\"". $LicenseClientName ."\" class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Client Email", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"LicenseClientEmail\" name=\"LicenseClientEmail\" value=\"". $LicenseClientEmail ."\" class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	/*
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Date Created", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"LicenseDateCreated\" name=\"LicenseDateCreated\" value=\"". $LicenseDateCreated ."\" class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	*/
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Date Upgraded", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"LicenseDateUpdated\" name=\"LicenseDateUpdated\" value=\"". $LicenseDateUpdated ."\" class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Type", rb_license_TEXTDOMAIN) .":</th>\n";
	echo "        <td><select name=\"LicenseType\" id=\"LicenseType\" class=\"rbformitem rbformselect\">\n";
	
					$query1 = "SELECT ProductID, ProductTitle FROM ". table_license_product ." ORDER BY ProductTitle";
					$results1 = mysql_query($query1);
					$count1 = mysql_num_rows($results1);
					
					if ($count1 > 0) {
						if (empty($LicenseType) || ($LicenseType < 1) ) {
							echo " <option value=\"0\" selected>--</option>\n";
						}
						while ($data1 = mysql_fetch_array($results1)) {
							echo " <option value=\"". $data1["ProductID"] ."\" ". selected($LicenseActive, $data1["ProductID"]) .">". $data1["ProductTitle"] ."</option>\n";
						}
						echo "</select>\n";
					} else {
						// No Types Loaded
						echo "". __("No Types Identified", rb_license_TEXTDOMAIN) .".";
					}
					
	echo "        </td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Status", rb_emp_TEXTDOMAIN) .":</th>\n";
	echo "        <td><select id=\"LicenseActive\" name=\"LicenseActive\" class=\"rbformitem rbformselect\">\n";
	echo "			  <option value=\"1\"". selected(1, $LicenseActive) .">Active</option>\n";
	echo "			  <option value=\"0\"". selected(0, $LicenseActive) .">Inactive</option>\n";
	echo "          </select></td>\n";
	echo "    </tr>\n";
	echo "  </tbody>\n";
	echo "</table>\n";

	if ( !empty($LicenseAutoID) && ($LicenseAutoID > 0) ) {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"LicenseAutoID\" value=\"". $LicenseAutoID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Update Record", rb_license_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} else {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Create Record", rb_license_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} 
	echo "</form>\n";
	
    echo "</div>\n";

} // End Manage


/* List Records *****************************************************/

function rb_display_list() {  

  echo "<div class=\"wrap\">\n";
  echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
  echo "  <h2>". __("List", rb_license_TEXTDOMAIN) ." ". LabelPlural ."</h2>\n";
	
  echo "  <h3 class=\"title\">". __("All Records", rb_license_TEXTDOMAIN) ."</h3>\n";
  echo "  <p><a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=add\">". __("Create New Record", rb_license_TEXTDOMAIN) ."</a></p>\n";

        global $wpdb;
		
		// Sort By
        $sort = "";
        if (isset($_GET['sort']) && !empty($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        else {
            $sort = "LicenseDateCreated";
        }
		
		// Sort Order
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
		echo "        <th class=\"column\" scope=\"col\"><a href=\"admin.php?page=". $_GET['page'] ."&sort=LicenseTitle&dir=". $sortDirection ."\">". __("Client", rb_license_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"admin.php?page=". $_GET['page'] ."&sort=LicenseURL&dir=". $sortDirection ."\">". __("URL", rb_license_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"admin.php?page=". $_GET['page'] ."&sort=LicenseName&dir=". $sortDirection ."\">". __("Name", rb_license_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"admin.php?page=". $_GET['page'] ."&sort=LicenseActive&dir=". $sortDirection ."\">". __("Active", rb_license_TEXTDOMAIN) ."</a></th>\n";
		echo "    </tr>\n";
		echo "</thead>\n";
		echo "<tfoot>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\" columnmanage-column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Client", rb_license_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("URL", rb_license_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Name", rb_license_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Active", rb_license_TEXTDOMAIN) ."</th>\n";
		echo "    </tr>\n";
		echo "</tfoot>\n";
		echo "<tbody>\n";
		
        $query = "SELECT * FROM ". table_license_license ." $filter ORDER BY $sort $dir";
        $results = mysql_query($query);
        $count = mysql_num_rows($results);
		
        while ($data = mysql_fetch_array($results)) {
            $LicenseAutoID = $data['LicenseAutoID'];
            $LicenseTitle = stripslashes($data['LicenseTitle']);
            if ($data['LicenseActive'] == 0) { $rowColor = " style='background: #FFEBE8'"; } else { $rowColor = ""; }

		echo "    <tr". $rowColor .">\n";
		echo "        <th class=\"check-column\" scope=\"row\"><input type=\"checkbox\" class=\"administrator\" id=\"". $LicenseAutoID ."\" name=\"". $LicenseAutoID ."\" value=\"". $LicenseAutoID ."\" /></th>\n";
		echo "        <td class=\"column\">". stripslashes($data['LicenseTitle']) ."\n";
		echo "          <div class=\"row-actions\">\n";
		echo "            <span class=\"edit\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;LicenseAutoID=". $LicenseAutoID ."\" title=\"". __("Edit this Record", rb_license_TEXTDOMAIN) . "\">". __("Edit", rb_license_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;LicenseAutoID=". $LicenseAutoID ."\"  onclick=\"if ( confirm('". __("You are about to delete this ". LabelSingular, rb_license_TEXTDOMAIN) . ".\'". __("Cancel", rb_license_TEXTDOMAIN) . "\' ". __("to stop", rb_license_TEXTDOMAIN) . ", \'". __("OK", rb_license_TEXTDOMAIN) . "\' ". __("to delete", rb_license_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"". __("Delete this Record", rb_license_TEXTDOMAIN) . "\">". __("Delete", rb_license_TEXTDOMAIN) . "</a> </span>\n";
		echo "          </div>\n";
		echo "        </td>\n";
		echo "        <td class=\"column\">". stripslashes($data['LicenseURL']) ."</td>\n";
		echo "        <td class=\"column\"><a href='mailto:". stripslashes($data['LicenseClientEmail']) ."'>". stripslashes($data['LicenseClientName']) ."</a></td>\n";
		echo "        <td class=\"column\">". stripslashes($data['LicenseVersion']) ."</td>\n";
		echo "    </tr>\n";
        }
		mysql_free_result($results);
		if ($count < 1) {
			if (isset($filter)) { 
	echo "    <tr>\n";
	echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
	echo "        <td class=\"column\" colspan=\"4\"><p>". __("No ". LabelPlural ." found with this criteria", rb_license_TEXTDOMAIN) . ".</p></td>\n";
	echo "    </tr>\n";
			} else {
	echo "    <tr>\n";
	echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
	echo "        <td class=\"column\" colspan=\"4\"><p>". __("There aren't any ". LabelPlural ." loaded yet", rb_license_TEXTDOMAIN) . "!</p></td>\n";
	echo "    </tr>\n";
			}
        } 
	echo "</tbody>\n";
	echo "</table>\n";
	echo "<p class=\"submit\">\n";
	echo "    <input type=\"hidden\" name=\"action\" value=\"deleteRecord\" />\n";
	echo "    <input type=\"submit\" name=\"submit\" value=\"". __("Delete", rb_license_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
	echo "</p>\n";

    echo "</form>\n";
    echo "</div>\n";
}
?>