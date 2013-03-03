<?php
/*
  $Id: manufacturers.php,v 1.9 2012/10/14 17:01:35 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Index_Manufacturers extends osC_Template {

/* Private variables */

    var $_module = 'manufacturers',
        $_group = 'index',
        $_page_title,
        $_page_contents = 'product_listing.php',
        $_page_image = 'table_background_list.gif';

/* Class constructor */

    function osC_Index_Manufacturers() {
      global $osC_Services, $osC_Language, $osC_Breadcrumb, $osC_Manufacturer, $osC_Database;

      $this->_page_title = sprintf($osC_Language->get('index_heading'), STORE_NAME);
      
      $man_sef = false;
      $man_id = false;
      $str_add = false;
      $dis_sef = false;

      if(strpos($_SERVER["REQUEST_URI"], "manufacturers") > 0){
          	$chpos = strpos($_SERVER["REQUEST_URI"], "manufacturers=");
          	
          	if($chpos == false)
          		$chpos = strpos($_SERVER["REQUEST_URI"], "manufacturers,");
          		
          	$man_id = substr($_SERVER["REQUEST_URI"], $chpos + 14);
          	if(strpos($man_id, "&")) {
          		$str_add = substr($man_id, strpos($man_id, "&") );
          		$man_id = substr($man_id, 0, strpos($man_id, "&")); 
          		$dis_sef = true;
          	}
          	
			if(strpos($man_id, "/")) {
          		$str_add = substr($man_id, strpos($man_id, "/") );
          		$man_id = substr($man_id, 0, strpos($man_id, "/"));          		
          	}
          	
          	if(is_numeric($man_id)){
          		$man_id = substr($man_id, 0, 3);
				$Qman = $osC_Database->query('select manufacturers_sef from :table_manufacturers where manufacturers_id = :manufacturers_id');
				$Qman->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
				$Qman->bindInt(':manufacturers_id', $man_id);
				$Qman->execute();	          
	          	$man_sef = $Qman->value('manufacturers_sef');
          	}
	        else {
	     		$Qman = $osC_Database->query('select manufacturers_id from :table_manufacturers where manufacturers_sef = :manufacturers_sef');
				$Qman->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
				$Qman->bindValue(':manufacturers_sef', $man_id);
				$Qman->execute();	          
	          	if($Qman->numberOfRows() > 0) {
	          		$man_sef = $man_id;
	          		$man_id = $Qman->Value("manufacturers_id");
	          		if(strpos($_SERVER["REQUEST_URI"], "filter=") || strpos($_SERVER["REQUEST_URI"], "sort=") || strpos($_SERVER["REQUEST_URI"], "page="))
	          			$man_sef = false;
	          	}
	          	
      		}      		
		if($man_sef) {
			
			if($str_add && $dis_sef)
          		$location = "/index.php/manufacturers," . $man_sef;// . $str_add;
          		
			elseif($str_add)
          		$location = "/index.php/manufacturers," . $man_sef . $str_add;
          		
			else
				$location = "/index.php/manufacturers," . $man_sef;
        	
        	

        	if($location !== $_SERVER["REQUEST_URI"]) {
        		if(!apc_fetch($_SERVER["REQUEST_URI"] . " > " . $location))
        			apc_add($_SERVER["REQUEST_URI"] . " > " . $location, "", 21600);

          		header ('HTTP/1.1 301 Moved Permanently');
  				header ('Location: ' . $location);
        	}
		}
      }

      if (!is_numeric($_GET[$this->_module]) && $man_id == false)  {
      	$man_sef = $_GET[$this->_module];
		$Qman_2 = $osC_Database->query('select manufacturers_id from :table_manufacturers where manufacturers_sef = :manufacturers_sef');
		$Qman_2->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
		$Qman_2->bindValue(':manufacturers_sef', $man_sef);
		$Qman_2->execute();
		$man_id = $Qman_2->value('manufacturers_id');    
      }
      
      if(!$man_id)
		$man_id = $_GET[$this->_module];
      	

      
      if (is_numeric($man_id)) {
        include('includes/classes/manufacturer.php');
        $osC_Manufacturer = new osC_Manufacturer($man_id);

        if ($osC_Services->isStarted('breadcrumb')) {
			if($man_sef)
				$osC_Breadcrumb->add($osC_Manufacturer->getTitle(), osc_href_link(FILENAME_DEFAULT, $this->_module . '=' . $man_sef ));
			else
				$osC_Breadcrumb->add($osC_Manufacturer->getTitle(), osc_href_link(FILENAME_DEFAULT, $this->_module . '=' . $man_id));
        }

        $this->_page_title = $osC_Manufacturer->getTitle();
        $this->_page_image = 'manufacturers/' . $osC_Manufacturer->getImage();

        $this->_process(); 
      } else {
			$this->_page_contents = 'index.php';
      }
    }

/* Private methods */

    function _process() {
      global $osC_Manufacturer, $osC_Products;

      include('includes/classes/products.php');
      $osC_Products = new osC_Products();
      $osC_Products->setManufacturer($osC_Manufacturer->getID());

      if (isset($_GET['filter']) && is_numeric($_GET['filter']) && ($_GET['filter'] > 0)) {
        $osC_Products->setCategory($_GET['filter']);
      }

      if (isset($_GET['sort']) && !empty($_GET['sort'])) {
        if (strpos($_GET['sort'], '|d') !== false) {
          $osC_Products->setSortBy(substr($_GET['sort'], 0, -2), '-');
        } else {
          $osC_Products->setSortBy($_GET['sort']);
        }
      }
    }
  }
?>
