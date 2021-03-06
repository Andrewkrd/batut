<?php
/*
  $Id: cart_add.php,v 1.8 2012/01/25 21:18:59 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Actions_cart_add {
    function execute() {
      global $osC_Session, $osC_ShoppingCart, $osC_Product;

      if ( !isset($osC_Product) ) {
        $id = false;
        // NEW URL
        if(strpos($_SERVER['REQUEST_URI'], "?") !== false) {
			$keyword = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], "/") +1);
			$keyword = substr($keyword, 0, strpos($keyword, '?'));
        }
        if(preg_match('/^[a-zA-Z0-9 -_]*$/', $keyword))
        	$id = $keyword;
			
        /*foreach ( $_GET as $key => $value ) { echo $value;
          if ( (is_numeric($key) || preg_match('/^[a-zA-Z0-9 -_]*$/', $key)) && ($key != $osC_Session->getName()) ) {//if ( (is_numeric($key) || ereg('^[a-zA-Z0-9 -_]*$', $key)) && ($key != $osC_Session->getName()) ) {
            $id = $key;
          }

          break;
        }*/

        if ( ($id !== false) && osC_Product::checkEntry($id) ) {
          $osC_Product = new osC_Product($id);
        }
      }

      if ( isset($osC_Product) ) {
        if ( $osC_Product->hasVariants() ) {
        if ( isset($_POST['variants']) && is_array($_POST['variants']) && !empty($_POST['variants']) ) {
            if ( $osC_Product->variantExists($_POST['variants']) ) {  
              $osC_ShoppingCart->add($osC_Product->getProductVariantID($_POST['variants']));
            } else {    	
              osc_redirect(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()));

              return false;
            }
          } else {
            osc_redirect(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()));

            return false;
          }
        } else {
          $osC_ShoppingCart->add($osC_Product->getID());
        }
      }

      osc_redirect(osc_href_link(FILENAME_CHECKOUT));
      }
    
  }
?>
