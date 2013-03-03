<?php
/*
  $Id: index.php,v 1.3 2012/01/10 20:29:46 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>

<?php if (file_exists('images/' . $osC_Template->getPageImage()) == true) {
echo osc_image(DIR_WS_IMAGES . $osC_Template->getPageImage(), $osC_Template->getPageTitle(), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, 'id="pageIcon"');
} else {}
?>

<div class="content error-page">
  <div class="container">
    <div class="row">
      <div class="span4">  
        <div class="big-text">404</div>
        <hr>
      </div>
      <div class="span7 offset1">
        <h2>Ой<span class="color">!!!</span></h2>
        <h4>Страница не найдена</h4>
        <hr>
        <div class="horizontal-links">
          <h5>Попробуйте начать поиск с этих страниц:</h5>
          <a href="/">Главная</a> <a href="/info/contact">Контакты</a> <a href="/category/naduvnyie_batutyi">Надувные батуты</a>
        </div>
        <hr>
        <div class="form">
          <form method="get" id="searchform" action="/search.php" class="form-search">
            <input type="text" value="" name="keywords" id="s" class="input-medium">
            <button type="submit" class="btn">Поиск</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>