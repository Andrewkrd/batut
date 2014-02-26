<?php
/*
  $Id: products.php,v 1.4 2012/08/23 20:49:55 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Products_Products extends osC_Template {

/* Private variables */

    var $_module = 'products',
        $_group = 'products',
        $_page_title,
        $_page_contents = 'info.php',
        $_page_image = 'table_background_list.gif';

/* Class constructor */

    function osC_Products_Products() {
      global $osC_Database, $osC_Services, $osC_Session, $osC_Language, $osC_Breadcrumb, $osC_Product; $osC_Category;

      // NEW URL
      $url = $_SERVER["REQUEST_URI"];
      if(strpos($url, "magazin")) 
      	$id = substr($url, strpos($url, "magazin/")+8);
      else
      	$id = false;
      
      if(empty($id) === false) {
    
        if (($id !== false) && osC_Product::checkEntry($id)) {
          $osC_Product = new osC_Product($id);
          $osC_Product->incrementCounter();

          $this->addPageTags('keywords', $osC_Product->getTitle());
          $this->addPageTags('keywords', $osC_Product->getModel());

          if ($osC_Product->hasTags()) {
            $this->addPageTags('keywords', $osC_Product->getTags());
          }

          $this->addJavascriptFilename('/templates/' . $this->getCode() . '/javascript/' . $this->_group . '/info.js');
          $this->addJavascriptFilename('/templates/' . $this->getCode() . '/javascript/' . $this->_group . '/lightbox.js');


          osC_Services_category_path::process($osC_Product->getCategoryID());

          if ($osC_Services->isStarted('breadcrumb')) {
          	// NEW URL
          	$osC_Category = new osC_Category($osC_Product->getCategoryID());   

          	if($osC_Category->hasParent()){
          		$osC_Category2 = new osC_Category($osC_Category->getParent());				
				$osC_Breadcrumb->add($osC_Category->getTitle(), osc_href_link("category/" . $osC_Category2->getData("category_url") . "/" . $osC_Category->getData("category_url")));
          	}
          	$osC_Breadcrumb->add($osC_Category->getTitle(), osc_href_link("category/" . $osC_Category->getData("category_url")));
          	          	

            $osC_Breadcrumb->add($osC_Product->getTitle(), osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()));
          }

          $this->_page_title = $osC_Product->getTitle(); 
        } else {
          $this->_page_title = $osC_Language->get('product_not_found_heading');
          $this->_page_contents = 'info_not_found.php';
        }
      } else {
        $this->_page_title = $osC_Language->get('product_not_found_heading');
        $this->_page_contents = 'info_not_found.php';
      }
    }
  }
?>
