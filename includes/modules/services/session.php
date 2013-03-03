<?php
/*
  $Id: session.php,v 1.1 2011/08/30 21:13:24 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Services_session {
    function start() {
      global $request_type, $osC_Session;

      include('includes/classes/session.php');
      $osC_Session = osC_Session::load();
	
      if (SERVICE_SESSION_FORCE_COOKIE_USAGE == '1') {
        osc_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*90);
		
        if (isset($_COOKIE['cookie_test'])) {
          $osC_Session->start();
        }
      } elseif (SERVICE_SESSION_BLOCK_SPIDERS == '1') {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $spider_flag = false;

        if (empty($user_agent) === false) {
          $spiders = file('includes/spiders.txt');

          foreach ($spiders as $spider) {
            if (empty($spider) === false) {
              if (strpos($user_agent, trim($spider)) !== false) {
                $spider_flag = true;
                break;
              }
            }
          }
        }

        if ($spider_flag === false) {
          $osC_Session->start();
        }
      } else {
        $osC_Session->start();
      }

// verify the ssl_session_id
      if ( ($request_type == 'SSL') && (SERVICE_SESSION_CHECK_SSL_SESSION_ID == '1') && (ENABLE_SSL == true) ) {
        if (isset($_SERVER['SSL_SESSION_ID']) && ctype_xdigit($_SERVER['SSL_SESSION_ID'])) {
          if (isset($_SESSION['SESSION_SSL_ID']) === false) {
            $_SESSION['SESSION_SSL_ID'] = $_SERVER['SSL_SESSION_ID'];
          }

          if ($_SESSION['SESSION_SSL_ID'] != $_SERVER['SSL_SESSION_ID']) {
            $osC_Session->destroy();

            osc_redirect(osc_href_link(FILENAME_INFO, 'ssl_check', 'AUTO'));
          }
        }
      }

// verify the browser user agent
      if (SERVICE_SESSION_CHECK_USER_AGENT == '1') {
        $http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

        if (isset($_SESSION['SESSION_USER_AGENT']) === false) {
          $_SESSION['SESSION_USER_AGENT'] = $http_user_agent;
        }

        if ($_SESSION['SESSION_USER_AGENT'] != $http_user_agent) {
          $osC_Session->destroy();

          osc_redirect(osc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
        }
      }

// verify the IP address
      if (SERVICE_SESSION_CHECK_IP_ADDRESS == '1') {
        if (isset($_SESSION['SESSION_IP_ADDRESS']) === false) {
          $_SESSION['SESSION_IP_ADDRESS'] = osc_get_ip_address();
        }

        if ($_SESSION['SESSION_IP_ADDRESS'] != osc_get_ip_address()) {
          $osC_Session->destroy();

          osc_redirect(osc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
        }
      }

      return true;
    }

    function stop() {
      global $osC_Session;

      $osC_Session->close();

      return true;
    }
  }
?>
