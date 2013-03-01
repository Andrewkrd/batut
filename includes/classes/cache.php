<?php
/*
  $Id: cache.php,v 1.3 2012/09/04 20:19:50 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

/**
 * The osC_Cache class handles the caching of dynamically generated data
 */

  class osC_Cache {

/**
 * The cached data
 *
 * @var mixed
 * @access private
 */

    private $_data;

/**
 * The key ID for the cached data
 *
 * @var string
 * @access private
 */

    private $_key;

/**
 * Write the data to a cache file
 *
 * @param string mixed $data The data to cache
 * @param string $key The key ID to save the cached data with
 * @access public
 */

    public function write($data, $key = null) {
    	$expire = CACHE_EXPIRE;
    	if(ENABLE_CACHE) {
	      if ( empty($key) ) {
	        $key = $this->_key;
	      }
	
	      return apc_add( $key , serialize($data), $expire) ;
    	}
    	else
    		return true;
	 
    }   
    /*
    public function write($data, $key = null) {
	// производим запись на диск только в том случае если включен кэш
    	if(ENABLE_CACHE) {
	      if ( empty($key) ) {
	        $key = $this->_key;
	      }
	
	      return ( file_put_contents(DIR_FS_WORK . $key . '.cache', serialize($data), LOCK_EX) !== false );
    	}
    	else
    		return true;
	 
    }*/

/**
 * Read data from a cache file if it has not yet expired
 *
 * @param string $key The key ID to read the data from the cached file
 * @param int $expire The amount of minutes the cached data is active for
 * @access public
 * @return boolean
 */

   public function read($key, $expire = null) {
      $this->_key = $key;
	  $expire = CACHE_EXPIRE;

      if(ENABLE_CACHE) {
	      if ( apc_fetch($key) ) {
	
			$this->_data = unserialize(apc_fetch($key));
					
			return true;         


	      }
      }
      return false;
    }
    
/* КЭШ на файлах   
      public function read($key, $expire = null) {
      $this->_key = $key;
	  $expire = 1440;
      $filename = DIR_FS_WORK . $key . '.cache';
// Не читать с диска вообще ничего чтобы не создавать нагрузку на дисковую подсистему.
      if(ENABLE_CACHE) {
	      if ( file_exists($filename) ) {
	        $difference = floor((time() - filemtime($filename)) / 60);
	
		//      if(ENABLE_CACHE) {
		        if ( empty($expire) || ( is_numeric($expire) && ($difference < $expire)) ) {
		        	$this->_data = unserialize(file_get_contents($filename));
					
		         	return true;         
		        }
		//      }
	      }
      }
      return false;
    }
*/
    
    
/**
 * Return the cached data
 *
 * @access public
 * @return mixed
 */

    public function getCache() {
      return $this->_data;
    }

/**
 * Start the buffer to cache its contents
 *
 * @access public
 */

    public function startBuffer() {
      ob_start();
    }

/**
 * Stop the buffer and cache its contents
 *
 * @access public
 */

    public function stopBuffer() {
      $this->_data = ob_get_contents();

      ob_end_clean();

      $this->write($this->_data);
    }

/**
 * Delete cached files by their key ID
 *
 * @param string $key The key ID of the cached files to delete
 * @access public
 */

   public static function clear($key) {
      apc_delete ($key); 
    } 
   /* public static function clear($key) {
      $key_length = strlen($key);

      $d = dir(DIR_FS_WORK);

      while ( ($entry = $d->read()) !== false ) {
        if ( (strlen($entry) >= $key_length) && (substr($entry, 0, $key_length) == $key) ) {
          @unlink(DIR_FS_WORK . $entry);
        }
      }

      $d->close();
    }*/
  }
?>
