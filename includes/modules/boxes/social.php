<?php
/*
  $Id: social.php,v 1.1 2012/05/24 20:17:09 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Boxes_social extends osC_Modules {
    var $_title,
        $_code = 'social',
        $_author_name = 'Andrew',
        $_author_www = 'http://www.oscommerce.com',
        $_group = 'boxes';

    function osC_Boxes_social() {
      global $osC_Language;

      $this->_title = "Иконки социальных сетей";
    }

    function initialize() {
      global $osC_Language, $osC_Template;

      //$this->_title_link = osc_href_link(FILENAME_SEARCH);

      //$this->_content = '<a href="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=1248/*http://market.yandex.ru/grade-shop.xml?shop_id=69737"><img src="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=1248/*http://img.yandex.ru/market/informer6.png" border="0" alt="Оцените качество магазина ujirafika.ru на Яндекс.Маркете." /></a>';
    	$this->_content = '<div align="center" style="width: 100px; height: 30px"><a rel="nofollow" href="http://www.facebook.com/ujirafika" target="blank" class="footer_image facebook">&nbsp;</a><a rel="nofollow" href="http://vk.com/ujirafika" target="blank" class="footer_image vkontakte">&nbsp;</a></div>';
    }
  }
?>
