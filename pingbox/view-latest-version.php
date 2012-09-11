<?php
 header("Content-type: text/xml; charset=utf-8"); 
  echo "<?xml version=\"1.0\"?>";
  echo "<rbagency_latest_version>";
  $rb_latest = get_option("rb_agency_latest_version");
  if(isset($rb_latest)){
  echo get_option("rb_agency_latest_version");
  }else{
    echo 0;
  }
  echo "</rbagency_latest_version>";
?>