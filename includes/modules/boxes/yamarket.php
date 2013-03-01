<?php
/*
  $Id: yamarket.php,v 1.1 2011/08/30 21:13:25 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Boxes_yamarket extends osC_Modules {
    var $_title,
        $_code = 'yamarket',
        $_author_name = 'Andrew',
        $_author_www = 'http://www.oscommerce.com',
        $_group = 'boxes';

    function osC_Boxes_yamarket() {
      global $osC_Language;

      $this->_title = "Оценки яндекс маркета";
    }

    function initialize() {
      global $osC_Language, $osC_Template;

      //$this->_title_link = osc_href_link(FILENAME_SEARCH);

      //$this->_content = '<a href="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=1248/*http://market.yandex.ru/grade-shop.xml?shop_id=69737"><img src="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=1248/*http://img.yandex.ru/market/informer6.png" border="0" alt="Оцените качество магазина ujirafika.ru на Яндекс.Маркете." /></a>';
    	$this->_content = '<a href="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=2508/*http://grade.market.yandex.ru/?id=69737&action=link"><img src="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=2506/*http://grade.market.yandex.ru/?id=69737&action=image&size=1" border="0" width="120" height="110" alt="Читайте отзывы покупателей и оценивайте качество магазина ujirafika.ru на Яндекс.Маркете" /></a>';
    }
  }
?>
