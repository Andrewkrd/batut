<?php

  // grab the item requested
  $QinfoDetails = osC_Info::getDetails();
  
?>
<h1><?php echo $osC_Template->getPageTitle(); ?></h1>
<?php
    echo '<div class="moduleBox">
    		<div>' . $QinfoDetails->value("info_description") . '</div>
		  </div>'
			  
?>

<div class="submitFormButtons" style="text-align: right;">
   <a class="btn" href="/info/">Продолжить</a>
</div>

