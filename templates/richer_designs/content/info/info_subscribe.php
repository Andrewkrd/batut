<?php
/*
  $Id: info_zvonok.php,v 1.4 2012/07/04 20:04:26 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
//  $QinfoList = osC_Zvonok::getListing();
?>

<?php if (file_exists('images/' . $osC_Template->getPageImage()) == true) {
echo osc_image(DIR_WS_IMAGES . $osC_Template->getPageImage(), $osC_Template->getPageTitle(), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, 'id="pageIcon"');
} else {}
?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<div>

  
<?php

if ($osC_MessageStack->size('error') > 0) {
	echo $osC_MessageStack->get('error');
}
$success = $osC_MessageStack->size('success');
if ($osC_MessageStack->size('success') > 0) {
	echo $osC_MessageStack->get('success');
}


?>
   


</div>
