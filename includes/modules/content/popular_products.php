<?php
/*
  $Id: popular_products.php,v 1.1 2011/08/30 21:13:01 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Content_popular_products extends osC_Modules {
    var $_title,
        $_code = 'popular_products',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_group = 'content';

/* Class constructor */

    function osC_Content_popular_products() {
      global $osC_Language;

      $this->_title = $osC_Language->get('popular_products_title');
    }

    function initialize() {
      global $osC_Database, $osC_Services, $osC_Language, $osC_Currencies, $osC_Image, $osC_Specials, $current_category_id, $osC_Template;

        if ( $current_category_id < 1 ) {
          $Qproducts = $osC_Database->query('select p.products_id, p.products_tax_class_id, p.products_price, pd.products_name, pd.products_keyword, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd where p.products_status = 1 and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = :language_id order by p.products_ordered desc limit :max_display_popular_products');
        } else {
          $Qproducts = $osC_Database->query('select distinct p.products_id, p.products_tax_class_id, p.products_price, pd.products_name, pd.products_keyword, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd, :table_products_to_categories p2c, :table_categories c where c.parent_id = :parent_id and c.categories_id = p2c.categories_id and p2c.products_id = p.products_id and p.products_status = 1 and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = :language_id order by p.products_ordered desc limit :max_display_popular_products');
          $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
          $Qproducts->bindTable(':table_categories', TABLE_CATEGORIES);
          $Qproducts->bindInt(':parent_id', $current_category_id);
        }

        $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
        $Qproducts->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qproducts->bindInt(':default_flag', 1);
        $Qproducts->bindInt(':language_id', $osC_Language->getID());
        $Qproducts->bindInt(':max_display_popular_products', MODULE_CONTENT_POPULAR_PRODUCTS_MAX_DISPLAY);

        if (MODULE_CONTENT_POPULAR_PRODUCTS_CACHE > 0) {
        $Qproducts->setCache('popular_products-' . $osC_Language->getCode() . '-' . $osC_Currencies->getCode() . '-' . $current_category_id, MODULE_CONTENT_POPULAR_PRODUCTS_CACHE);
      }
        $Qproducts->execute();
        
        if ($Qproducts->numberOfRows()) {
        $rows = 0;
        while ($Qproducts->next()) {

			$osC_Product = new osC_Product($Qproducts->valueInt('products_id'));
    		$rows++;

			if($rows == 1 || fmod ($rows, 4) == 1)
				$this->_content .= '<div class="row-fluid"><ul class="thumbnails product-list-inline-small">';
	
			$this->_content .= '<li class="span3">';
			
	
			$this->_content .= '<div class="thumbnail" style="text-align:center;">'. osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()), $osC_Image->show($osC_Product->getImage(), $osC_Product->getTitle())) . '</div>'; 
				
			
			$this->_content .= '<div class="caption"><h3>';
			if (isset($_GET['manufacturers'])) {
				$this->_content .= osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword() . '/manufacturers,' . $_GET['manufacturers']), $osC_Product->getTitle());
			} else {
				$this->_content .= osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()), $osC_Product->getTitle());
			}
			$this->_content .= '</h3>';
			// Introduce Text
			$this->_content .= '<p>' . $osC_Product->getSubTitle() . '</p>';
				
					
	
			$price = $osC_Product->getPriceFormatedList(true);
				
			if($osC_Product->getAvailable() == 0)
				$this->_content .= '<p>' . $price . '<span class="label label-inverse pull-left">Отсутствует</span></p>';
			elseif($osC_Product->getAvailable() == 1)
				$this->_content .= '<div>' . $price . '<span class="label label-success pull-left">В наличии</span></div>';
			elseif($osC_Product->getAvailable() == 2)
				$this->_content .= '<p>' . $price . '<span class="label pull-left">Под заказ</span></p>';
			elseif($osC_Product->getAvailable() == 3)
				$this->_content .= '<p>' . $price . '<span class="label pull-left">Под заказ 10-14 дней</span></p>';
	
			
			$this->_content .= '<div class="clearfix"></div>';
			
	   		if( $osC_Product->getAvailable() >= 1 ) {
				$this->_content .= '<div class="button pull-left"><a href=' . osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword() . '?action=cart_add') . '><i class="icon-shopping-cart icon-large"></i> <span style="color: white;">' . $osC_Language->get('button_buy_now') . '</span></a></div>';
			}
			//echo $price;
			
			$this->_content .= '</div>';
			$this->_content .= '</li>';
			
			if(fmod ($rows , 4) == 0 || $Qproducts->numberOfRows() == $rows)
				$this->_content .= "</ul></div>";

        }

        $this->_content .= '<div class="clearfix"></div>';
        
      }

      $Qproducts->freeResult();
    }

    function install() {
      global $osC_Database;

      parent::install();

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Entries To Display', 'MODULE_CONTENT_POPULAR_PRODUCTS_MAX_DISPLAY', '9', 'Maximum number of popular products to display', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'MODULE_CONTENT_POPULAR_PRODUCTS_CACHE', '60', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_CONTENT_POPULAR_PRODUCTS_MAX_DISPLAY', 'MODULE_CONTENT_POPULAR_PRODUCTS_CACHE');
      }

      return $this->_keys;
    }
  }
?>
