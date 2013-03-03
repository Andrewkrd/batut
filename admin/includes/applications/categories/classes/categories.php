<?php
/*
  $Id: categories.php,v 1.4 2012/09/11 20:06:47 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Categories_Admin {
    public static function get($id, $language_id = null, $key = null) {
      global $osC_Database, $osC_Language, $osC_CategoryTree;

      if ( empty($language_id) ) {
        $language_id = $osC_Language->getID();
      }

      $Qcategories = $osC_Database->query('select c.*, cd.* from :table_categories c, :table_categories_description cd where c.categories_id = :categories_id and c.categories_id = cd.categories_id and cd.language_id = :language_id');
      $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
      $Qcategories->bindInt(':categories_id', $id);
      $Qcategories->bindInt(':language_id', $language_id);
      $Qcategories->execute();

      $data = $Qcategories->toArray();

      $data['childs_count'] = sizeof($osC_CategoryTree->getChildren($Qcategories->valueInt('categories_id'), $dummy = array()));
      $data['products_count'] = $osC_CategoryTree->getNumberOfProducts($Qcategories->valueInt('categories_id'));

      $Qcategories->freeResult();

      if ( !empty($key) && isset($data[$key]) ) {
        $data = $data[$key];
      }

      return $data;
    }

    public static function getAll($id = null) {
      global $osC_Database, $osC_Language, $current_category_id;

      if ( !is_numeric($id) ) {
        if ( isset($current_category_id) && is_numeric($current_category_id) ) {
          $id = $current_category_id;
        } else {
          $id = 0;
        }
      }

      $result = array('entries' => array());

      $Qcategories = $osC_Database->query('select c.*, cd.categories_name, cd.categories_text, cd.categories_text_top, cd.kissmt_categories_description, cd.categories_title from :table_categories c, :table_categories_description cd where c.categories_id = cd.categories_id and cd.language_id = :language_id and c.parent_id = :parent_id order by c.sort_order, cd.categories_name');
      $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
      $Qcategories->bindInt(':language_id', $osC_Language->getID());
      $Qcategories->bindInt(':parent_id', $id);
      $Qcategories->execute();

      while ( $Qcategories->next() ) {
        $result['entries'][] = $Qcategories->toArray();
      }

      $result['total'] = $Qcategories->numberOfRows();

      $Qcategories->freeResult();

      return $result;
    }

    public static function find($search, $id = null) {
      global $osC_Database, $osC_Language, $current_category_id;

      if ( !is_numeric($id) ) {
        if ( isset($current_category_id) && is_numeric($current_category_id) ) {
          $id = $current_category_id;
        } else {
          $id = 0;
        }
      }

      $osC_CategoryTree = new osC_CategoryTree_Admin();
      $osC_CategoryTree->setRootCategoryID($id);

      $categories = array();

      $Qcategories = $osC_Database->query('select c.categories_id from :table_categories c, :table_categories_description cd where c.categories_id = cd.categories_id and cd.language_id = :language_id and (cd.categories_name like :categories_name)');
      $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
      $Qcategories->bindInt(':language_id', $osC_Language->getID());
      $Qcategories->bindValue(':categories_name', '%' . $search . '%');
      $Qcategories->execute();

      while ( $Qcategories->next() ) {
        if ( $Qcategories->valueInt('categories_id') != $id ) {
          $category_path = $osC_CategoryTree->getPathArray($Qcategories->valueInt('categories_id'));
          $top_category_id = $category_path[0]['id'];

          if ( !in_array($top_category_id, $categories) ) {
            $categories[] = $top_category_id;
          }
        }
      }

      $result = array('entries' => array());

      $Qcategories = $osC_Database->query('select c.*, cd.categories_name, cd.categories_text, cd.categories_text_top, cd.kissmt_categories_description, cd.categories_title, from :table_categories c, :table_categories_description cd where c.categories_id = cd.categories_id and cd.language_id = :language_id and c.categories_id in :categories_id order by c.sort_order, cd.categories_name');
      $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
      $Qcategories->bindInt(':language_id', $osC_Language->getID());
      $Qcategories->bindRaw(':categories_id', '("' . implode('", "', $categories) . '")');
      $Qcategories->execute();

      while ( $Qcategories->next() ) {
        $result['entries'][] = $Qcategories->toArray();
      }

      $result['total'] = $Qcategories->numberOfRows();

      $Qcategories->freeResult();

      return $result;
    }

    public static function save($id = null, $data) {
      global $osC_Database, $osC_Language;

      $category_id = '';
      $error = false;

      $osC_Database->startTransaction();

      if ( is_numeric($id) ) {
        $Qcat = $osC_Database->query('update :table_categories set sort_order = :sort_order, last_modified = now() where categories_id = :categories_id');
        $Qcat->bindInt(':categories_id', $id);
      } else {
        $Qcat = $osC_Database->query('insert into :table_categories (parent_id, sort_order, date_added) values (:parent_id, :sort_order, now())');
        $Qcat->bindInt(':parent_id', $data['parent_id']);
      }

      $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qcat->bindInt(':sort_order', $data['sort_order']);
      $Qcat->setLogging($_SESSION['module'], $id);
      $Qcat->execute();

      if ( !$osC_Database->isError() ) {
        $category_id = (is_numeric($id)) ? $id : $osC_Database->nextID();

        foreach ( $osC_Language->getAll() as $l ) {
          if ( is_numeric($id) ) {
            $Qcd = $osC_Database->query('update :table_categories_description set categories_name = :categories_name, category_url = :category_url, categories_text = :categories_text, kissmt_categories_description=:kissmt_categories_description, categories_text_top = :categories_text_top,  categories_title = :categories_title where categories_id = :categories_id and language_id = :language_id');
          } else {
            $Qcd = $osC_Database->query('insert into :table_categories_description (categories_id, language_id, categories_name, category_url, categories_text, kissmt_categories_description, categories_text_top, categories_title) values (:categories_id, :language_id, :categories_name, :category_url, :categories_text, :kissmt_categories_description, :categories_text_top, :categories_title)');
          }

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
        " "=> "_", "."=> "", "/"=> "_"
   		 );
   		  $cat_url = strtr($data['name'][$l['id']],$Atr);    
    	  $cat_url = preg_replace('/[^A-Za-z0-9_\-]/', '', $cat_url);	

          $Qcd->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
          $Qcd->bindInt(':categories_id', $category_id);
          $Qcd->bindInt(':language_id', $l['id']);
          $Qcd->bindValue(':categories_name', $data['name'][$l['id']]);
          $Qcd->bindValue(':category_url', $cat_url);
          $Qcd->bindValue(':categories_text', $data['categories_text']);
          $Qcd->bindValue(':kissmt_categories_description', $data['kissmt_categories_description']);
          $Qcd->bindValue(':categories_text_top', $data['categories_text_top']);
          $Qcd->bindValue(':categories_title', $data['categories_title']);
          
          $Qcd->setLogging($_SESSION['module'], $category_id);
          $Qcd->execute();

          if ( $osC_Database->isError() ) {
            $error = true;
            break;
          }
        }

        if ( $error === false ) {
          $categories_image = new upload($data['image'], realpath('../' . DIR_WS_IMAGES . 'categories'));

          if ( $categories_image->exists() && $categories_image->parse() && $categories_image->save() ) {
            $Qcf = $osC_Database->query('update :table_categories set categories_image = :categories_image where categories_id = :categories_id');
            $Qcf->bindTable(':table_categories', TABLE_CATEGORIES);
            $Qcf->bindValue(':categories_image', $categories_image->filename);
            $Qcf->bindInt(':categories_id', $category_id);
            $Qcf->setLogging($_SESSION['module'], $category_id);
            $Qcf->execute();

            if ( $osC_Database->isError() ) {
              $error = true;
            }
          }
        }
      }

      if ( $error === false ) {
        $osC_Database->commitTransaction();

        osC_Cache::clear('categories');
        osC_Cache::clear('category_tree');
        osC_Cache::clear('also_purchased');

        return true;
      }

      $osC_Database->rollbackTransaction();

      return false;
    }

    public static function delete($id) {
      global $osC_Database, $osC_CategoryTree;

      if ( is_numeric($id) ) {
        $osC_CategoryTree->setBreadcrumbUsage(false);

        $categories = array_merge(array(array('id' => $id, 'text' => '')), $osC_CategoryTree->getArray($id));
        $products = array();
        $products_delete = array();

        foreach ( $categories as $category ) {
          $Qproducts = $osC_Database->query('select products_id from :table_products_to_categories where categories_id = :categories_id');
          $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
          $Qproducts->bindInt(':categories_id', $category['id']);
          $Qproducts->execute();

          while ( $Qproducts->next() ) {
            $products[$Qproducts->valueInt('products_id')]['categories'][] = $category['id'];
          }
        }

        foreach ( $products as $key => $value ) {
          $Qcheck = $osC_Database->query('select categories_id from :table_products_to_categories where products_id = :products_id and categories_id not in :categories_id limit 1');
          $Qcheck->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
          $Qcheck->bindInt(':products_id', $key);
          $Qcheck->bindRaw(':categories_id', '("' . implode('", "', $value['categories']) . '")');
          $Qcheck->execute();

          if ( $Qcheck->numberOfRows() === 0 ) {
            $products_delete[$key] = $key;
          }
        }

        osc_set_time_limit(0);

        foreach ( $categories as $category) {
          $osC_Database->startTransaction();

          $Qimage = $osC_Database->query('select categories_image from :table_categories where categories_id = :categories_id');
          $Qimage->bindTable(':table_categories', TABLE_CATEGORIES);
          $Qimage->bindInt(':categories_id', $category['id']);
          $Qimage->execute();

          $Qc = $osC_Database->query('delete from :table_categories where categories_id = :categories_id');
          $Qc->bindTable(':table_categories', TABLE_CATEGORIES);
          $Qc->bindInt(':categories_id', $category['id']);
          $Qc->setLogging($_SESSION['module'], $id);
          $Qc->execute();

          if ( !$osC_Database->isError() ) {
            $Qcd = $osC_Database->query('delete from :table_categories_description where categories_id = :categories_id');
            $Qcd->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
            $Qcd->bindInt(':categories_id', $category['id']);
            $Qcd->setLogging($_SESSION['module'], $id);
            $Qcd->execute();

            if ( !$osC_Database->isError() ) {
              $Qp2c = $osC_Database->query('delete from :table_products_to_categories where categories_id = :categories_id');
              $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
              $Qp2c->bindInt(':categories_id', $category['id']);
              $Qp2c->setLogging($_SESSION['module'], $id);
              $Qp2c->execute();

              if ( !$osC_Database->isError() ) {
                $osC_Database->commitTransaction();

                osC_Cache::clear('categories');
                osC_Cache::clear('category_tree');
                osC_Cache::clear('also_purchased');

                if ( !osc_empty($Qimage->value('categories_image')) ) {
                  $Qcheck = $osC_Database->query('select count(*) as total from :table_categories where categories_image = :categories_image');
                  $Qcheck->bindTable(':table_categories', TABLE_CATEGORIES);
                  $Qcheck->bindValue(':categories_image', $Qimage->value('categories_image'));
                  $Qcheck->execute();

                  if ( $Qcheck->numberOfRows() ) {
                    if (file_exists(realpath('../' . DIR_WS_IMAGES . 'categories/' . $Qimage->value('categories_image')))) {
                      @unlink(realpath('../' . DIR_WS_IMAGES . 'categories/' . $Qimage->value('categories_image')));
                    }
                  }
                }
              } else {
                $osC_Database->rollbackTransaction();
              }
            } else {
              $osC_Database->rollbackTransaction();
            }
          } else {
            $osC_Database->rollbackTransaction();
          }
        }

        foreach ( $products_delete as $id ) {
          osC_Products_Admin::delete($id);
        }

        osC_Cache::clear('categories');
        osC_Cache::clear('category_tree');
        osC_Cache::clear('also_purchased');

        return true;
      }

      return false;
    }

    public static function move($id, $new_id) {
      global $osC_Database;

      $category_array = explode('_', $new_id);

      if ( in_array($id, $category_array)) {
        return false;
      }

      $Qupdate = $osC_Database->query('update :table_categories set parent_id = :parent_id, last_modified = now() where categories_id = :categories_id');
      $Qupdate->bindTable(':table_categories', TABLE_CATEGORIES);
      $Qupdate->bindInt(':parent_id', end($category_array));
      $Qupdate->bindInt(':categories_id', $id);
      $Qupdate->setLogging($_SESSION['module'], $id);
      $Qupdate->execute();

      osC_Cache::clear('categories');
      osC_Cache::clear('category_tree');
      osC_Cache::clear('also_purchased');

      return true;
    }
  }
?>
