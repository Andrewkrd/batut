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
* @lastmod $Date: 2012/10/09 21:51:39 $:  Date of last commit
* @version $Rev:: 63                                                  $:  Revision of last commit
* @Id $Id: kiss_modules.php,v 1.8 2012/10/09 21:51:39 ujirafika.ujirafika Exp $:  Full Details   
*/

  abstract class KissMT_Modules {
    
    private $cache_retrieved = false;
    private $cache;
    private $cache_md5 = false;
    protected $cache_name;
    protected $get_value;
    protected $original_get;
    protected $cache_suffix;    
    protected $markers = array();
    protected $root_index = 'root'; // options - root = www.mysite.com/ - index = www.mysite.com/FILENAME_DEFAULT (index.php)
    
    public function __construct() {
    } // end constructor

    /**
    * Store the cache on class destruction
    */
    public function __destruct() {
      $this->store();
    } // end destructor
    
    /**
    * Located in the modules files
    */
    abstract public function process();
    
    /**
    * Remove parents from the presented path returning an integer
    * 
    * @param string $path
    * @return int
    */
    protected function parsePath( $path ) {
      if ( false !== strpos( $path, '_' ) ) {
        $as_array = explode( '_', $path );
        $count = count( $as_array );
        if ( is_numeric( $as_array[( $count-1 )] ) ) {
          return $as_array[( $count-1 )];
        }
      }
      if ( is_numeric( $path ) ) {
        return $path;
      }
    } // end method
    
    /**
    * Set the cache filename based on a number of parameters
    * 
    * @param string $basename
    * @param string $get_key
    * @param mixed $get_value - integer or a path with underscores
    */
    protected function setCacheString( $basename, $get_key, $get_value ) {
      return KissMT::init()->retrieve( 'cache_prefix' ) . KissMT::init()->retrieve( 'languages_id' ) . '_' . str_replace( '.php', '', basename( $basename ) ) . '_' . $get_key . '_' . $get_value . '.cache'; 
    } // end method
    
    public function getCacheName() {
      return $this->cache_name; 
    } // end method
    
    /**
    * Initiate the parsing of the string into title keywords and description
    * 
    * @param string $leading_values - main keywords
    * @param string $description - description and possible extra keywords
    */
    protected function parse( $leading_values, $description = false, $price = null , $database_keywords = null, $shortProductTitle = '',$fullProductTitle='', $title_padding = true) {
		//echo $leading_values;
      $leading_values = str_replace( array_keys( $this->markers ), array_values( $this->markers ), $leading_values ); 
      if (!empty($shortProductTitle) ) {


		if (is_null($price)) {
			//echo $leading_values;
			//$this->buildDescription( $price , $leading_values, $fullProductTitle.$description);    	
			$this->buildDescription( $price , $leading_values, $description);  	
			$this->buildTitle( $leading_values . '[-separator-]' . $shortProductTitle, $description);
			$this->buildKeyWords( $fullProductTitle . $shortProductTitle . '[-separator-]' . $leading_values . ' ' . $description , $database_keywords);	
 					
		} else {
			//echo $description;
			$this->buildDescription( $price , $leading_values, $description);    		
			$this->buildKeyWords( $fullProductTitle . $leading_values . ' ' . $description , $database_keywords);		
			$this->buildTitle( $shortProductTitle . $leading_values, $description);		
		}
		$this->buildPageTitle( $shortProductTitle . $leading_values ); 
	  } else {
	  	//echo $leading_values;
		$this->buildDescription( $price , $leading_values, $description);      
		$this->buildKeyWords( $fullProductTitle . $leading_values . ' ' . $description , $database_keywords);
		$this->buildTitle( $fullProductTitle . $leading_values, $description, $title_padding );
		$this->buildPageTitle( $fullProductTitle . $leading_values ); 	  
	  }
    } // end method

    /**
    * Strip out unwanted characters, spaces etc.
    * 
    * @param mixed $string - string to be stripped
    * @param mixed $type - title/keywords/description may need a different strip pattern
    * @return string - formatted/stripped string
    */
    private function stripCharacters( $string, $type = 'description' ) {
      $no_tags = preg_replace( '@<[\/\!]*?[^<>]*?>@si', ' ', $this->stripSymbols( $string ) );
      switch( $type ) {
        case 'keywords':
          $pattern = "@[!#\$%&\"()\*\+,\./:;<=>\?\@\[\]\^_`\{|\}~\n\r\t]+@";
          break;
        case 'title':
          $pattern = "@[!#\$%\"()\*\+,\/:;<=>\?\@\[\]\^_`\{|\}~\n\r\t]+@";
          break;
        default:
          $pattern = "@[!#\$%\"()\*\+/:;<=>\?\@\[\]\^_`\{|\}~\n\r\t]+@";
          break;
      }
      $no_special_characters = preg_replace( $pattern, ' ', $no_tags );
      $no_special_characters = str_replace( "'", '', $no_special_characters );
      return $remove_multi_spaces = preg_replace( '@[\s]{2,100}@', ' ', $no_special_characters );
    }
    
    private function buildPageTitle( $leading_values ) {
      if ( mb_substr( $leading_values, -13, 13, "UTF-8" ) == '[-separator-]' ) {
        $leading_values = mb_substr( $leading_values, 0, mb_strlen( $leading_values ) -13 );
      }
      KissMT::init()->page_title = $this->trimWordsToLength( str_replace( '[-separator-]', ' ' . KissMT::init()->retrieve( 'title_separator' ) . ' ', $leading_values ), 'page_title' );
    }
    
    /**
    * Specific processing of meta keywords
    * 
    * @param string $keywords - string to be converted into keywords
    * @return string
    */
    private function buildKeyWords( $keywords ,$database_keywords = null) {
	  
	  global $osC_Product;
	  
	  $keywords = rtrim( trim( str_replace( '[-separator-]', ',', $keywords ) ), ',' );
	  $stripped =  $this->stripCharacters( $keywords, 'keywords' );
      $words_as_array = explode( ' ', $stripped );   
      array_walk( $words_as_array, array( __CLASS__, 'stripStopWords' ) );

	  if (isset($osC_Product)) {	

		  if (!empty($database_keywords)) {
	 	  	$database_keywords  = rtrim( trim( str_replace( '[-separator-]', ',', $database_keywords ) ), ',' ); 
			$database_keywords_array = explode( ',', $database_keywords ); 	  
		  	array_walk( $database_keywords_array, array( __CLASS__, 'stripCharacters' ) );	
		    array_walk( $database_keywords_array, array( __CLASS__, 'stripStopWords' ) );  
			$database_keywords  =   KissMT::init()->entities( rtrim( str_replace( array( '[--replaced--],', '[--replaced--]' ), '',  implode( ',', $database_keywords_array ) ), ',' ), $decode = false );	  		     
    			     
		  }

      	  $generatedKeywords = KissMT::init()->entities( rtrim( str_replace( array( '[--replaced--],', '[--replaced--]' ), '',  implode( ',', $words_as_array ) ), ',' ), $decode = false );	  
		  $group_words  = explode(',',$generatedKeywords);

		  $jjj = 1;
		  for ($iii = 0; $iii <= (count($group_words)-1) ; $iii++) {
		  		
		  		if (!empty($group_words[$iii])) {
					KissMT::init()->keywords .= $group_words[$iii] . ' ';
					if ($jjj == KISS_MT_META_KEYWORD_SIZE) {
						KissMT::init()->keywords = trim(KissMT::init()->keywords);
						KissMT::init()->keywords .= ',';
						$jjj = 1;
					} else {$jjj++;}
				}
				
		  }

		  KissMT::init()->keywords = $this->limitMeta( ($database_keywords ? $database_keywords . ',' : '') . KissMT::init()->keywords	, 'keywords' );	    

	  } else {

      	  KissMT::init()->keywords = $this->limitMeta( KissMT::init()->entities( rtrim( str_replace( array( '[--replaced--],', '[--replaced--]' ), '',  implode( ',', $words_as_array ) ), ',' ), $decode = false ), 'keywords' );
	  
	  }  

    } // end method
    
    /**
    * Specific processing of meta description
    * 
    * @param string $leading_values - main keywords
    * @param string $description - description and possible extra keywords
    * @return string
    */
    private function buildDescription( $price , $leading_values, $description ) {
      //echo str_replace( '[-separator-]', ', ', $description . $leading_values);
      $description = rtrim( trim( str_replace( '[-separator-]', ', ', $description . " " . $leading_values) ), ',' );

      $stripped = $this->stripCharacters( $description, 'description' );
      KissMT::init()->description = $this->limitMeta( ($price?"Цена " . $price.' ':'' ) .  KissMT::init()->entities( trim( rtrim( $stripped, '.' ) ), $decode = false ) . '.' );
    } // end method
    
    /**
    * Specific processing of meta title
    * 
    * @param mixed $leading_values - main keywords
    * @param mixed $description - description and possible extra keywords
    * @return string
    */
    private function buildTitle( $leading_values, $description, $title_padding = true) {
    	
      //$full_title = ( $leading_values . ( !is_null( $description) ? '[-separator-]' . $description : '' ) );
	  $full_title = $leading_values;
	  
      if ( mb_substr( $full_title, -13, 13, "UTF-8" ) == '[-separator-]' ) {
        $full_title = mb_substr( $full_title, 0, strlen( $full_title ) -13, "UTF-8" );
      }
      $full_title = str_replace( '[-separator-]', ' ' . KissMT::init()->retrieve( 'title_separator' ) . ' ', $full_title );  
      $stripped = $this->stripCharacters( $full_title, 'title' );
      
      $title = trim( rtrim( trim( $stripped ), KissMT::init()->retrieve( 'title_separator' ) ) );
      if ( strlen( trim( KISSMT_TITLE_PADDING ) ) > 0 && $title_padding) { // KISSMT_TITLE_PADDING can be left blank
      	if(defined("KISSMT_TITLE_HOME_PADDING"))
            if(KISSMT_TITLE_HOME_PADDING !== "")
      		$title .= ' ' . KissMT::init()->retrieve( 'title_separator' ) . ' '  . sprintf( KISSMT_TITLE_HOME_PADDING, STORE_NAME );
      	else
		$title .= ' ' . KissMT::init()->retrieve( 'title_separator' ) . ' ' . sprintf( KISSMT_TITLE_PADDING, STORE_NAME );
      }
      elseif(strlen( trim( KISSMT_TITLE_PADDING ) ) > 0 && !$title_padding)
		$title .= ' ' . KissMT::init()->retrieve( 'title_separator' ) . ' '  . 'Краснодар';
      KissMT::init()->title = $this->limitMeta( KissMT::init()->entities( trim( $title ), $decode = false ), 'title' ); 
    }
    
    /**
    * limit the meta title/keywords/description in length
    * 
    * @param string $text - string to limit
    * @param string $type - description, keywords, title
    * @return string
    */
    private function limitMeta( $text, $type = "description" ) {
      switch ( $type ) {
        case 'description':
          if ( mb_strlen( $text ) > KISS_MT_META_DESCRIPTION_LENGTH ) {
            return mb_substr( $text, 0, ( KISS_MT_META_DESCRIPTION_LENGTH - 3 ), "UTF-8" ) . '...';
          }
          return $text;
          break;
        case 'keywords':
          $keyword_array = explode( ',', $text );
          return implode( ',', array_splice( $keyword_array, 0, KISS_MT_META_KEYWORDS_NUMWORDS ) );
          break;
        case 'title':

          return $this->formatTitleWords( $text );
          break; 
        default:
        return $text;
          break;
      }
    } // end method
    
    private function formatTitleWords( $text ) {
      if ( !empty( KissMT::init()->smallwords ) ) {
        $words_array = explode( ' ', $text );
        $ucfirst_words = array();
        $it = new ArrayIterator( $words_array );
        while ( $it->valid() ) {
          if( ctype_alpha( mb_substr( $it->current(), 0, 1, "UTF-8" ) ) ) {
            if ( !in_array( mb_strtolower($it->current(), "UTF-8"), KissMT::init()->smallwords ) ) {  
              $ucfirst_words[] = ucfirst( mb_strtolower( $it->current(), "UTF-8" ) );
            } else {
              $ucfirst_words[] = $it->current();
            } 
          } elseif( !is_null( $it->current() ) ) {
            $ucfirst_words[] = $it->current();
          }
          $it->next();
        }
        return $this->trimWordsToLength( implode( ' ', $ucfirst_words ), 'title' );
      }
      return $this->trimWordsToLength( $text, 'title' );
    }
    
    private function trimWordsToLength( $text, $type = 'title' ) {
      $limit = false;
      switch ( $type ) {
        case 'page_title':
        $limit = KISS_MT_PAGE_TITLE_LENGTH;
          break;
        default:
          $limit = KISS_MT_META_TITLE_LENGTH;
          break;
      }
      if ( false !== $limit ) {
        $nearest_max_length_less = 0;
        $current_length = 0;
        $use_words = array();
        $it = new ArrayIterator( explode( ' ', $text ) );
        while ( $it->valid() ) {
          $current_length += ( strlen($it->current()) +1 );
          $use_words[] = $it->current();
          if ( $current_length <= $limit ) { 
            $nearest_max_length_less = $current_length;
          } elseif ( $current_length  >= $limit ) {
            if( ( $limit - $nearest_max_length_less ) < ( $current_length - $limit ) ) {
              unset( $use_words[count( $use_words)-1] );
              if ( trim( $use_words[count( $use_words)-1] ) == KissMT::init()->retrieve( 'title_separator' ) ) {
                unset( $use_words[count( $use_words)-1] );
              }  
              return implode( ' ', $use_words );
            } else {
              return implode( ' ', $use_words );
            } 
          }
          $it->next();
        }
      }
      return $text;
    }
    
    /**
    * Strip symbols like trademark etc.
    * 
    * @param mixed $string - string to strip
    * @return mixed
    */
    private function stripSymbols( $string ) {
      $symbols = array( '&reg;', '&copy;', '&deg;', '&trade;' );
      $symbols = array_map( array( $this, 'htmlDecodeSymbols' ), $symbols );
      return str_replace( array_merge( $symbols, array( '&nbsp;' ) ), '', $string );
    }
    
    private function htmlDecodeSymbols( $item ) {
      return KissMT::init()->entities( $item, $decode = true );
    }
    
    /**
    * Strip stopwords from the keywords
    * 
    * @param string $word - passed by reference into array_walk
    */
    private function stripStopWords( &$word ) {
      static $already_used = array();

      if( empty( KissMT::init()->stopwords ) ) {
       return;
      }
      $word = mb_strtolower( trim( $word ), "UTF-8" );
      if ( is_null( $word ) || is_numeric( $word )
                                  || ( $word == '-' )
                                  || in_array( $word, $already_used ) ) {
        $word = '[--replaced--]';
        return;
      }
      $it = new ArrayIterator( KissMT::init()->stopwords );
      while ( $it->valid() ) {
        if ( ( $it->current() == $word ) ) {
          $word = '[--replaced--]';
          return;
        }
        $it->next(); 
      }
      $already_used[] = $word;
    } // end method
    
    /**
    * Ensure the array is free of duplicate values
    * 
    * @param array $array
    * @return string
    */
    protected function removeArrayDuplicates( $array ) {
      $no_duplicates = array();
      $it = new ArrayIterator( $array );
      while ( $it->valid() ) {
        $current = $it->current();
        if ( !in_array( mb_strtolower( $current, "UTF-8" ), $no_duplicates ) && !empty( $current ) ) {
          $no_duplicates[] = mb_strtolower( $it->current(), "UTF-8" );
        }
        $it->next(); 
      }
      return $no_duplicates;
    } // end method
    
    /**
    * Force root page to www.mysite.com/ (standard) if $root_index = 'root'
    * Force root page to www.mysite.com/index.php if $root_index = 'index'
    * 
    * @param string $the_current_canonical_link - full canonical link
    * @return string - modded full canonical link
    */
    private function rootURL( $the_current_canonical_link ) {
      if ( ( substr( $the_current_canonical_link, -strlen( FILENAME_DEFAULT ), strlen( $the_current_canonical_link ) ) == FILENAME_DEFAULT ) && ( strtolower( $this->root_index ) == 'root' ) ) {
        $the_current_canonical_link = substr( $the_current_canonical_link, 0, strlen( $the_current_canonical_link )-strlen( FILENAME_DEFAULT ) );
      }
      return $the_current_canonical_link;
    }
    
    protected function checkCanonical( $get_key = false, $additional = false ) {
      global $request_type;
      if( isset( $this->noindex_follow ) && is_array( $this->noindex_follow ) ) {
        $it = new ArrayIterator( $this->noindex_follow );
        while ( $it->valid() ) {
          if( array_key_exists( $it->current(), $_GET ) ) {
            return 'noindex';
          }
          $it->next();
        }
      }

    if ( false === $get_key ) {
        $link = osc_href_link( KissMT::init()->retrieve( 'basename' ), '', $request_type, false,null,true ) ;
     }
     else 
     	$link = osc_href_link( KissMT::init()->retrieve( 'basename' ), $get_key . $this->original_get . $additional, $request_type, false,null,true );
      
    if(strpos( $link, "?" ) !== false)
		$link = substr( $link, 0 , strpos( $link, "?" ));
		
   /*if(strpos( $link, "/filter" ) !== false)
		$link = substr( $link, 0 , strpos( $link, "/filter" ));
		
    if(strpos( $link, "/sort" ) !== false)
		$link = substr( $link, 0 , strpos( $link, "/sort" ));
      
*/
	/*if(!apc_fetch("canonical: " . $link))
		apc_add("canonical: " . $link . " FROM " . $_SERVER["REQUEST_URI"], "", 21600);	*/
		
      return $this->rootURL( $link );

    }
    
     protected function retrieve( $cachename ) {

      $this->cache = $cachename;
      //$this->cache = KissMT::init()->retrieve( 'cache_path' ) . $cachename;
      if( apc_fetch( $this->cache ) && ( KISSMT_CACHE_ON == 'true')  ) {
      	
        //KissMT::init()->cachefile_size = number_format( filesize( $this->cache ) / 1024, 2 ) . ' kb';
        $time = microtime( true );
        $serialized = gzinflate( apc_fetch( $this->cache ) );
        $this->cache_md5 = md5( $serialized ); 
        $meta = unserialize( $serialized );
        KissMT::init()->performance['time'] += ( microtime( true ) - $time );
        $this->cache_retrieved = true;
        KissMT::init()->title = KissMT::init()->entities( $meta['title'], $decode = false );
        KissMT::init()->keywords = KissMT::init()->entities( $meta['keywords'], $decode = false ); 
        KissMT::init()->description = KissMT::init()->entities( $meta['description'], $decode = false );
        KissMT::init()->page_title = KissMT::init()->entities(  $meta['page_title'], $decode = false ); 
        return true; 
      }
      return false;
    } // end method
    
    
    
/*    КЭШ на файлах
    protected function retrieve( $cachename ) {
      $this->cache = KissMT::init()->retrieve( 'cache_path' ) . $cachename;
      if( is_readable( $this->cache ) && ( KISSMT_CACHE_ON == 'true')  ) {
        KissMT::init()->cachefile_size = number_format( filesize( $this->cache ) / 1024, 2 ) . ' kb';
        $time = microtime( true );
        $serialized = gzinflate( file_get_contents( $this->cache ) );
        $this->cache_md5 = md5( $serialized ); 
        $meta = unserialize( $serialized );
        KissMT::init()->performance['time'] += ( microtime( true ) - $time );
        $this->cache_retrieved = true;
        KissMT::init()->title = KissMT::init()->entities( $meta['title'], $decode = false );
        KissMT::init()->keywords = KissMT::init()->entities( $meta['keywords'], $decode = false ); 
        KissMT::init()->description = KissMT::init()->entities( $meta['description'], $decode = false );
        KissMT::init()->page_title = KissMT::init()->entities(  $meta['page_title'], $decode = false ); 
        return true; 
      }
      return false;
    } // end method
 */  

    protected function store() {

      if ( KISSMT_CACHE_ON == 'true' ) {
        $meta = array( 'title' => KissMT::init()->entities( KissMT::init()->title, $decode = true ),
                       'keywords' => KissMT::init()->entities( KissMT::init()->keywords, $decode = true ),
                       'description' => KissMT::init()->entities( KissMT::init()->description, $decode = true ),
                       'page_title' => KissMT::init()->entities( KissMT::init()->page_title, $decode = true ) );
        $serialized = serialize( $meta );
        if ( $this->cache_md5 === md5( $serialized ) ) {
          return;
        }
        $save = gzdeflate( $serialized, 1 );
       /* if( (substr( $this->cache, -6, 6 ) == '.cache' ) && ( $this->cache_retrieved == false )
                                                         && ( false !== file_put_contents( $this->cache, $save, LOCK_EX ) ) ) { */
                                                         	
		if( (substr( $this->cache, -6, 6 ) == '.cache' ) && (   apc_add( $this->cache, $save ) ) ) {                                                          	
          return true; 
        }
      }
      return false;
    } // end method
    
    public function gc() {
      if ( KISSMT_CACHE_RESET !== 'reset' ) {
        return false;
      }
      foreach ( glob( KissMT::init()->retrieve( 'cache_path' ) . "*.cache" ) as $filename ) {
        @unlink( $filename );
      }
      $query = "SELECT configuration_id FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'KISSMT_CACHE_RESET'";
	  $result = $osC_Database->query(':sqlraw');
	  $result->bindRaw(':sqlraw',$query);
	  $result->execute();

      $db_installed = $result->next();
      $result->freeResult();
      if ( false !== $db_installed ) {
        $query = "UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = 'false' WHERE configuration_key = 'KISSMT_CACHE_RESET'";
	    $result = $osC_Database->query(':sqlraw');
	    $result->bindRaw(':sqlraw',$query);
	    $result->execute();
 
      }
    } // end method
 
  } // End class
?>
