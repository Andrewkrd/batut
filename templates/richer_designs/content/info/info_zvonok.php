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
   
<?php if($success == 0) :?>


<form name="contact" action="/info.php?zvonok=process" method="post">
<?php 
$dop_text = "";
$arr = explode("/", $_SERVER["HTTP_REFERER"]);
foreach($arr as $id)
	if (osC_Product::checkEntry($id))	{
		$osC_Product = new osC_Product($id);
		$dop_text = "Хочу заказать \"" . $osC_Product->getTitle() . "\"";
		if($osC_Product->hasModel())
			 $dop_text .= ", Артикул товара: " . $osC_Product->getModel() ;
   	
   }
  		

?>
<div class="moduleBox">
  <div class="content">
    <ol>
      <li><?php echo osc_draw_label('Ваше имя', 'user', null, true) . osc_draw_input_field('user'); ?></li>
      <li><?php echo osc_draw_label('Номер телефона', 'number', null, true) . osc_draw_input_field('number'); ?></li>
      <li><?php echo osc_draw_label('Информация', 'misc') . osc_draw_textarea_field('misc', $dop_text, 10, 3); ?></li>
    </ol>
  </div>
</div>

<div class="submitFormButtons" style="text-align: right;">
  <?php echo $osC_Template->osc_draw_image_jquery_button(array('icon' => 'triangle-1-e', 'title' => 'Заказать')); ?>
</div>

</form>



<?php endif; ?>

</div>
