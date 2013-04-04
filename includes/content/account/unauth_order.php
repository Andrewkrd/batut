<?php
/*
  $Id: unauth_order.php,v 1.3 2012/02/05 19:49:44 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  require('includes/classes/address_book.php');

  class osC_Account_Unauth_order extends osC_Template {

/* Private variables */

    var $_module = 'unauth_order',
        $_group = 'account',
        $_page_title,
        $_page_contents = 'account_unauth_order.php',
        $_page_image = 'table_background_confirmation.gif';

/* Class constructor */

    function osC_Account_Unauth_order() {
      global $osC_Session, $osC_Services, $osC_Language, $osC_ShoppingCart, $osC_Customer, $osC_MessageStack, $osC_NavigationHistory, $osC_Breadcrumb, $osC_Payment, $osC_Shipping;

      
      if (!isset($_POST['firstname']) || empty($_POST['firstname'])) {
    		 $osC_MessageStack->add('error', "Поле Имя не заполнено");    	
      } 
      
      if (!isset($_POST['lastname']) || empty($_POST['lastname'])) {
    		 $osC_MessageStack->add('error', "Поле фамилия не заполнено");    	
      }
    
      if (!isset($_POST['email_address']) || empty($_POST['email_address'])) {
      		$osC_MessageStack->add('error', "Поле Email не заполнено");    
      }      
      elseif (!osc_validate_email_address($_POST['email_address'])) {
      		$osC_MessageStack->add('error', "Поле Email заполненно некоректно");    
      }
            
      if (!isset($_POST['phone']) || empty($_POST['phone'])) {
    	    $osC_MessageStack->add('error', "Поле Номер телефона не заполнено");
      }
      elseif(strlen($_POST['phone']) < 5) {
    		$osC_MessageStack->add('error', "Поле Номер телефона слишком короткое");
      }
      
      if (!isset($_POST['street_address']) || empty($_POST['street_address'])) {
      		$osC_MessageStack->add('error', "Поле Адрес не заполнено");    
      }   
      
      
      
    
      if($osC_MessageStack->size('error') == 0)	{    
      
      $osC_Customer->setCustomerData(2);
      
      $osC_Customer->setFirstName(osc_sanitize_string($_POST["firstname"]));
      $osC_Customer->setLastName(osc_sanitize_string($_POST["lastname"]));
      $osC_Customer->setEmailAddress(osc_sanitize_string($_POST["email_address"]));
      $osC_Customer->setDefaultAddressID(1);
      
	  $_SESSION['comments'] = "Номер телефона: " . osc_sanitize_string($_POST['phone']) . "<br>";
	  $_SESSION['comments'] .= "Адрес доставки: " . osc_sanitize_string($_POST['street_address']) . "<br>";
	  $_SESSION['comments'] .= "Комментарии к заказу: " . osc_sanitize_string($_POST['comment']) . "<br>";
	  if(isset($_POST['newsletter']) && $_POST['newsletter'] == 1)
	  	$newsletter = 1;
	  else
	  	$newsletter = 0;
	  $_SESSION['comments'] .= "Подписка на новости: " . $newsletter;
      
      if ($osC_ShoppingCart->hasContents() === false) {
        osc_redirect(osc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
        //echo "redir2";
      }

		// if no shipping method has been selected, redirect the customer to the shipping method selection page
	  include('includes/classes/shipping.php');
      $osC_Shipping = new osC_Shipping("flat");
	  $osC_ShoppingCart->setShippingMethod($osC_Shipping->getCheapestQuote());
	  
      if (($osC_ShoppingCart->hasShippingMethod() === false)) {
        osc_redirect(osc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
        //echo "redir3";
      }

		// load selected payment module
      include('includes/classes/payment.php');
      $osC_Payment = new osC_Payment("cod");
      $osC_ShoppingCart->setBillingMethod(array('id' => "cod", 'title' => $GLOBALS['osC_Payment_' . "cod"]->getMethodTitle()));
            

      if ($osC_Payment->hasActive() && ($osC_ShoppingCart->hasBillingMethod() === false)) {
      	osc_redirect(osc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
        //echo "redir1";
      }

      include('includes/classes/order.php');

      $osC_Payment->process();

      $osC_ShoppingCart->reset(true);

		// unregister session variables used during checkout
      unset($_SESSION['comments']);
      
      $osC_Customer->reset();      
      }
      
    }
  }
?>
