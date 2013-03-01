<?php
/*
  $Id: information.php,v 1.1 2011/08/31 20:01:59 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>

<!-- box information start //-->

<!-- boot 
          <div class="well sidebar-nav">
<ul class="nav nav-list">
   <li class="nav-header"><?php //echo $osC_Box->getTitle(); ?></li>
  <?php //echo $osC_Box->getContent(); ?>
</ul>
</div>
-->
<p>&nbsp;</p>
<h5 class="title"><?php echo $osC_Box->getTitle(); ?></h5>
<nav>
	<ul id="nav2">
	  <?php echo $osC_Box->getContent(); ?>
	</ul>
</nav>

<!-- box information end //-->