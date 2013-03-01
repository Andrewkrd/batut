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
* @lastmod $Date: 2012/01/31 17:07:54 $:  Date of last commit
* @version $Rev:: 71                                                  $:  Revision of last commit
* @Id $Id: kiss_meta_tags_class.php,v 1.5 2012/01/31 17:07:54 ujirafika.ujirafika Exp $:  Full Details   
*/

  final class KissMT {
    
    private static $_singleton = null;
    private $abstracts_path;
    private $modules_path;
    private $stopwords_path;
    private $smallwords_path;
    private $module;
    private $html_end;
    private $cache_path; 
    private $language;
    private $languages_id;
    private $cache_prefix = 'kissmt_';
    private $request_type = 'NONSSL';
    private $breadcrumb = array();
    private $canonical = false;
    private $title_separator;
    private static $entitites_supported = array( 'ISO-8859-1', 'ISO-8859-15', 'UTF-8', 'CP866', 'CP1251', 'CP1252', 'KOI8-R', 'BIG5', 'GB2312', 'BIG5-HKSCS', 'SHIFT_JIS', 'EUC-JP' );

    public $basename;
    public $title;
    public $page_title;
    public $keywords;
    public $description;
    public $cachefile_size = 'Not loaded';
    public $stopwords = array();
    public $smallwords = array();
    public $performance = array( 'time' => 0, 'querycount' => 0, 'queries' => array() );

    
    private function __construct() {
    } // end constructor
    
    public function __destruct() {
      $this->performance();
    } // end destructor
    
    /**
    * Obtain a singleton instance of the class
    */
    public static function init() {
      if ( is_null( self::$_singleton ) ) {
        return self::$_singleton = new self;
      } else {
        return self::$_singleton;
      }
    } // end method
    
    /**
    * Set up all the main parameters
    * 
    * @param string $language
    * @param int $languages_id
    * @param array $breadcrumb
    * @param string $request_type
    */
    public function setup( $language, $language_code ,$languages_id, $breadcrumb, $request_type) {
      $this->title_separator = ( defined( 'KISSMT_TITLE_SEPARATOR' ) && !is_null( KISSMT_TITLE_SEPARATOR ) ) ? trim( KISSMT_TITLE_SEPARATOR ) : '-';
      $this->setPaths();
      include_once $this->includes_path . 'init.php';
      include_once 'includes/modules/kiss_meta_tags/languages/' . $language_code . '/kissmt.php'; 
      include_once $this->abstracts_path . 'kiss_modules.php';
      $this->basename = $this->setBaseName();
      ( KISSMT_XHTML_OUTPUT === 'true' ) ? $this->html_end = ' />' : $this->html_end = '>'; 
      $this->language = $language;
      $this->languages_id = $languages_id;
      $this->request_type = $request_type;
      $this->getStopWords();
      $this->getSmallWords();
      $this->setBreadcrumb( $breadcrumb );   
      $this->loadModules();
      $this->module->process();
    } // end method
    
    /**
    * Ensure that we have working paths or trigger a warning
    */
    private function setPaths() {
      $kiss_path = 'includes/modules/kiss_meta_tags/';
      $path = false;
      $dynamic_path_remove = 'includes/modules/kiss_meta_tags/classes';
      $dynamic_path = str_replace( $dynamic_path_remove, '', str_replace( DIRECTORY_SEPARATOR, '/', realpath( dirname( __FILE__ ) ) ) );
      if ( file_exists( DIR_FS_CATALOG . $kiss_path . 'kiss_meta_tags.php' ) ) {
        $path =  DIR_FS_CATALOG . $kiss_path;
      } elseif ( file_exists( $dynamic_path . $kiss_path . 'kiss_meta_tags.php' ) ) {
        $path = $dynamic_path . $kiss_path;
      } 
      if ( false !== $path ) {
        $this->cache_path      = $path . 'cache/'; 
        $this->abstracts_path  = $path . 'abstracts/';
        $this->modules_path    = $path . 'modules/';
        $this->includes_path   = $path . 'includes/';
        $this->stopwords_path  = $path . 'stopwords/';
        $this->smallwords_path = $path . 'smallwords/';
        if ( false === is_writable( $this->cache_path ) ) {
          trigger_error( __CLASS__ . ' can\'t write to the cache directory. Please change the permissions.', E_USER_WARNING );
        }
        return;
      }
      trigger_error( __CLASS__ . ' can\'t find a filepath! DIR_FS_CATALOG MUST contain a full path.', E_USER_WARNING ); 
    } // end method
    
    /**
    * Set the Kiss Module Name to load - platform independent - 
    */
    private function setBaseName() {

	  global $osC_Product, $osC_Reviews, $osC_Specials; 

      $base = new ArrayIterator( array( 'SCRIPT_NAME', 'PHP_SELF' ) );
      while ( $base->valid() ) { 
        if ( array_key_exists(  $base->current(), $_SERVER ) && !empty(  $_SERVER[$base->current()] ) ) {
          if ( false !== strpos( $_SERVER[$base->current()], '.php' ) ) {
            preg_match( '@[a-z0-9_]+\.php@i', $_SERVER[$base->current()], $matches );
            if ( is_array( $matches ) && ( array_key_exists( 0, $matches ) )
                                      && ( substr( $matches[0], -4, 4 ) == '.php' )
                                      && ( is_readable( $matches[0] ) ) ) {
				// return index promptly
                if ($matches[0] == 'index.php') return $matches[0];

				if ($matches[0] == 'products.php') {                                      
					// Regular product 
					if (isset($osC_Product) && !isset($_GET['reviews'])) {
						return 'products.php';

					// New Products
					} elseif ( isset($_GET['new']) ) {
						return 'products_new.php';

					// Specials
					} elseif ( isset($_GET['specials']) && isset($osC_Specials) ) {
						return 'specials.php';

					// All reviews
					} elseif ( isset($_GET['reviews']) &&  !isset($osC_Product) && isset($osC_Reviews) ) {
						return 'reviews.php';

					// Page display product review on file
					} elseif ( isset($_GET['reviews']) && is_numeric($_GET['reviews']) ) {
						return 'product_reviews_info.php';

					// Check if product review on file for product
					} elseif ( isset($_GET['reviews']) &&  isset($osC_Product) ) {
						return 'product_reviews.php';

					// return default index tags if products.php is surfed improperly from store root
					} elseif ( !isset($osC_Product) ) {		
						return 'index.php';
					}
				}

				// these .php files may not necessarily exist, this is so that defines in language files (kissmt.php) will work  
				switch ( $matches[0] ) {

					case 'info.php':

					   // tags for shipping.php
					   if ( strpos($_SERVER['REQUEST_URI'], 'shipping') !== false )
					   {
							// write tags
							return 'shipping.php';
					   } 

					   // tags for privacy.php
					   elseif ( strpos($_SERVER['REQUEST_URI'], 'privacy') !== false)
					   {
							// write tags
							return 'privacy.php';
					   } 

					   // tags for conditions.php
					   elseif ( strpos($_SERVER['REQUEST_URI'], 'conditions') !== false)
					   {
							// write tags
							return 'conditions.php';
					   }

					   // tags for contact_us.php
					   elseif ( strpos($_SERVER['REQUEST_URI'], 'contact') !== false)
					   {
							// write tags
							return 'contact.php';
					   }

					   // tags for sitemap.php
					   elseif ( strpos($_SERVER['REQUEST_URI'], 'sitemap') !== false)
					   {
						   	// write tags
							return 'sitemap.php';
					   } 
	
					   elseif (strpos($_SERVER['REQUEST_URI'], 'info.php') !== false)
					   {
						   	// write tags default info
							return 'information.php';					   }
							
					   elseif (strpos($_SERVER['REQUEST_URI'], 'account.php') !== false)
					   {
						   	// write tags default info
							return 'account.php';					   }

					default:

				}

				// retun anything else
                return $matches[0];
            } 
          } 
        }
        $base->next();
      }
      // Some odd server set ups return / for SCRIPT_NAME and PHP_SELF when accessed as mysite.com (no index.php) where they usually return /index.php
      if ( ( $_SERVER['SCRIPT_NAME'] == '/' ) && ( $_SERVER['PHP_SELF'] == '/' ) ) {
        return 'index.php';
      } 
      trigger_error( 'KissMT could not find a valid base filename, please inform the developer.', E_USER_WARNING );
    } // end method
    
    /**
    * Load the stopwords based on language
    */
    private function getStopWords() {

      $stopwords_path = $this->stopwords_path . 'russian' . '_stopwords.ini';
      if ( is_readable( $stopwords_path ) ) {
        $ini = parse_ini_file( $stopwords_path );
        $this->stopwords = $ini['stopword'];
      }
    } // end method
    
    private function getSmallWords() {
      $smallwords_path = $this->smallwords_path . $this->language . '.php';
      if ( is_readable( $smallwords_path ) ) {
        include_once $smallwords_path;
        $this->smallwords = $smallwordsarray;
      }
    }
    
    /**
    * Set and reverse the breadcrumb array
    * 
    * @param array $breadcrumb
    */
    private function setBreadCrumb( $breadcrumb ) {
	  global $osC_Language;
      $count = count( $breadcrumb );
      $crumb = array();
      for ( $i=0; $i<($count); $i++ ) {
		$breadcrumb[$i] = html_entity_decode(strip_tags($breadcrumb[$i]));
        if ( $breadcrumb[$i] != $osC_Language->get('breadcrumb_top') && $breadcrumb[$i] != $osC_Language->get('breadcrumb_shop') ) {
          $crumb[$i] = $breadcrumb[$i];
        }
      }
      $crumb = array_reverse( $crumb );
      $this->breadcrumb = $crumb;
    } // end method

    /**
    * Load the page based modules
    * If no module exists for the page load the generic module
    */
    private function loadModules() {
      if ( is_readable( $this->modules_path . $this->basename ) ) {
        include_once $this->modules_path . $this->basename;
        $this->module = new KissMT_Module;
        $this->module->gc();
        return;
      }
      if ( is_readable( $this->modules_path . 'generic_module.php' ) ) {
        include_once $this->modules_path . 'generic_module.php';
        $this->module = new KissMT_Module;
        $this->module->gc();
        return;
      }
      trigger_error( 'KissMT could not find a module for this filename please inform the developer.', E_USER_WARNING ); 
    } // end method
    
    /**
    * Retrieve a private property from the class
    * 
    * @param string $to_retrieve
    */
    public function retrieve( $to_retrieve ) {
      if ( isset( $this->{$to_retrieve} ) ) {
        return $this->{$to_retrieve};
      }
      trigger_error( 'Attempt to retrieve a non existent property of ' . __CLASS__ . ' named ' . $to_retrieve, E_USER_WARNING );
    } // end method
    
    /**
    * Query wrapper
    * 
    * Purely exists to allow monitoring of the number and content of queries
    * @param string $sql - The query
    * @return resource - query result
    */
    public function query( $sql ) {
	  global $osC_Database;
      $this->performance['querycount']++;
      $this->performance['queries'][] = $sql;
      $time = microtime( true );
	  $result = $osC_Database->query(':sqlraw');
	  $result->bindRaw(':sqlraw',$sql);
	  $result->execute();
      $this->performance['time'] += ( microtime( true ) - $time );
      return $result;
    } // end method
    
    /**
    * Externally set the private property $canonical
    * 
    * @param string $canonical_url - either a full url or string noindex
    */
    public function setCanonical( $canonical_url ) {
      $this->canonical = $canonical_url;
    } // end method
    
    /**
    * Print a tag if required based on the value of $canonical
    * 
    */
    private function canonical() {
      if ( ( KISSMT_CANONICAL_ON == 'true' ) && ( false !== $this->canonical ) ) {
        if ( $this->canonical == 'noindex' ) {
          echo '<meta name="robots" content="noindex, follow"/' . $this->html_end . PHP_EOL;;
        } else {
          echo '<link rel="canonical" href="' . $this->canonical . '"/' . $this->html_end . PHP_EOL;;
          echo '<meta name="robots" content="index, follow"/' . $this->html_end . PHP_EOL;;
        }
      }
      return false;
    } // end method
    
    public static function entities( $string, $decode = false) {

      global $char_set;

      if ( in_array( strtoupper($char_set ), self::$entitites_supported ) ) {
        if ( false === $decode ) {
          return htmlentities( trim( $string ), ENT_QUOTES, $char_set );
        } else {
          return html_entity_decode( trim( $string ), ENT_QUOTES, $char_set );
        }
      }
      return trim( $string );
    } 
    
    /**
    * Output the final meta tags
    */
    public function output() {
      echo '<title>' . $this->title . '</title>' . PHP_EOL .
      '<meta name="description" content="' . $this->description . '"/' . $this->html_end . PHP_EOL .
      '<meta name="keywords" content="' . $this->keywords . '"/' . $this->html_end . PHP_EOL;
      $this->canonical();
    } // end method
    
    /**
    * Performance reporting
    * 
    * reports on links built queries used, time to load cache, cache size
    */
    private function performance() {

      if ( KISSMT_PERFORMANCE_OUTPUT !== 'true' ) {
        return false;
      }
      $performance_time = ( 'false' !== $this->performance['time'] ) ? round( $this->performance['time'], 4 ) . ' seconds' : '<span style="color: red;">cache not loaded</span>';
?>
<div style="padding: 3em; font-family: verdana; width: 750px; margin: auto;">
  <div style="width: 100%; background-color: #ffffdd; border: 1px solid #1659AC; font-size: 10pt;">
    <div style="background-color: #2E8FCA; font-size: 12pt; font-weight: bold; padding: 0.5em; color: #00598E;">
      <div style="float: right; color: #0073BA; font-weight: bold; font-size: 16pt; margin-top: -0.2em;">FWR MEDIA</div>
        KissMT Dynamic Seo Meta Tags - Performance
    </div>
    <div style="padding: 0.5em; background-color: #CCE3F1; color: #027AC6; font-size: 10pt;">Query Count: <?php echo $this->performance['querycount']; ?></div>
    <div style="padding: 0.5em; color: #027AC6; font-size: 10pt;"><?php echo ( ($this->performance['querycount'] > 0 ) ? 'Query load time ' : 'Cache load time ' ) . $performance_time; ?></div>
    <div style="padding: 0.5em; background-color: #CCE3F1; color: #027AC6; font-size: 10pt;">Cache File Size: <?php echo $this->cachefile_size; ?></div>
    <div style="background-color: #fff; padding: 0.5em; color: #737373; font-size: 10pt;">
      <div style="padding: 0.5em;"><span style="font-weight: bold; text-decoration: underline;">Queries:</span></div>
<?php
      foreach ( $this->performance['queries'] as $index => $query ) {
        echo '      <div style="padding: 0.2em; font-family: tahoma; font-size: 7pt;">' . $query . '</div>' . PHP_EOL;
      }
      if( KISSMT_CLASS_OUTPUT == 'true' ) {
         echo '      <hr />' . PHP_EOL;
        echo '      <div style="font-size: 7pt;"><pre>' . print_r( $this, true ) . '</pre></div>' . PHP_EOL;
      }
?>
    </div>
  </div>
</div>
<?php
    } // end method

  } // End class
?>
