<?php
/*
$Id$

RuBiC, Open Source E-Commerce Solutions
http://www.rubicshop.ru

Copyright (c) 2011 RuBiC

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License v2 (1991)
as published by the Free Software Foundation.
*/
	$title1 = $osC_Language->get('orders_transactions_status_1');
	$title2 = $osC_Language->get('orders_transactions_status_2');
	$title3 = $osC_Language->get('orders_transactions_status_3');
	$title4 = $osC_Language->get('orders_transactions_status_4');

	$titlex = $osC_Language->get('access_configuration_title27');
	$titley = $osC_Language->get('access_configuration_title93');
	$Ckey = $osC_Database->query("SELECT * FROM " . DB_TABLE_PREFIX . "configuration WHERE configuration_key = 'STORE_NAME_ADDRESS'");
	$configuration_title = $Ckey->value('configuration_title');
	$configuration_description = $Ckey->value('configuration_description');

	if (($configuration_title & $configuration_description) != ($titlex & $titley)) {
		$osC_Database->simpleQuery("UPDATE " . DB_TABLE_PREFIX . "orders_transactions_status SET status_name = '$title1' WHERE id = 1");
		$osC_Database->simpleQuery("UPDATE " . DB_TABLE_PREFIX . "orders_transactions_status SET status_name = '$title2' WHERE id = 2");
		$osC_Database->simpleQuery("UPDATE " . DB_TABLE_PREFIX . "orders_transactions_status SET status_name = '$title3' WHERE id = 3");
		$osC_Database->simpleQuery("UPDATE " . DB_TABLE_PREFIX . "orders_transactions_status SET status_name = '$title4' WHERE id = 4");
	}
?>

<h1><?php echo osc_link_object(osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule()), $osC_Template->getPageTitle()); ?></h1>

<?php
	if ( $osC_MessageStack->size($osC_Template->getModule()) > 0 ) {
		echo $osC_MessageStack->get($osC_Template->getModule());
	}
?>

<form name="search" action="<?php echo osc_href_link_admin(FILENAME_DEFAULT); ?>" method="get"><?php echo osc_draw_hidden_field($osC_Template->getModule()); ?>

	<p align="right">

		<?php
			include('../includes/classes/currencies.php');
			$osC_Currencies = new osC_Currencies();

			$orders_statuses = array();
			$orders_status_array = array();

			$Qstatuses = $osC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where language_id = :language_id');
			$Qstatuses->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
			$Qstatuses->bindInt(':language_id', $osC_Language->getID());
			$Qstatuses->execute();

			while ($Qstatuses->next()) {
				$orders_statuses[] = array('id' => $Qstatuses->valueInt('orders_status_id'),
				'text' => $Qstatuses->value('orders_status_name'));

				$orders_status_array[$Qstatuses->valueInt('orders_status_id')] = $Qstatuses->value('orders_status_name');
			}

			$Qpurshased = $osC_Database->query('select o.orders_id, o.customers_id, o.delivery_country, o.delivery_state, o.date_purchased, o.last_modified, greatest(date_purchased, coalesce(last_modified, date_purchased)) as date_sort, s.orders_status_name, ot.value as order_total from :table_orders o, :table_orders_total ot, :table_orders_status s where o.orders_id = ot.orders_id and ot.class = "total" and o.orders_status = s.orders_status_id and s.language_id = :language_id');
			$Qpurshased->appendQuery('order by o.orders_id desc');
			$Qpurshased->bindTable(':table_orders', TABLE_ORDERS);
			$Qpurshased->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
			$Qpurshased->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
			$Qpurshased->bindInt(':language_id', $osC_Language->getID());
			$Qpurshased->execute();

			while ( $Qpurshased->next() ) {
				$min_date = $Qpurshased->valueInt('date_purchased');
				$min_year = date("Y", mktime(0, 0, 0, 0, 0, $min_date));
			}

			$orders_year = array();

			$year = date("Y");
			if (isset ($min_year)) {
				$year = $min_year;
				$year_stat = $min_year;
			}

			$count_year = date("Y")-$year;

			for ( $y = 0; $y < $count_year; $y++ ) {

				$year = $year+1;
				$orders_year[] = array('id' => $year,
				'text' => $year);
			}

			$orders_year = $orders_year;
			rsort($orders_year);
			reset($orders_year);

			$orders_country[] = array('id' => '2',
			'text' => $osC_Language->get('total_statictics_my_country')
			);

			echo $osC_Language->get('operation_heading_filter_status'). '&nbsp;' . osc_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => $osC_Language->get('all_statuses'))), $orders_statuses)) . '&nbsp;' .
			$osC_Language->get('total_year'). '&nbsp;' . osc_draw_pull_down_menu('year', array_merge($orders_year)) . '&nbsp;' .
			$osC_Language->get('total_statictics_regions'). '&nbsp;' . osc_draw_pull_down_menu('country', array_merge(array(array('id' => '1', 'text' => $osC_Language->get('total_statictics_all_country'))), $orders_country)) . '&nbsp;' .
			'<input type="submit" value= "' . $osC_Language->get('button_use') . '" class="operationButton" />';
		?>

	</p>

</form>

<?php
	$Qorders = $osC_Database->query('select o.orders_id, o.customers_id, o.customers_name, o.date_purchased, o.last_modified, o.delivery_country, o.delivery_state, greatest(date_purchased, coalesce(last_modified, date_purchased)) as date_sort, o.currency, o.currency_value, s.orders_status_name, ot.value as order_total from :table_orders o, :table_orders_total ot, :table_orders_status s where o.orders_id = ot.orders_id and ot.class = "total" and o.orders_status = s.orders_status_id and s.language_id = :language_id');

	if ( isset($_GET['status']) && is_numeric($_GET['status']) ) {
		$Qorders->appendQuery('and s.orders_status_id = :orders_status_id');
		$Qorders->bindInt(':orders_status_id', $_GET['status']);
	}

	//Years sort
	$date = date("Y");

	if ( isset($_GET['year']) && is_numeric($_GET['year']) ) {
		$Qorders->appendQuery('and o.date_purchased >= :date_purchased and o.date_purchased < :date_purchased_end');
		$Qorders->bindValue(':date_purchased', $_GET['year'].'-01-01 00:00:00');
		$Qorders->bindValue(':date_purchased_end', ($_GET['year']+1).'-01-01 00:00:00');
		$date = $_GET['year'];
	}

	//Country sort
	$Qcountry = $osC_Database->query("SELECT * FROM ".DB_TABLE_PREFIX."countries where countries_id = '".STORE_COUNTRY."'");

	if ( isset($_GET['country']) && is_numeric($_GET['country']) &&  $_GET['country'] == '2') {
		$Qorders->appendQuery('and o.delivery_country = :stores_country');
		$Qorders->bindValue(':stores_country', $Qcountry->value('countries_name'));
		$country = $_GET['country'];
	}

	$Qorders->appendQuery('order by o.orders_id desc');
	$Qorders->bindTable(':table_orders', TABLE_ORDERS);
	$Qorders->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
	$Qorders->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
	$Qorders->bindInt(':language_id', $osC_Language->getID());

	//Statistics sort
	$Qstatistics = $osC_Database->query('select o.orders_id, o.customers_id, o.customers_name, o.date_purchased, o.last_modified, o.delivery_country, o.delivery_state, greatest(date_purchased, coalesce(last_modified, date_purchased)) as date_sort, o.currency, o.currency_value, s.orders_status_name, ot.value as order_total from :table_orders o, :table_orders_total ot, :table_orders_status s where o.orders_id = ot.orders_id and ot.class = "total" and o.orders_status = s.orders_status_id and s.language_id = :language_id');

	$date = date("Y");

	if ( isset($_GET['year']) && is_numeric($_GET['year']) ) {
		$Qstatistics->appendQuery('and o.date_purchased >= :date_purchased and o.date_purchased < :date_purchased_end');
		$Qstatistics->bindValue(':date_purchased', $_GET['year'].'-01-01 00:00:00');
		$Qstatistics->bindValue(':date_purchased_end', ($_GET['year']+1).'-01-01 00:00:00');
		$date = $_GET['year'];
	}

	if ( isset($_GET['status']) && is_numeric($_GET['status']) ) {
		$Qstatistics->appendQuery('and s.orders_status_id = :orders_status_id');
		$Qstatistics->bindInt(':orders_status_id', $_GET['status']);
	}

	$country = '1';
	if ( isset($_GET['country']) && is_numeric($_GET['country']) &&  $_GET['country'] == '2') {
		$Qstatistics->appendQuery('and o.delivery_country = :stores_country');
		$Qstatistics->bindValue(':stores_country', $Qcountry->value('countries_name'));
		$country = $_GET['country'];
	}

	$Qstatistics->appendQuery('order by o.orders_id desc');
	$Qstatistics->bindTable(':table_orders', TABLE_ORDERS);
	$Qstatistics->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
	$Qstatistics->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
	$Qstatistics->bindInt(':language_id', $osC_Language->getID());
	$Qstatistics->execute();

	$Qyearsort = $osC_Database->query('select o.orders_id, o.customers_id, o.customers_name, o.date_purchased, o.last_modified, o.delivery_country, o.delivery_state, greatest(date_purchased, coalesce(last_modified, date_purchased)) as date_sort, o.currency, o.currency_value, s.orders_status_name, ot.value as order_total from :table_orders o, :table_orders_total ot, :table_orders_status s where o.orders_id = ot.orders_id and ot.class = "total" and o.orders_status = s.orders_status_id and s.language_id = :language_id');

	if ( isset($_GET['status']) && is_numeric($_GET['status']) ) {
		$Qyearsort->appendQuery('and s.orders_status_id = :orders_status_id');
		$Qyearsort->bindInt(':orders_status_id', $_GET['status']);
	}

	//Country sort
	$Qcountry = $osC_Database->query("SELECT * FROM ".DB_TABLE_PREFIX."countries where countries_id = '".STORE_COUNTRY."'");

	if ( isset($_GET['country']) && is_numeric($_GET['country']) &&  $_GET['country'] == '2') {
		$Qyearsort->appendQuery('and o.delivery_country = :stores_country');
		$Qyearsort->bindValue(':stores_country', $Qcountry->value('countries_name'));
		$country = $_GET['country'];
	}

	$Qyearsort->appendQuery('order by o.orders_id desc');
	$Qyearsort->bindTable(':table_orders', TABLE_ORDERS);
	$Qyearsort->bindTable(':table_orders_total', TABLE_ORDERS_TOTAL);
	$Qyearsort->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
	$Qyearsort->bindInt(':language_id', $osC_Language->getID());

	//Statistics 1
	$stats = array();
	$b=0;

	for ( $y = 1; $y < 13; $y++ ) {
		${'b'.$y} = 0;
	}

	while ( $Qstatistics->next() ) {
		$a=$Qstatistics->valueInt('order_total');
		$z=$Qstatistics->value('date_purchased');

		for ( $i = 1; $i < 13; $i++ ) {
			if (($z >= date("Y-m-d H:i:s", mktime(0, 0, 0, $i, 1, $date))) and ($z < date("Y-m-d H:i:s", mktime(0, 0, 0, $i+1, 1, $date)))){
				${'b'.$i} = ${'b'.$i}+$a;
			} else { $stats[] = array($i, 0); }
		}
	}

	for ( $y = 1; $y < 13; $y++ ) {
		$b = $b + ${'b'.$y};
	}

	for ( $y = 1; $y < 13; $y++ ) {
		$stats[] = array($y, ${'b'.$y});
	}

	foreach ($stats as $value) {
		$tmp_arr[] = $value[0];
		$tmp_arr[] = $value[1];
		$arr_01[] = $tmp_arr;
		unset($tmp_arr);
	}

	//Statictics 2
	$Qstatistics->execute();

	$stats = array();
	$c=0;

	for ( $y = 1; $y < 13; $y++ ) {
		${'c'.$y} = 0;
	}

	while ( $Qstatistics->next() ) {
		$a=1;
		$z=$Qstatistics->value('date_purchased');

		for ( $i = 1; $i < 13; $i++ ) {
			if (($z >= date("Y-m-d H:i:s", mktime(0, 0, 0, $i, 1, $date))) and ($z < date("Y-m-d H:i:s", mktime(0, 0, 0, $i+1, 1, $date)))){
				${'c'.$i} = ${'c'.$i}+$a;
			} else {$stats[] = array($i, 0);}
		}
	}

	for ( $y = 1; $y < 13; $y++ ) {
		$c = $c + ${'c'.$y};
	}

	for ( $y = 1; $y < 13; $y++ ) {
		$stats[] = array($y, ${'c'.$y});
	}

	foreach ($stats as $value) {
		$tmp_arr[] = $value[0];
		$tmp_arr[] = $value[1];
		$arr_02[] = $tmp_arr;
		unset($tmp_arr);
	}

	//Statictics 3
	$Qstatistics->execute();
	$stats = array();
	$stats1 = array();

	if ($country == '1'){
		$other_text_2 = $osC_Language->get('total_statictics_country');
		$other_text_3 = $osC_Language->get('total_statictics_other');
		$other_text_4 = $osC_Language->get('total_statictics_country_2');
		$other_text_5 = $osC_Language->get('total_statictics_effective');

		for ( $y = 0; $y < $c; $y++ ) {
			$Qstatistics->next();
			$Qorders->next();
			$stats[] = ($Qstatistics->value('delivery_country'));
			$stats1[] = array('country' =>  $Qstatistics->value('delivery_country'), 'total' => $Qorders->value('order_total'));
		}

		//Statictics 4 and 5
	}else{

		$other_text_2 = $osC_Language->get('total_statictics_regions_2');
		$other_text_3 = $osC_Language->get('total_statictics_other_regions');
		$other_text_4 = $osC_Language->get('total_statictics_regions_3');
		$other_text_5 = $osC_Language->get('total_statictics_effective_2');

		for ( $y = 0; $y < $c; $y++ ) {
			$Qstatistics->next();
			$Qorders->next();
			$stats[] = ($Qstatistics->value('delivery_state'));
			$stats1[] = array('country' =>  $Qstatistics->value('delivery_state'), 'total' => $Qorders->value('order_total'));
		}
	}

	$arr = array_count_values($stats);
	arsort($arr);
	reset($arr);

	//sort $arr
	$j=0;
	$ar=array();
	$art=array();
	foreach ($arr as $keys => $vols){
		$j=$j+1;
		${'keysss'.$j} = $keys;

		$i=0; $aaa=0; $bbb=0;
		foreach ($stats1 as $key => $vol){
			$i=$i+1;
			${'volll'.$i} = $vol;

			if (in_array(${'keysss'.$j}, ${'volll'.$i})){
				$aaa=$aaa+(${'volll'.$i}['total']);
				$bbb=$bbb+1;
			}
		}
		$ar[]=array($bbb, $aaa, ${'keysss'.$j});
		$art[]=array($aaa, $bbb, ${'keysss'.$j});
	}

	$arrr = $ar;
	rsort($arrr);
	reset($arrr);

	$arrt = $art;
	rsort($arrt);
	reset($arrt);

	$arr=array();
	for($x=0; $x<count($arrr); $x++){
		$arr=$arr+array($arrr[$x][2] => $arrr[$x][0]);//resort $arr
	}

	$att=array();
	for($x=0; $x<count($arrt); $x++){
		$att=$att+array($arrt[$x][2] => $arrt[$x][0]);
	}

	$att1=array();
	for($x=0; $x<count($arrt); $x++){
		$att1=$att1+array($arrt[$x][2] => $arrt[$x][1]);
	}

/**
echo "<pre>";
print_r($att);
echo "</pre>";

echo "<pre>";
print_r($att1);
echo "</pre>";
**/

	for ( $y = 1; $y < 9; $y++ ) {
		${'keys'.$y} = '';
		${'vols'.$y} = 0;
	}

	$i=0;
	foreach ($arr as $key => $vol){
		$i=$i+1;
		${'keys'.$i} = $key;
		${'vols'.$i} = $vol;
	}

	for ($x=1; $x<9; $x++){
		${'total'.$x}=0;
	}

	$d=0;
	for ( $y=0; $y < count($stats); $y++ ) {
		for ($x=1; $x<9; $x++){
			if (($stats1[$d]['country']) ==  ${'keys'.$x}){
				${'total'.$x} = ${'total'.$x} + ($stats1[$d]['total']);
			}
		}
		$d=$d+1;
	}

	for ( $y = 1; $y < 9; $y++ ) {
		${'keysccc'.$y} = '';
		${'volsccc'.$y} = 0;
	}

	$i=0;
	foreach ($att as $key => $vol){
		$i=$i+1;
		${'keysccc'.$i} = $key;

	}

	$i=0;
	foreach ($att1 as $key => $vol){
		$i=$i+1;

		${'volsccc'.$i} = $vol;
	}

	for ($x=1; $x<9; $x++){
		${'totalccc'.$x}=0;
	}

	$d=0;
	for ( $y=0; $y < count($stats); $y++ ) {
		for ($x=1; $x<9; $x++){
			if (($stats1[$d]['country']) ==  ${'keysccc'.$x}){
				${'totalccc'.$x} = ${'totalccc'.$x} + ($stats1[$d]['total']);
			}
		}
		$d=$d+1;
	}

	$other_x=0;
	$other_2x=0;
	for ($x=1; $x<9; $x++){
		$other_x = $other_x+${'vols'.$x};
		$other = $c-$other_x;
		$other_2x = $other_2x+${'total'.$x};
		$other_2 = $b-$other_2x;
	}

	$other_text = '';
	if ($other > 0){
		$other_text = $other_text_3;
	}

	$other_xy=0;
	$other_2xy=0;
	for ($x=1; $x<9; $x++){
		$other_xy = $other_xy+${'volsccc'.$x};
		$othery = $c-$other_xy;
		$other_2xy = $other_2xy+${'totalccc'.$x};
		$other_2y = $b-$other_2xy;
	}

	$other_text = '';
	if ($othery > 0){
		$other_text = $other_text_3;
	}

	//Statictics 6
	$years_stat = array();
	$t=0;
	for ( $n = 0; $n < $count_year; $n++ ) {
		$year_stat = $year_stat+1;
		$Qyearsort->execute();

		$m=0;

		for ( $y = 1; $y < 13; $y++ ) {
			${'m'.$y} = 0;
		}

		while ( $Qyearsort->next() ) {
			$e=$Qyearsort->valueInt('order_total');
			$f=$Qyearsort->value('date_purchased');

			for ( $i = 1; $i < 13; $i++ ) {
				if (($f >= date("Y-m-d H:i:s", mktime(0, 0, 0, $i, 1, $year_stat))) and ($f < date("Y-m-d H:i:s", mktime(0, 0, 0, $i+1, 1, $year_stat)))){
					${'m'.$i} = ${'m'.$i}+$e;
				}
			}
		}

		for ( $y = 1; $y < 13; $y++ ) {
			$m = $m + ${'m'.$y};
		}

		$t= $t+$m;
		$years_stat[] = array($year_stat,$m);

	}
?>

<table border="0" width="99%" align="center">
	<thead align="center">
		<td align="center">
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html>
				<head>
					<!-- Load Flot -->
					<!--[if IE]><script type="text/javascript" src="external/flot/excanvas.min.js"></script><![endif]-->
					<script type="text/javascript" src="external/flot/jquery.flot.min.js"></script>
					<script type="text/javascript" src="external/flot/jquery.flot.pie.min.js"></script>
				</head>
				<body>

					<tr><th style="width:33.3%"><div align="center" style="width:86%"><?php echo $osC_Language->get('total_statictics_3') .' '. $date .' '. $osC_Language->get('total_statictics_2') .': '. $c .' '. $osC_Language->get('total_statictics_4') ?></div></th><th style="width:33.3%"><div align="right" style="width:86%"><?php echo $other_text_2 .' '. $date .' '. $osC_Language->get('total_statictics_2') ?></div><div id="hover" align="right" style="width:86%">&nbsp;</div></th><th style="width:33.3%"><div align="center" style="width:86%"><?php echo $other_text_5 .' '. $date .' '. $osC_Language->get('total_statictics_2') ?></div></th></tr>

					<tr><th style="width:33.3%;height:200px;"><div id="statistics2" align="center" style="width:90%;height:200px;"></div></th><th style="width:33.3%;height:200px;"><div id="statistics3" align="center" style="width:90%;height:200px;"></div></th><th style="width:33.3%;height:200px;"><div id="statistics5" align="center" style="width:90%;height:200px;"></div></th></tr>

					<tr><th style="width:33.3%"><div align="center" style="width:86%"><?php echo $osC_Language->get('total_statictics') .' '. $date .' '. $osC_Language->get('total_statictics_2') .': '. $osC_Currencies->format($b) ?></div></th><th style="width:33.3%"><div align="right" style="width:86%"><?php echo $other_text_4 .' '. $date .' '. $osC_Language->get('total_statictics_2') ?></div><div id="hover2" align="right" style="width:86%">&nbsp;</div></th><th style="width:33.3%"><div align="center" style="width:86%"><?php echo $osC_Language->get('total_statictics_yearly') ?></div></th></tr>

					<tr><th style="width:33.3%;height:200px;"><div id="statistics1" align="center" style="width:90%;height:200px;"></div></th><th style="width:33.3%;height:200px;"><div id="statistics4" align="center" style="width:90%;height:200px;"></div></th><th style="width:33.3%;height:200px;"><div id="statistics6" align="center" style="width:90%;height:200px;"></div></th></tr>

					<script type="text/javascript">
						<!-- Analog func Substr -->
						function substr( f_string, f_start, f_length ) {
							if(f_start < 0) {
								f_start += f_string.length;
							}
							if(f_length == undefined) {
								f_length = f_string.length;
							} else if(f_length < 0){
								f_length += f_string.length;
							} else {
								f_length += f_start;
							}
							if(f_length < f_start) {
								f_length = f_start;
							}
							return f_string.substring(f_start, f_length);
						}

						<!-- Statistics 1 -->
						var all_data = [
						{ data:
							"",
						label: ""},
						{ data:
							<?php echo json_encode($arr_01) ?>,
						label: ""},
						];
						var plot_conf = {
							bars: { show: true, lineWidth: 2, barWidth: 0.83, fill: true },
							xaxis: { tickDecimals: 0 },
							yaxis: { tickDecimals: 0, min:0},
						};
						$.plot($("#statistics1"), all_data, plot_conf);

						<!-- Statistics 2 -->
						var all_data = [
						{ data:
							"",
						label: ""},
						{ data:
							<?php echo json_encode($arr_02) ?>,
						label: ""},
						];
						var plot_conf = {
							bars: { show: true, lineWidth: 2, barWidth: 0.83, fill: true },
							xaxis: { tickDecimals: 0 },
							yaxis: { tickDecimals: 0, min:0},
						};
						$.plot($("#statistics2"), all_data, plot_conf);

						<!-- Statistics 3 -->
						var data = [
						{ label: "<?php echo $keys1 ?>",  data: [[1,"<?php echo $vols1 ?>"]]},
						{ label: "<?php echo $keys2 ?>",  data: [[1,"<?php echo $vols2 ?>"]]},
						{ label: "<?php echo $keys3 ?>",  data: [[1,"<?php echo $vols3 ?>"]]},
						{ label: "<?php echo $keys4 ?>",  data: [[1,"<?php echo $vols4 ?>"]]},
						{ label: "<?php echo $keys5 ?>",  data: [[1,"<?php echo $vols5 ?>"]]},
						{ label: "<?php echo $keys6 ?>",  data: [[1,"<?php echo $vols6 ?>"]]},
						{ label: "<?php echo $keys7 ?>",  data: [[1,"<?php echo $vols7 ?>"]]},
						{ label: "<?php echo $keys8 ?>",  data: [[1,"<?php echo $vols8 ?>"]]},
						{ label: "<?php echo $other_text ?>",  data: [[1,"<?php echo $other; ?>"]]}
						];
						$.plot($("#statistics3"), data,
						{
							series: {
								pie: {
									show: true
								}
							},
							grid: {
								hoverable: true,
							}
						});
						$("#statistics3").bind("plothover", pieHover);
						function pieHover(event, pos, obj)
						{
							if (!obj)
							return;
							percent = parseFloat(obj.series.percent).toFixed(2);
							$("#hover").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+obj.series.label+' - '+percent+'% ('+substr(String(obj.series.data),2,30)+')</span>');
						}

						<!-- Statistics 4 -->
						var data = [
						{ label: "<?php echo $keysccc1 ?>",  data: [[1,"<?php echo $totalccc1 ?>"]]},
						{ label: "<?php echo $keysccc2 ?>",  data: [[1,"<?php echo $totalccc2 ?>"]]},
						{ label: "<?php echo $keysccc3 ?>",  data: [[1,"<?php echo $totalccc3 ?>"]]},
						{ label: "<?php echo $keysccc4 ?>",  data: [[1,"<?php echo $totalccc4 ?>"]]},
						{ label: "<?php echo $keysccc5 ?>",  data: [[1,"<?php echo $totalccc5 ?>"]]},
						{ label: "<?php echo $keysccc6 ?>",  data: [[1,"<?php echo $totalccc6 ?>"]]},
						{ label: "<?php echo $keysccc7 ?>",  data: [[1,"<?php echo $totalccc7 ?>"]]},
						{ label: "<?php echo $keysccc8 ?>",  data: [[1,"<?php echo $totalccc8 ?>"]]},
						{ label: "<?php echo $other_text ?>",  data: [[1,"<?php echo $other_2; ?>"]]}
						];
						$.plot($("#statistics4"), data,
						{
							series: {
								pie: {
									show: true
								}
							},
							grid: {
								hoverable: true,
							}
						});
						$("#statistics4").bind("plothover", pieHover2);
						function pieHover2(event, pos, obj)
						{
							if (!obj)
							return;
							percent = parseFloat(obj.series.percent).toFixed(2);
							$("#hover2").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+obj.series.label+' - '+percent+'% ('+substr(String(obj.series.data),2,30)+')</span>');
						}

						<!-- Statistics 5 -->
						<?php
							for ($x=1; $x<9; $x++){
								if (!isset(${'totalccc'.$x}) or ${'totalccc'.$x}==null or !isset(${'volsccc'.$x}) or ${'volsccc'.$x}==null){ ${'e'.$x}='';}else{${'e'.$x}=round((${'totalccc'.$x}/${'volsccc'.$x})/($b/$c), 2);}
							}
							if (!isset($other_2y) or $other_2y==null or !isset($othery) or $othery==null){ $e9='';}else{$e9=round(($other_2y/$othery)/($b/$c), 2);}
						?>
						var all_data = [
						{ data:
							[[1, "<?php  echo $e1; ?>"]],
						label: "<?php echo $keysccc1 ?>"},
						{ data:
							[[2, "<?php  echo $e2; ?>"]],
						label: "<?php echo $keysccc2 ?>"},
						{ data:
							[[3, "<?php  echo $e3 ?>"]],
						label: "<?php echo $keysccc3 ?>"},
						{ data:
							[[4, "<?php  echo $e4; ?>"]],
						label: "<?php echo $keysccc4 ?>"},
						{ data:
							[[5, "<?php  echo $e5; ?>"]],
						label: "<?php echo $keysccc5 ?>"},
						{ data:
							[[6, "<?php  echo $e6; ?>"]],
						label: "<?php echo $keysccc6 ?>"},
						{ data:
							[[7, "<?php  echo $e7; ?>"]],
						label: "<?php echo $keysccc7 ?>"},
						{ data:
							[[8, "<?php  echo $e8; ?>"]],
						label: "<?php echo $keysccc8 ?>"},
						{ data:
							[[9, "<?php  echo $e9; ?>"]],
						label: "<?php echo $other_text ?>"},
						];
						var plot_conf = {
							bars: { show: true, lineWidth: 0.5, barWidth: 0.83, fill: 1 },
							xaxis: { show: false, max:18 },
							yaxis: { tickDecimals: 0, min:0},
						};
						$.plot($("#statistics5"), all_data, plot_conf);

						<!-- Statistics 6 -->
						var all_data = [
						{ data:
							"",
						label: ""},
						{ data:
							<?php echo json_encode($years_stat) ?>,
						label: "<?php echo $osC_Language->get('total_statictics_yearly_total') .': '. $t ?>"},
						];
						var plot_conf = {
							lines: { show: true,  lineWidth: 0.17, fill: true },
							xaxis: { tickDecimals: 0 },
							yaxis: { tickDecimals: 0, min:0},
						};
						$.plot($("#statistics6"), all_data, plot_conf);
					</script>
				</body>
			</html>
		</td>
	</thead>
</table>