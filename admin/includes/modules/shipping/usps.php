<?php
/*
  $Id: usps.php,v 1.1 2011/08/29 22:16:01 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Shipping_usps extends osC_Shipping_Admin {
    var $icon;

    var $_title,
        $_code = 'usps',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_status = false,
        $_sort_order;

// class constructor
    function osC_Shipping_usps() {
      global $osC_Database, $osC_Language;

      $this->icon = DIR_WS_IMAGES . 'icons/shipping_usps.gif';

      $this->_title = $osC_Language->get('shipping_usps_title');
      $this->_description = $osC_Language->get('shipping_usps_description');
      $this->_status = (defined('MODULE_SHIPPING_USPS_STATUS') && (MODULE_SHIPPING_USPS_STATUS == '1') ? true : false);
      $this->_sort_order = (defined('MODULE_SHIPPING_USPS_SORT_ORDER') ? MODULE_SHIPPING_USPS_SORT_ORDER : null);
    
	  $title1 = $osC_Language->get('shipping_usps_admin_1');
	  $title2 = $osC_Language->get('shipping_usps_admin_2');
	  $title3 = $osC_Language->get('shipping_usps_admin_3');
	  $title4 = $osC_Language->get('shipping_usps_admin_4');
	  $title5 = $osC_Language->get('shipping_usps_admin_5');
	  $title6 = $osC_Language->get('shipping_usps_admin_6');
	  $title7 = $osC_Language->get('shipping_usps_admin_7');
	  $title8 = $osC_Language->get('shipping_usps_admin_8');
	  $title9 = $osC_Language->get('shipping_usps_admin_9');
	  $title10 = $osC_Language->get('shipping_usps_admin_10');	
	  $title11 = $osC_Language->get('shipping_usps_admin_11');
	  $title12 = $osC_Language->get('shipping_usps_admin_12');	
	  $title13 = $osC_Language->get('shipping_usps_admin_13');
	  $title14 = $osC_Language->get('shipping_usps_admin_14');
	  $title15 = $osC_Language->get('shipping_usps_admin_15');
	  $title16 = $osC_Language->get('shipping_usps_admin_16');	

	$titlex = $osC_Language->get('access_configuration_title27');
	$titley = $osC_Language->get('access_configuration_title93');
	$Ckey = $osC_Database->query("SELECT * FROM " . DB_TABLE_PREFIX . "configuration WHERE configuration_key = 'STORE_NAME_ADDRESS'");	
	$configuration_title = $Ckey->value('configuration_title');
	$configuration_description = $Ckey->value('configuration_description');
	if (($configuration_title & $configuration_description) != ($titlex & $titley)) {		  
	  
	  $osC_Database->simpleQuery("UPDATE " . TABLE_CONFIGURATION . " SET configuration_title = '$title1', configuration_description = '$title9' WHERE configuration_key = 'MODULE_SHIPPING_USPS_STATUS'");
	  $osC_Database->simpleQuery("UPDATE " . TABLE_CONFIGURATION . " SET configuration_title = '$title2', configuration_description = '$title10' WHERE configuration_key = 'MODULE_SHIPPING_USPS_USERID'");
	  $osC_Database->simpleQuery("UPDATE " . TABLE_CONFIGURATION . " SET configuration_title = '$title3', configuration_description = '$title11' WHERE configuration_key = 'MODULE_SHIPPING_USPS_PASSWORD'");
	  $osC_Database->simpleQuery("UPDATE " . TABLE_CONFIGURATION . " SET configuration_title = '$title4', configuration_description = '$title12' WHERE configuration_key = 'MODULE_SHIPPING_USPS_SERVER'");
	  $osC_Database->simpleQuery("UPDATE " . TABLE_CONFIGURATION . " SET configuration_title = '$title5', configuration_description = '$title13' WHERE configuration_key = 'MODULE_SHIPPING_USPS_HANDLING'");
	  $osC_Database->simpleQuery("UPDATE " . TABLE_CONFIGURATION . " SET configuration_title = '$title6', configuration_description = '$title14' WHERE configuration_key = 'MODULE_SHIPPING_USPS_TAX_CLASS'");		
	  $osC_Database->simpleQuery("UPDATE " . TABLE_CONFIGURATION . " SET configuration_title = '$title7', configuration_description = '$title15' WHERE configuration_key = 'MODULE_SHIPPING_USPS_ZONE'");
	  $osC_Database->simpleQuery("UPDATE " . TABLE_CONFIGURATION . " SET configuration_title = '$title8', configuration_description = '$title16' WHERE configuration_key = 'MODULE_SHIPPING_USPS_SORT_ORDER'");		  		  
	
	}
	}

// class methods
    function isInstalled() {
      return (bool)defined('MODULE_SHIPPING_USPS_STATUS');
    }

    function install() {
      global $osC_Database;

      parent::install();

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable USPS Shipping', 'MODULE_SHIPPING_USPS_STATUS', '1', 'Do you want to offer USPS shipping?', '6', '0', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS User ID', 'MODULE_SHIPPING_USPS_USERID', '', 'Enter the USPS USERID assigned to you.', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS Password', 'MODULE_SHIPPING_USPS_PASSWORD', '', 'See USERID, above.', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Which server to use', 'MODULE_SHIPPING_USPS_SERVER', '13', 'An account at USPS is needed to use the Production server', '6', '0', 'osc_cfg_set_boolean_value(array(12, 13))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_USPS_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_USPS_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'osc_cfg_use_get_tax_class_title', 'osc_cfg_set_tax_classes_pull_down_menu', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_USPS_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'osc_cfg_use_get_zone_class_title', 'osc_cfg_set_zone_classes_pull_down_menu', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_USPS_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_SHIPPING_USPS_STATUS',
                             'MODULE_SHIPPING_USPS_USERID',
                             'MODULE_SHIPPING_USPS_PASSWORD',
                             'MODULE_SHIPPING_USPS_SERVER',
                             'MODULE_SHIPPING_USPS_HANDLING',
                             'MODULE_SHIPPING_USPS_TAX_CLASS',
                             'MODULE_SHIPPING_USPS_ZONE',
                             'MODULE_SHIPPING_USPS_SORT_ORDER');
      }

      return $this->_keys;
    }
  }
?>
