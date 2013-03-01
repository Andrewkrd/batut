<?php
/*
  $Id: login.php,v 1.6 2012/07/19 19:50:14 ujirafika.ujirafika Exp $

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

<?php
  if ($osC_MessageStack->size('login') > 0) {
    echo $osC_MessageStack->get('login');
  }
?>

<div class="span6">
  <div class="formy well">
  <div class="title">
    <h4 class="title"><?php echo $osC_Language->get('login_new_customer_heading'); ?></h4>
  </div>

  
    <p><?php echo $osC_Language->get('login_new_customer_text'); ?></p>
    
    <button class="btn btn-danger pull-right" type="button" onclick="document.location.href='account.php?create'">продолжить</button>
<div class="clearfix"></div>
  </div>
</div>

<div class="span6">
  <div class="formy well">
  <form name="login" action="<?php echo osc_href_link(FILENAME_ACCOUNT, 'login=process', 'SSL', false, false); ?>" method="post">

  <h4 class="title"><?php echo $osC_Language->get('login_returning_customer_heading'); ?></h4>

 
    <p><?php echo $osC_Language->get('login_returning_customer_text'); ?></p>

    <ol>
      <li><?php echo osc_draw_label($osC_Language->get('field_customer_email_address'), 'email_address') . osc_draw_input_field('email_address'); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('field_customer_password'), 'password') . osc_draw_password_field('password'); ?></li>
    </ol>

    <p><?php echo sprintf($osC_Language->get('login_returning_customer_password_forgotten'), osc_href_link(FILENAME_ACCOUNT, 'password_forgotten', 'SSL', false, false)); ?></p>
    
	<button class="btn btn-danger pull-right" type="submit">войти</button>
	<div class="clearfix"></div>

  </form>
  </div>
</div>

