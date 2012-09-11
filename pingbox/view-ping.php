<?php
global $wpdb;

$ObjPost = json_decode(file_get_contents('php://input'),true);

// Create Record
  $result = mysql_query("SELECT * FROM ".table_license_license." WHERE LicenseURL = '".$wpdb->escape($ObjPost["client_domain"])."'");
  $count = mysql_num_rows($result);
  
  if($count <= 0){
   $insert = "INSERT INTO " . table_license_license ." (LicenseTitle, LicenseVersion, LicenseText, LicenseType, LicenseURL, LicenseClientName, LicenseClientEmail, LicenseDateUpdated, LicenseActive)" .
   "VALUES ('" . $wpdb->escape($ObjPost["client_sitename"]) . "','" . $wpdb->escape($ObjPost["client_plugin_version"]) . "','','".$wpdb->escape($ObjPost["client_plugin_name"])."','".$wpdb->escape($ObjPost["client_domain"])."','" . $wpdb->escape($ObjPost["client_sitename"]) . "','" . $wpdb->escape($ObjPost["client_admin_email"]) . "',now(),'0')";
   $results = $wpdb->query($insert);
  }
  
  exit;
?>