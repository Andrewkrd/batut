<?php
/*
  $Id: results.php,v 1.1 2011/08/31 20:02:25 ujirafika.ujirafika Exp $

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

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>
<div class="span9">
<?php
  require('includes/modules/product_listing.php');
?>
<div class="clearfix"></div>
<button class="btn btn-danger" type="button" onclick="document.location.href='<?php echo osc_href_link(FILENAME_SEARCH) ?>'">Назад</button>
</div>