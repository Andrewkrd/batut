<?php 

error_reporting(E_ALL);

header("Content-Type: text/html; charset=UTF-8");

include("../includes/classes/xml.php");

require('../includes/configure.php');
require('../includes/database_tables.php');
require('../includes/classes/database.php');
/*
$url = "http://www.begemott.ru/change_city.php?city_id=44"; // Страничка, на которую посылаем ajax-запросы 
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL,$url);

curl_setopt ($ch, CURLOPT_VERBOSE, 2); // Отображать детальную информацию о соединении
curl_setopt ($ch, CURLOPT_ENCODING, 0); // Шифрование можно включить, если нужно
curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); //Прописываем User Agent, чтобы приняли за своего
curl_setopt ($ch, CURLOPT_COOKIEFILE, "cookie.txt"); // Сюда будем записывать cookies, файл в той же папке, что и сам скрипт
curl_setopt ($ch, CURLOPT_COOKIEJAR, "cookie.txt");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt ($ch, CURLOPT_FAILONERROR, 1);
curl_setopt ($ch, CURLOPT_HEADER, 1);
curl_setopt ($ch, CURLINFO_HEADER_OUT, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);

$result1 = curl_exec($ch);  // у нас есть результат обработки файла 

// вырезаем со страницы все куки которые пришли 
preg_match_all('|Set-Cookie: (.*);|U', $result1, $results); 

// собираем их в строчку для CURL 
$all_cookie_string = implode(';', $results[1]);

$url = "http://www.begemott.ru/getprice.xls"; // Страничка, на которую посылаем ajax-запросы 
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL,$url);

curl_setopt ($ch, CURLOPT_VERBOSE, 2); // Отображать детальную информацию о соединении
curl_setopt ($ch, CURLOPT_ENCODING, 0); // Шифрование можно включить, если нужно
curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); //Прописываем User Agent, чтобы приняли за своего
curl_setopt ($ch, CURLOPT_COOKIEFILE, "cookie.txt"); // Сюда будем записывать cookies, файл в той же папке, что и сам скрипт
curl_setopt ($ch, CURLOPT_COOKIEJAR, "cookie.txt");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt ($ch, CURLOPT_FAILONERROR, 1);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt ($ch, CURLINFO_HEADER_OUT, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_COOKIE, $all_cookie_string);  //Устанавливаем нужные куки в необходимом формате 
//curl_setopt($ch, CURLOPT_POSTFIELDS, "CITY=44"); //Устанавливаем значения, которые мы передаем через POST на сервер в нужном формат
$result = curl_exec($ch); 

curl_close($ch); 

$fopen = fopen ("price.xml", "a+");
fputs ($fopen, $result);
*/
if ( file_exists("price.xml") ) {
        $osC_XML = new osC_XML(file_get_contents( "price.xml" ));

        $definitions = $osC_XML->toArray();
}

$infile = array();
//$sum = 0;
foreach ($definitions["Workbook"]["Worksheet"]["Table"]["Row"] as $cell)
	if(array_key_exists("Cell", $cell))
		if(array_key_exists(0, $cell["Cell"]))
			if($cell["Cell"][0]["Data attr"]["ss:Type"] == "Number") {
			//	print_r($cell);
			//	$sum++;
				$key = $cell["Cell"][0]["Data"];
				$infile[$key]["key"] = $key;
				
				$infile[$key]["price"] = $cell["Cell"][11]["Data"];
				
				
				if( $cell["Cell"][12]["Data"] == "в наличии")	
					$infile[$key]["avail"] = 1;	
				else 
					$infile[$key]["avail"] = 0;			    	
	
			}
//echo $sum;
//print_r($infile);

// Данные о товарах в базе
$ctt = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die(mysql_error());
$db = mysql_select_db(DB_DATABASE) or die("Ошибка базы данных");
mysql_query("SET NAMES UTF8");
$tmp="
	select
		p.products_id, p.begemot_key, p.begemot_price
	FROM
		" . DB_TABLE_PREFIX . "products AS p		 
	WHERE
		p.products_status=1 AND		
		p.parent_id=0
	ORDER BY p.products_id";

$res = mysql_query($tmp);

$pos = array();
while ($tovar = mysql_fetch_array($res)) {
	if($tovar["begemot_key"] > 0) {
		$pos[$tovar["begemot_key"]]["id"] = $tovar["products_id"];
		$pos[$tovar["begemot_key"]]["key"] = $tovar["begemot_key"];
		$pos[$tovar["begemot_key"]]["price"] = $tovar["begemot_price"];
		$pos[$tovar["begemot_key"]]["off"] = true;
		$pos[$tovar["begemot_key"]]["price_ch"] = false;
	}
}	

//print_r($pos);

foreach ($infile as $key=>$value) {
	//print_r($value);	
	//echo $value['key'] . " - ";
	if( array_key_exists($value['key'], $pos)) {
		if($value['avail']) 
			$pos[$value['key']]["off"] = false;
		if(round($pos[$value['key']]["price"]) !== round($value['price'])) {
			$pos[$value['key']]["price_ch"] = true;
			$pos[$value['key']]["price_delta"] = $pos[$value['key']]["price"] - $value['price'];
		}
	}
}


foreach($pos as $position) {
	if($position["off"] == true)
		echo "Нет на складе: begemot_key=" . $position["key"] . " product_id=" . $position["id"] . "\n";
	if($position["price_ch"] == true)
		echo "Изминилась цена: begemot_key=" . $position["key"] . " на " . $position["price_delta"] .  " product_id=" . $position["id"] . "\n";
		
}

/*
 * ALTER TABLE  `osc_products` ADD  `begemot_key` INT NOT NULL ,
ADD  `begemot_price` DECIMAL( 15, 2 ) NOT NULL
 */


// Echo memory peak usage
//echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n";
