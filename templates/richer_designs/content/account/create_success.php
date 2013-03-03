<?php
/*
  $Id: create_success.php,v 1.2 2011/11/09 09:03:48 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  if ($osC_NavigationHistory->hasSnapshot()) {
    $origin_href = $osC_NavigationHistory->getSnapshotURL();
    $osC_NavigationHistory->resetSnapshot();
  } else {
    $origin_href = osc_href_link(FILENAME_DEFAULT);
  }
?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<div>
  <div style="float: left;"><?php echo osc_image('templates/' . $osC_Template->getCode() . '/images/table_background_man_on_board.png', $osC_Template->getPageTitle()); ?></div>

  <div style="padding-top: 30px;">
    <p><?php echo sprintf($osC_Language->get('success_account_created'), osc_href_link(FILENAME_INFO, 'contact')); ?></p>
  </div>
</div>

 <button class="btn btn-notify pull-right" type="button" onclick="document.location.href='<?php echo $origin_href;?>'"><?php echo $osC_Language->get('button_continue');?></button>
