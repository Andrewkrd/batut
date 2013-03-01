<?php
/*
  $Id: account_unauth_order.php,v 1.7 2012/08/28 19:04:39 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>

<h1>Оформление заказа без регистрации</h1>


<?php if ($osC_MessageStack->size('error') > 0) {
	echo $osC_MessageStack->get('error');
?>


<div class="moduleBox" style="width: 49%;"> 
  <form name="login" action="<?php echo osc_href_link(FILENAME_ACCOUNT, 'unauth_order', 'SSL', false, false); ?>" method="post">
  <div class="outsideHeading">
    <h6>Без регистрации</h6>
  </div>

  <div class="content">
    <p>Самый быстрый способ совершить покупку.</p>

		<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_first_name'), 'firstname', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('firstname', null, "class=input-large"); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_last_name'), 'lastname', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('lastname', null, "class=input-large"); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label('Номер телефона:', 'phone', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('phone', null, "class=input-large"); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_email_address'), 'email_address', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('email_address', null, "class=input-large"); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label("Адрес:", 'street_address', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('street_address', null, "class=input-large"); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label("Подписаться на рассылку:", 'newsletter', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_checkbox_field('newsletter', '1'); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label("Комментарии к заказу:", 'comment', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('comment', null, "class=input-large"); ?>
		</div></div>




    <ol>
      <li><?php echo osc_draw_label($osC_Language->get('field_customer_first_name'), 'firstname', null, true) . osc_draw_input_field('firstname'); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('field_customer_last_name'), 'lastname', null, true) . osc_draw_input_field('lastname'); ?></li>
      <li><?php echo osc_draw_label("Номер телефона:", 'phone', null, true) . osc_draw_input_field('phone'); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('field_customer_email_address'), 'email_address', null, true) . osc_draw_input_field('email_address'); ?></li>
      <li><?php echo osc_draw_label("Адрес:", 'street_address', null, true) . osc_draw_input_field('street_address'); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('field_customer_newsletter'), 'newsletter') . osc_draw_checkbox_field('newsletter', '1'); ?></li>
      <li><?php echo osc_draw_label("Комментарии к заказу:", 'comment', null, false) . osc_draw_textarea_field('comment'); ?></li>
    </ol>
  

    <p align="right"><?php echo $osC_Template->osc_draw_image_jquery_button(array('icon' => 'key', 'title' => 'Продолжить')); ?></p>
  </div>
</form>
</div>


<?php } else { ?>


<h1>Ваш заказ был успешно обработан!</h1>


<div>
  <div style="float: right;"><?php echo osc_image('/templates/' . $osC_Template->getCode() . '/images/table_background_man_on_board.png', $osC_Template->getPageTitle()); ?></div>

  <div style="padding-top: 20px;">
    <p>Ваш заказ был успешно обработан. В ближайшее время с вами свяжется менеджер для уточнения деталей заказа.</p>
    
    <p>Уточнить детали заказа вы всегда можете по тел. +7(861) 244-44-39 или <a href="mailto: info@ujirafika.ru">info@ujirafika.ru</a></p>

    <p>Пожалуйста, задайте любые имеющиеся вопросы <a href="/info.php/contact">владельцу магазина</a>.</p>

    <h2 style="text-align: left;">Спасибо за онлайн-покупку!</h2>
  </div>
</div>

<?php }?>