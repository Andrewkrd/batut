<?php
/*
  $Id: apc.php,v 1.1 2012/10/09 21:51:36 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/


/**
 * The osC_Session_apc class stores the session data in the apc
 */

  class osC_Session_apc extends osC_Session {//extends osC_Session_database {


/**
 * Constructor, loads the memcache based session storage handler
 *
 * @param string $name The name of the session
 * @access public
 */

    public function __construct($name = null) {
      parent::__construct($name);

        session_set_save_handler(array(&$this, '_apc_open'),
                                 array(&$this, '_apc_close'),
                                 array(&$this, '_apc_read'),
                                 array(&$this, '_apc_write'),
                                 array(&$this, '_apc_destroy'),
                                 array(&$this, '_apc_gc'));
      
    }

/**
 * Opens the memcache based session storage handler
 *
 * @access protected
 */

    protected function _apc_open() {
	return true;
    }

/**
 * Closes the memcache based session storage handler
 *
 * @access protected
 */

    protected function _apc_close() {
	return true;
    }

/**
 * Read session data from the memcache based session storage handler
 *
 * @param string $id The ID of the session
 * @access protected
 */

    protected function _apc_read($id) {
      $id = 'osc_sess_' . $id;
	  return apc_fetch($id);
    }

/**
 * Writes session data to the memcache based session storage handler
 *
 * @param string $id The ID of the session
 * @param string $value The session data to store
 * @access protected
 */

    protected function _apc_write($id, $value) {
      $id = 'osc_sess_' . $id;

      if ( apc_exists($id) ) {
      	$var = apc_delete($id);
        return apc_add($id, $value, $this->_life_time);
        
      } else {
        return apc_add($id, $value, $this->_life_time);
      }
    }

/**
 * Destroys the session data from the memcache based session storage handler
 *
 * @param string $id The ID of the session
 * @access protected
 */

    protected function _apc_destroy($id) {
      $id = 'osc_sess_' . $id;

      return apc_delete($id);
    }

/**
 * Garbage collector for the memcache based session storage handler
 *
 * @param string $max_life_time The maxmimum time a session should exist
 * @access protected
 */

    protected function _apc_gc($max_life_time) {
      return true;
    }
  }
?>
