<?php 
/**
*
* KISS Dynamic SEO Meta Tags
* KISS = (Keep It Simple Stupid!) 
* 
* @package KISS Meta Tags SEO v1.0
* @license http://www.opensource.org/licenses/gpl-2.0.php GNU Public License
* @link http://www.fwrmedia.co.uk
* @copyright Copyright 2008-2009 FWR Media
* @author Robert Fisher, FWR Media, http://www.fwrmedia.co.uk 
* @lastdev $Author: ujirafika.ujirafika $:  Author of last commit
* @lastmod $Date: 2011/08/30 21:13:08 $:  Date of last commit
* @version $Rev:: 10                                                  $:  Revision of last commit
* @Id $Id: kiss_meta_tags.php,v 1.1 2011/08/30 21:13:08 ujirafika.ujirafika Exp $:  Full Details   
*/

  include_once 'includes/modules/kiss_meta_tags/classes/kiss_meta_tags_class.php';
  
  /**
  * Set the language - load the language specific stopwords - set everything up
  */

  $char_set = $osC_Language->getCharacterSet();
  KissMT::init()->setup( strtolower($osC_Language->getName()), $osC_Language->getCode() , $osC_Language->getID(), $osC_Breadcrumb->getArray(), $request_type );
  
  /**
  * Output the meta html
  */
  KissMT::init()->output();
?>
