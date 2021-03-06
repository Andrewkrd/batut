<?php
/*
  $Id: info_sitemap.php,v 1.1 2011/08/31 20:02:41 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
  $QinfoList = osC_Info::getListing();
  $osC_CategoryTree->reset();
  $osC_CategoryTree->setShowCategoryProductCount(false);
?>

<?php if (file_exists('images/' . $osC_Template->getPageImage()) == true) {
echo osc_image(DIR_WS_IMAGES . $osC_Template->getPageImage(), $osC_Template->getPageTitle(), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, 'id="pageIcon"');
} else {}
?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<div>
  <div style="float: right; width: 49%;">
    <ul>
      <li><?php echo osc_link_object(osc_href_link(FILENAME_ACCOUNT, null, 'SSL'), $osC_Language->get('sitemap_account')); ?></li>
        <ul>
          <li><?php echo osc_link_object(osc_href_link(FILENAME_ACCOUNT, '?edit', 'SSL'), $osC_Language->get('sitemap_account_edit')); ?></li>
          <li><?php echo osc_link_object(osc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'), $osC_Language->get('sitemap_address_book')); ?></li>
          <li><?php echo osc_link_object(osc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL'), $osC_Language->get('sitemap_account_history')); ?></li>
          <li><?php echo osc_link_object(osc_href_link(FILENAME_ACCOUNT, 'newsletters', 'SSL'), $osC_Language->get('sitemap_account_notifications')); ?></li>
        </ul>
      </li>
      <li><?php echo osc_link_object(osc_href_link(FILENAME_CHECKOUT, null, 'SSL'), $osC_Language->get('sitemap_shopping_cart')); ?></li>
      <li><?php echo osc_link_object(osc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'), $osC_Language->get('sitemap_checkout_shipping')); ?></li>
      <li><?php echo osc_link_object(osc_href_link(FILENAME_SEARCH), $osC_Language->get('sitemap_advanced_search')); ?></li>
      <li><?php echo osc_link_object(osc_href_link(FILENAME_INFO), $osC_Language->get('box_information_heading')); ?></li>
        <ul>
<?php

	while ($QinfoList->next()) {
    	echo '<li><a href="' . osc_href_link(FILENAME_INFO, "view=" . $QinfoList->value("info_id"), "NONSSL") . '"> ' . $QinfoList->value("info_name") . '</a></li>';
    }

?>
          <li><?php echo osc_link_object(osc_href_link(FILENAME_INFO, 'contact'), $osC_Language->get('box_information_contact')); ?></li>
        </ul>
    </ul>
  </div>

  <div style="width: 49%;">
    <?php echo $osC_CategoryTree->getTree(); ?>
  </div>
</div>
