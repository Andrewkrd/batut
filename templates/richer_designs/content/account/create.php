<?php
/*
  $Id: create.php,v 1.1 2011/08/31 20:02:29 ujirafika.ujirafika Exp $

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




<div class="form form-small">

<h4><?php echo $osC_Language->get('my_account_title'); ?></h4>


<?php
  if ($osC_MessageStack->size('create') > 0) {
    echo $osC_MessageStack->get('create');
  }
?>

<form name="create" action="<?php echo osc_href_link(FILENAME_ACCOUNT, 'create=save', 'SSL'); ?>" method="post" onsubmit="return check_form(create);" class="form-horizontal">
<div class="span6">
<div class="formy well">

<div><em style="float: right; margin-top: 10px;"><?php echo $osC_Language->get('form_required_information'); ?></em></div>

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


<?php
  if (ACCOUNT_GENDER > -1) {
    $gender_array = array(array('id' => 'm', 'text' => $osC_Language->get('gender_male')),
                          array('id' => 'f', 'text' => $osC_Language->get('gender_female')));
?>
		<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_gender'), 'fake', null, (ACCOUNT_GENDER > 0), "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_radio_field('gender', $gender_array, null, 'style="float: left; margin: 5px;"', ""); ?>
		</div></div>
<?php
  }
?>

	

<?php
  if (ACCOUNT_DATE_OF_BIRTH == '1') {
?>
		<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_date_of_birth'), 'dob_days', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_date_pull_down_menu('dob', null, false, null, null, date('Y')-1901, -5); ?>
		</div></div>
<?php
  }
?>
	<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_email_address'), 'email_address', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('email_address', null, "class=input-large"); ?>
		</div></div>
		
<?php
  if (ACCOUNT_NEWSLETTER == '1') {
?>
	<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_newsletter'), 'newsletter', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_checkbox_field('newsletter', '1'); ?>
		</div></div>
		
<?php
  }
?>
		<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_password'), 'password', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_password_field('password'); ?>
		</div></div>
		
		
		<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_password_confirmation'), 'confirmation', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_password_field('confirmation'); ?>
		</div></div>
		
		
    


<?php
  if (DISPLAY_PRIVACY_CONDITIONS == '1') {
?>

<div class="moduleBox">
  <h6><?php echo $osC_Language->get('create_account_terms_heading'); ?></h6>

  <div class="content">
    <?php echo sprintf($osC_Language->get('create_account_terms_description'), osc_href_link(FILENAME_INFO, 'view=1', 'AUTO')) . '<br /><br /><ol><li>' . osc_draw_checkbox_field('privacy_conditions', array(array('id' => 1, 'text' => $osC_Language->get('create_account_terms_confirm')))) . '</li></ol><br>'; ?>
  </div>
</div>

<?php
  }
?>

<div class="submitFormButtons">

 <button class="btn btn-notify" type="button" onclick="document.location.href='account.php'"><?php echo $osC_Language->get('button_back');?></button>
 <button class="btn btn-danger pull-right" type="submit"><?php echo $osC_Language->get('button_continue');?></button>
 
</div>
  </div>
</div>

</form>
</div>
