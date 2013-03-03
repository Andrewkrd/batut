<?php
/*
  $Id: zvonok.php,v 1.2 2012/01/31 20:34:56 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
  class osC_Info_Subscribe extends osC_Template {

/* Private variables */

    var $_module = 'subscribe',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'info_subscribe.php',
        $_page_image = 'table_background_specials.gif',
        $_data = array();

/* Class constructor */

    function osC_Info_Subscribe() {
      global $osC_Services, $osC_Language, $osC_Breadcrumb;

      $this->_page_title = "Подписка на новостную рассылку.";

      if ($osC_Services->isStarted('breadcrumb')) {
        $osC_Breadcrumb->add($this->_page_title, osc_href_link(FILENAME_INFO, $this->_module));
      }
     
   
      $this->_process();
      
      
    }
    
	
    
    function _process() {
	global $osC_MessageStack;
	
        if (!isset($_POST['email_address']) || empty($_POST['email_address'])) {
      		$osC_MessageStack->add('error', "Поле Email не заполнено");    
        }      
        elseif (!osc_validate_email_address($_POST['email_address'])) {
      		$osC_MessageStack->add('error', "Поле Email заполненно некоректно");    
        }

    	
    	if($osC_MessageStack->size('error') == 0)	{    	
	    	$text = "Подписка на новостную рассылку\n
	    	Email: " .  $_POST['email_address'] . "\n
	    	Дополнительная информация: \n
	    	Сайт: " . $_SERVER["SERVER_NAME"] . "\n 
	    	Дата: " . date("r") . "\n
	    	Ip: " . osc_get_ip_address();
	    	
	    	
			$osC_Mail = new osC_Mail(null, null, null, EMAIL_FROM, "Подписка на новости с сайта" . $_SERVER["SERVER_NAME"]);
			$osC_Mail->setBodyPlain($text);
			$osC_Mail->clearTo();
	
			$osC_Mail->addTo("Андрей", "labinsk@inbox.ru");
			$osC_Mail->send();
	    	    	
    		$osC_MessageStack->add('success', "Ваш Email обработан системой.<br />Обо всех инетересных новостях, акциях и скидках Вы узнаете первыми.", "success");
    	}
    	
    	return true;
    }
    
    
  }
?>
