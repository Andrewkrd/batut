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
* @lastmod $Date: 2012/09/17 19:59:57 $:  Date of last commit
* @version $Rev:: 69                                                  $:  Revision of last commit
* @Id $Id: init.php,v 1.3 2012/09/17 19:59:57 ujirafika.ujirafika Exp $:  Full Details   
*/

  /**
  * The number of products or categories to retrieve from the database for description/keywords.
  */
  defined( 'KISSMT_CATEGORIES_PRODUCTS_LIMIT' ) || define( 'KISSMT_CATEGORIES_PRODUCTS_LIMIT', 4 );
  /**
  * Separator for the page title words.
  */
  defined( 'KISSMT_TITLE_SEPARATOR' )           || define( 'KISSMT_TITLE_SEPARATOR', ':' );
  /**
  * Limit the meta description to X characters
  */
  defined( 'KISS_MT_META_DESCRIPTION_LENGTH' )  || define( 'KISS_MT_META_DESCRIPTION_LENGTH', 300 );



  /**
  * Limit the meta keywords to X number of keywords
  */
  defined( 'KISS_MT_META_KEYWORDS_NUMWORDS' )   || define( 'KISS_MT_META_KEYWORDS_NUMWORDS', 10 );
  /**
  * Set the number of words in each generated keyword  (product)
  */
  defined( 'KISS_MT_META_KEYWORD_SIZE' )   || define( 'KISS_MT_META_KEYWORD_SIZE', 2 );


  /**
  * Limit the product name length
  */
  defined( 'KISS_MT_META_TITLE_LIMIT_NUMWORDS' )   || define( 'KISS_MT_META_TITLE_LIMIT_NUMWORDS', true);
  /**
  * Limit the product name to X words in meta title if the above is true
  */
  defined( 'KISS_MT_META_TITLE_NUMWORDS' )   || define( 'KISS_MT_META_TITLE_NUMWORDS', 16);
  /**
  * Limit the overall meta title to the nearest full word to X characters
  */
  defined( 'KISS_MT_META_TITLE_LENGTH' )        || define( 'KISS_MT_META_TITLE_LENGTH', 220 );




  /**
  * Limit the page title to the nearest full word to X characters
  */
  defined( 'KISS_MT_PAGE_TITLE_LENGTH' )        || define( 'KISS_MT_PAGE_TITLE_LENGTH', 200 );
  /**
  * XHTML tag output - string true / false
  */
  defined( 'KISSMT_XHTML_OUTPUT' )              || define( 'KISSMT_XHTML_OUTPUT', 'false' );
  /**
  * Output the performance info - you'll see it at the bottom of the page.
  * string true / false
  */
  defined( 'KISSMT_PERFORMANCE_OUTPUT' )        || define( 'KISSMT_PERFORMANCE_OUTPUT', 'false' );
  /**
  * Output the class properties. - you'll see it at the bottom of the page
  * Note: This will only output if KISSMT_PERFORMANCE_OUTPUT is also set to true.
  */
  defined( 'KISSMT_CLASS_OUTPUT' )              || define( 'KISSMT_CLASS_OUTPUT', 'false' );
  /**
  * Cache reset - string reset / false  
  */
  defined( 'KISSMT_CACHE_RESET' )               || define( 'KISSMT_CACHE_RESET', 'false' );
  /**
  * Cache on/off switch - string true / false  
  */
  defined( 'KISSMT_CACHE_ON' )                  || define( 'KISSMT_CACHE_ON', 'false' );
  /**
  * Show the canonical tag for relevant pages - string true / false
  */
  defined( 'KISSMT_CANONICAL_ON' )              || define( 'KISSMT_CANONICAL_ON', 'true' ); 
  
  
  defined( 'KISSMT_DESCRIPTIONS_EXTENSION_ENABLE' )              || define( 'KISSMT_DESCRIPTIONS_EXTENSION_ENABLE', 'true' );  
?>
