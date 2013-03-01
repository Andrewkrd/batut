<?php

  class osC_Info {


/* Class constructor */

    function osC_Info() {
    }

    function &getListing() {
      global $osC_Database, $osC_Language;

      $QinfoList = $osC_Database->query('select * from :table_info where active = "1" and language_id = :language_id order by sort_order ASC');
      $QinfoList->bindTable(':table_info', TABLE_INFO);
      $QinfoList->bindInt(':language_id', $osC_Language->getID());
      $QinfoList->execute();
      
      return $QinfoList;
    }

    function &getDetails() {
      global $osC_Database, $osC_Language;

      // NEW URL
      $url = $_SERVER["REQUEST_URI"];
      if(strpos($url, "info/") !== false) 
      	$id = substr($url, strpos($url, "info/")+5);
      else
      	$id = false;
      	
      if($id) {     	
      
      if(is_numeric($id) ) {
      	$QinfoList = $osC_Database->query('select * from :table_info where active = "1" and language_id = :language_id and info_id = :request_id ');
		$QinfoList->bindInt(':request_id', $id);
      }
      else {
      	$QinfoList = $osC_Database->query('select * from :table_info where active = "1" and language_id = :language_id and info_url = :request_id ');
      	$QinfoList->bindValue(':request_id', $id);
      }
      $QinfoList->bindTable(':table_info', TABLE_INFO);
      $QinfoList->bindInt(':language_id', $osC_Language->getID());
      $QinfoList->execute();
      return $QinfoList;
      }
      else 
      	return false;
    }
  }
?>
