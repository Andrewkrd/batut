<?php
/*
  $Id: category_path.php,v 1.1 2011/08/30 21:13:24 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Services_category_path {
    function start() {
      global $osC_CategoryTree;

      osC_Services_category_path::process();

      include('includes/classes/category_tree.php');
      $osC_CategoryTree = new osC_CategoryTree();

      return true;
    }

    function process($id = null) {
      global $cPath, $cPath_array, $current_category_id, $osC_CategoryTree, $osC_Database;

      $cPath = '';
      $cPath_array = array();
      $current_category_id = 0;
      $url = $_SERVER["REQUEST_URI"];
  // NEW URL
      if(empty($id) && strpos($url, "category") !== false) {
      		           
	      if(strpos($url, "?") !== false)
	      	$url = substr($url, 0, strpos($url, "?"));
	     	
	      $url_array = explode("/", $url);

	      $cat_array = array();
	
	      $cat_key_exist = false;
	      foreach ($url_array as $key) {
	      	if($cat_key_exist)
	      		$cat_array[] = $key;
	      	if($key == "category")
	      		$cat_key_exist = true;
	      }
	      // 2 уровня вложенности категории пока..
	      if(count($cat_array)>2)
			echo "NOT FOUND!!!";	      

	      if(count($cat_array) > 1)
	      	$Qcategories = $osC_Database->query('select distinct categories_id from :table_categories_description where category_url=":categories_url2" and language_id = :language_id');
	      else
	      	$Qcategories = $osC_Database->query('select distinct categories_id from :table_categories_description where category_url=":categories_url" and language_id = :language_id');
	      $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
	      
	      if(count($cat_array) > 1)
	      	$Qcategories->bindTable(':categories_url2', $cat_array[1]);
	      else
	      	$Qcategories->bindTable(':categories_url', $cat_array[0]);
	      $Qcategories->bindInt(':language_id', 2);
	      $Qcategories->execute();
	      
	      while ($Qcategories->next())
	            $cat_ids[] = $Qcategories->value('categories_id');
          $cPath = implode("_", $cat_ids);
          	          
	      $Qcategories->freeResult();
      }
      
      
      
      if (!empty($id)) {
        $cPath = $osC_CategoryTree->buildBreadcrumb($id);
      }

      if (!empty($cPath)) {
        $cPath_array = array_unique(array_filter(explode('_', $cPath), 'is_numeric'));
        $cPath = implode('_', $cPath_array);
        $current_category_id = end($cPath_array);
      }
      
    }

    function stop() {
      return true;
    }
  }
?>
