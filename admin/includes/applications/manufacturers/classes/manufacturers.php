<?php
/*
  $Id: manufacturers.php,v 1.3 2012/09/04 20:19:48 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Manufacturers_Admin {
    public static function getData($id, $language_id = null) {
      global $osC_Database, $osC_Language;

      if ( empty($language_id) ) {
        $language_id = $osC_Language->getID();
      }

      $Qmanufacturers = $osC_Database->query('select m.*, mi.* from :table_manufacturers m, :table_manufacturers_info mi where m.manufacturers_id = :manufacturers_id and m.manufacturers_id = mi.manufacturers_id and mi.languages_id = :languages_id');
      $Qmanufacturers->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
      $Qmanufacturers->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
      $Qmanufacturers->bindInt(':manufacturers_id', $id);
      $Qmanufacturers->bindInt(':languages_id', $language_id);
      $Qmanufacturers->execute();

      $data = $Qmanufacturers->toArray();

      $Qclicks = $osC_Database->query('select sum(url_clicked) as total from :table_manufacturers_info where manufacturers_id = :manufacturers_id');
      $Qclicks->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
      $Qclicks->bindInt(':manufacturers_id', $id);
      $Qclicks->execute();

      $data['url_clicks'] = $Qclicks->valueInt('total');

      $Qproducts = $osC_Database->query('select count(*) as products_count from :table_products where manufacturers_id = :manufacturers_id');
      $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproducts->bindInt(':manufacturers_id', $id);
      $Qproducts->execute();

      $data['products_count'] = $Qproducts->valueInt('products_count');

      $Qclicks->freeResult();
      $Qproducts->freeResult();
      $Qmanufacturers->freeResult();

      return $data;
    }

    public static function save($id = null, $data) {
      global $osC_Database, $osC_Language;

      $error = false;

      $osC_Database->startTransaction();

      if ( is_numeric($id) ) {
        $Qmanufacturer = $osC_Database->query('update :table_manufacturers set manufacturers_name = :manufacturers_name, manufacturers_sef=:manufacturers_sef, manufacturers_text=:manufacturers_text, last_modified = now() where manufacturers_id = :manufacturers_id');
        $Qmanufacturer->bindInt(':manufacturers_id', $id);
        $Qmanufacturer->bindValue(':manufacturers_sef', $data["sef_url"]);	
        
      } else {
        $Qmanufacturer = $osC_Database->query('insert into :table_manufacturers (manufacturers_name, manufacturers_sef, manufacturers_text, date_added) values (:manufacturers_name, :manufacturers_sef, :manufacturers_text, now())');
        if(empty($data["sef_url"]))  {
	        $Atr = array(
	        "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
	        "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
	        "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
	        "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
	        "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
	        "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
	        "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
	        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"e","ж"=>"j",
	        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
	        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
	        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
	        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
	        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", 
	        " "=> "_", "."=> "", "/"=> "_",
			"A"=>"a", "B"=>"b", "C"=>"c", "D"=>"d", "E"=>"e", "F"=>"f", "G"=>"g",
			"H"=>"h", "I"=>"i", "J"=>"j", "K"=>"k", "L"=>"l", "M"=>"m", "N"=>"n",
			"O"=>"o", "P"=>"p", "Q"=>"q", "R"=>"r", "S"=>"s", "T"=>"t", "U"=>"u",
			"V"=>"v", "W"=>"w", "X"=>"x", "Y"=>"y", "Z"=>"z",        
			"a"=>"a", "b"=>"b", "c"=>"c", "d"=>"d", "e"=>"e", "f"=>"f", "g"=>"g",
			"h"=>"h", "i"=>"i", "j"=>"j", "k"=>"k", "l"=>"l", "m"=>"m", "n"=>"n",
			"o"=>"o", "p"=>"p", "q"=>"q", "r"=>"r", "s"=>"s", "t"=>"t", "u"=>"u",
			"v"=>"v", "w"=>"w", "x"=>"x", "y"=>"y", "z"=>"z", "é"=>"é"
			);
		   	$man_url = strtr($data['name'],$Atr);    
		    $man_url = preg_replace('/[^A-Za-z0-9_\-]/', '', $man_url);
	        $Qmanufacturer->bindValue(':manufacturers_sef', $man_url);	
      	} 
      	else 
      		$Qmanufacturer->bindValue(':manufacturers_sef', $data["sef_url"]);	   
      }

      
      
      
     	
      
            
      
      $Qmanufacturer->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
      $Qmanufacturer->bindValue(':manufacturers_name', $data['name']);
      $Qmanufacturer->bindValue(':manufacturers_text', $data['text']);
      $Qmanufacturer->setLogging($_SESSION['module'], $id);
      $Qmanufacturer->execute();

      if ( !$osC_Database->isError() ) {
        if ( is_numeric($id) ) {
          $manufacturers_id = $id;
        } else {
          $manufacturers_id = $osC_Database->nextID();
        }

        $image = new upload('manufacturers_image', realpath('../' . DIR_WS_IMAGES . 'manufacturers'));

        if ( $image->exists() ) {
          if ( $image->parse() && $image->save() ) {
            $Qimage = $osC_Database->query('update :table_manufacturers set manufacturers_image = :manufacturers_image where manufacturers_id = :manufacturers_id');
            $Qimage->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
            $Qimage->bindValue(':manufacturers_image', $image->filename);
            $Qimage->bindInt(':manufacturers_id', $manufacturers_id);
            $Qimage->setLogging($_SESSION['module'], $manufacturers_id);
            $Qimage->execute();

            if ( $osC_Database->isError() ) {
              $error = true;
            }
          }
        }
      } else {
        $error = true;
      }

      if ( $error === false ) {
        foreach ( $osC_Language->getAll() as $l ) {
          if ( is_numeric($id) ) {
            $Qurl = $osC_Database->query('update :table_manufacturers_info set manufacturers_url = :manufacturers_url where manufacturers_id = :manufacturers_id and languages_id = :languages_id');
          } else {
            $Qurl = $osC_Database->query('insert into :table_manufacturers_info (manufacturers_id, languages_id, manufacturers_url) values (:manufacturers_id, :languages_id, :manufacturers_url)');
          }

          $Qurl->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
          $Qurl->bindInt(':manufacturers_id', $manufacturers_id);
          $Qurl->bindInt(':languages_id', $l['id']);
          $Qurl->bindValue(':manufacturers_url', $data['url'][$l['id']]);
          $Qurl->setLogging($_SESSION['module'], $manufacturers_id);
          $Qurl->execute();

          if ( $osC_Database->isError() ) {
            $error = true;
            break;
          }
        }
      }

      if ( $error === false ) {
        $osC_Database->commitTransaction();

        osC_Cache::clear('manufacturers');

        return true;
      }

      $osC_Database->rollbackTransaction();

      return false;
    }

    public static function delete($id, $delete_image = false, $delete_products = false) {
      global $osC_Database;

      if ( $delete_image === true ) {
        $Qimage = $osC_Database->query('select manufacturers_image from :table_manufacturers where manufacturers_id = :manufacturers_id');
        $Qimage->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
        $Qimage->bindInt(':manufacturers_id', $id);
        $Qimage->execute();

        if ( $Qimage->numberOfRows() && !osc_empty($Qimage->value('manufacturers_image')) ) {
          if ( file_exists(realpath('../' . DIR_WS_IMAGES . 'manufacturers/' . $Qimage->value('manufacturers_image'))) ) {
            @unlink(realpath('../' . DIR_WS_IMAGES . 'manufacturers/' . $Qimage->value('manufacturers_image')));
          }
        }
      }

      $Qm = $osC_Database->query('delete from :table_manufacturers where manufacturers_id = :manufacturers_id');
      $Qm->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
      $Qm->bindInt(':manufacturers_id', $id);
      $Qm->setLogging($_SESSION['module'], $id);
      $Qm->execute();

      $Qmi = $osC_Database->query('delete from :table_manufacturers_info where manufacturers_id = :manufacturers_id');
      $Qmi->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
      $Qmi->bindInt(':manufacturers_id', $id);
      $Qmi->setLogging($_SESSION['module'], $id);
      $Qmi->execute();

      if ( $delete_products === true ) {
        $Qproducts = $osC_Database->query('select products_id from :table_products where manufacturers_id = :manufacturers_id');
        $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
        $Qproducts->bindInt(':manufacturers_id', $id);
        $Qproducts->execute();

        while ( $Qproducts->next() ) {
          osC_Products_Admin::delete($Qproducts->valueInt('products_id'));
        }
      } else {
        $Qupdate = $osC_Database->query('update :table_products set manufacturers_id = null where manufacturers_id = :manufacturers_id');
        $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
        $Qupdate->bindInt(':manufacturers_id', $id);
        $Qupdate->setLogging($_SESSION['module'], $id);
        $Qupdate->execute();
      }

      osC_Cache::clear('manufacturers');

      return true;
    }
  }
?>
