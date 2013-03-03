<?php
/*
  $Id: categories.php,v 1.1 2011/08/30 21:13:25 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Boxes_one_category extends osC_Modules {
    var $_title,
        $_code = 'categories',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_group = 'boxes';

    function osC_Boxes_one_category() {
      global $osC_Language;
	// TODO выделить в языковую переменную
      $this->_title = "";
    }

    function initialize() {
      global $osC_CategoryTree, $cPath;

      $osC_CategoryTree->reset();
      
      $osC_CategoryTree->setRootString('','');
      $osC_CategoryTree->setParentGroupString('', '');      
      /*$osC_CategoryTree->setCategoryPath($cPath, '<b>', '</b>');
      $osC_CategoryTree->setParentGroupString('', '');
      $osC_CategoryTree->setParentString('<li>', '');
      $osC_CategoryTree->setChildString('</li>', '');
	  $osC_CategoryTree->setSpacerString('&nbsp;', 2);*/
      $osC_CategoryTree->setShowCategoryProductCount((BOX_CATEGORIES_SHOW_PRODUCT_COUNT == '1') ? true : false);
      
      
      //$tree = $osC_CategoryTree->getArray();
      
      
      


      $this->_content = $osC_CategoryTree->getTree();
    }

    function install() {
      global $osC_Database;

      parent::install();

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Show Product Count', 'BOX_CATEGORIES_SHOW_PRODUCT_COUNT', '1', 'Show the amount of products each category has', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('BOX_CATEGORIES_SHOW_PRODUCT_COUNT');
      }

      return $this->_keys;
    }
  }
?>
