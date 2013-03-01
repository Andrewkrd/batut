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
  require('includes/classes/zvonok.php');
  class osC_Info_Zvonok extends osC_Template {

/* Private variables */

    var $_module = 'zvonok',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'info_zvonok.php',
        $_page_image = 'table_background_specials.gif',
        $_data = array();

/* Class constructor */

    function osC_Info_Zvonok() {
      global $osC_Services, $osC_Language, $osC_Breadcrumb;

      $this->_page_title = "Заказать обратный звонок. Сделать заказ.";

      if ($osC_Services->isStarted('breadcrumb')) {
        $osC_Breadcrumb->add($this->_page_title, osc_href_link(FILENAME_INFO, $this->_module));
      }
     
   	  if ($_GET[$this->_module] == 'process') {
        $this->_process();
      }
      
    }
    
	
    
    function _process() {
		global $osC_MessageStack;
		
		if (!isset($_POST['user']) || empty($_POST['user'])) {
    		 $osC_MessageStack->add('error', "Поле имя не заполнено");    	
    	}    	    	
    	
    	if (!isset($_POST['number']) || empty($_POST['number'])) {
    	    $osC_MessageStack->add('error', "Поле номер телефона не заполнено");
    	}
    	elseif(strlen($_POST['number']) < 5) {
    		$osC_MessageStack->add('error', "Поле номер телефона слишком короткое");
    	}
    	
    	if($osC_MessageStack->size('error') == 0)	{    	
	    	$text = "Заказан обратный звонок\n
	    	Имя: " . $_POST["user"] . "\n
	    	Номер телефона: " .  $_POST["number"] . "\n
	    	Дополнительная информация: " .  $_POST["misc"] . "\n
	    	Страница: " . $_SERVER["HTTP_REFERER"] . "\n 
	    	Дата: " . date("r") . "\n
	    	Ip: " . osc_get_ip_address();
	    	
	    	
			$osC_Mail = new osC_Mail(null, null, null, EMAIL_FROM, "Заказан обратный звонок");
			$osC_Mail->setBodyPlain($text);
			$osC_Mail->clearTo();
	
			$osC_Mail->addTo("Андрей", "labinsk@inbox.ru");
			$osC_Mail->send();
	    	    	
    		$osC_MessageStack->add('success', "Ваша заявка принята.<br />В ближайшее время с вами свяжется менеджер.", "success");
    	}
    	
    	return true;
    }
    
    
  }
?>
