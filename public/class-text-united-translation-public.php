<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.textunited.com/
 * @since      1.0.0
 *
 * @package    Text_United_Translation
 * @subpackage Text_United_Translation/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Text_United_Translation
 * @subpackage Text_United_Translation/public
 * @author     Text United <servus@textunited.com>
 */
class Text_United_Translation_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->login = get_option("TUcheckAuth");

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Text_United_Translation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Text_United_Translation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/text-united-translation-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( "flags-ico",plugin_dir_url( __FILE__ ) . '/css/flag-icon.css', array(), $this->version, 'all'  );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Text_United_Translation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Text_United_Translation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/text-united-translation-public.js', array( 'jquery' ), $this->version, false );

	}
	
	public function TUT_display_info() {
	
	     $language = get_locale();
		
	    if($this->login!=200)
	   	return false;
	
	 
		  if(get_option( 'TUdefaultLanguage' )==$language || is_404()){
		  //return false;
		  }
  
	    
		$langT2 = strtolower($language);
		$type = get_option( 'TUlanguageSelectorType' );
		$drop ='';
		$TUshape = get_option( 'TUflagShape' );
		$class='';
		if(!empty($TUshape) && get_option('TUlanguageSelectorContent') == 1){
		$class= "min-width:initial;";
		}
		$current_category = get_queried_object();
		$languagesPost =[];
		if(!empty($current_category->ID) && !is_author()){
		$link = str_replace(get_site_url().'/','', get_the_permalink($current_category->ID));
		$link2 = str_replace(''.$langT2.'/','', $link);
		$post = get_post_meta( $current_category->ID, 'tu_language_field_post',true);
		$slug = $link2;
		}elseif(is_author()){	 
		$author_id = get_query_var('author'); 
		$slug = 'author/'.get_the_author_meta( 'nicename', $author_id );
		}
		else{
		$slug = '';
		if(!empty($current_category)){
		$slug = str_replace(get_site_url().'/','', get_term_link($current_category->term_id,$current_category->taxonomy));
        $slug =  str_replace(''.$langT2.'/','', $slug);
		}
		}
	    $languages =[];
		$languuagelist = get_option( 'TUlanguagesAdd' );
		if(empty($post)){
		 foreach($languuagelist as $k=>$langugeCode){
	        array_push($languages, strtolower($langugeCode['LangCode']));
		 }
		}
		else{
		foreach($post as $k=>$langugeCode){
	        array_push($languages, strtolower($langugeCode));
		 }
		 }
	    $langT = get_option( 'TUdefaultLanguage' );
		
		$drop ='';
		
		if(empty($slug) && !is_front_page()){ 
		return false;
		}
		
		$site = get_site_url();
	    if(($type == 2 or $type == 3) and !empty(get_option( 'TUshortcode' ))){

       if(count($languagesPost)==1 && $languagesPost[0]==strtolower($langT))
		{
		
		}else{
		if($type==2){
		$style = 'right:0px;left:initial;';
		$aling = 'text-align:right;display:block;';
		}
		else{
		$style = 'left:0px;right:initial;';
		$aling = 'text-align:left;display:block;';
		}
		
		
		$drop .= '<div id="languageWidget" style="position:initial;max-width:180px"><div style="position:relative" class="dropdowns">
		<div id="TUT_myDropdown" class="dropdown-content " style="'.$style.'">';
		foreach($languages as $k=>$langugeCode){
		
			if($slug=='/')
			$slug = '';
			$list = '<a href="'.esc_url($site.'/'. $langugeCode.'/'.$slug).'">'.$this->TUT_selectorContent( $langugeCode ).'</a>';
			if(strtolower($language)==strtolower($langT) && strtolower($langugeCode) == strtolower($langT))
			$list = '<a href="'.esc_url($site.'/'.$slug).'">'.$this->TUT_selectorContent( $langugeCode ).'</a>';
			$drop .=$list;
			
		}
		  $drop .= '</div>
		<a style="'.$aling.'" style="display:block;"><button class="dropbtn" onclick="TUT_myFunction()"></button></a>
	  </div> </div>';
	

	
	  }
	  }elseif(($type == 4 or $type == 5 or $type == 6 or $type == 7 or $type == 1) and !empty(get_option( 'TUshortcode' ))){
	    if($type==4){
		$style = 'float:right;';
		$aling = 'text-align:right;display:block;';
		}
		else{
		$style = '';
		$aling = 'text-align:left;display:block;';
		}
		$top = '';
		if($type==6 or $type==7){
		$top = 'top:40px !important;bottom:initial !important;';
		}
	  
	$drop .= '  <div class="Tudropdown" style="padding-right:20px;'.$style.' '.$class.'">
  <div id="Tudropdown-menu" class="Tudropdown-menu" style="'.$class.' '.$top.'">';
  
  		foreach($languages as $k=>$langugeCode){
			
			if($slug=='/')
			$slug = '';
		    $list = '<a class="Tudropdown-item" href="'.esc_url($site.'/'. $langugeCode.'/'.$slug).'">'.$this->TUT_selectorContent( $langugeCode ).'</a>';
			if(strtolower($language)==strtolower($langT) && strtolower($langugeCode) == strtolower($langT))
			$list = '<a class="Tudropdown-item" href="'.esc_url($site.'/'.$slug).'">'.$this->TUT_selectorContent( $langugeCode ).'</a>';
			$drop .=$list;
			
		}

$drop .= '</div>
  <span  class="TUarrow" onclick="TUT_tuDropdown()">
  '.$this->TUT_selectorContent($langT2).' <div class="arrow"><div class="arrow-top"></div><div class="arrow-bottom"></div></div>
  </span>
</div>';
	  
	  }
	  if(count($languages)==1 && $languages[0]==strtolower(get_option( 'TUdefaultLanguage' ))){return '';}
	  else
	  return $drop;
	}
  

	function TUT_pre_page_rules(){
		global $wp_query;
		global $wp;
		 
		if($this->login!=200 || is_admin())
	   	return false;
 
		if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
		$selectedLanguage = get_post_meta(get_option( 'page_for_posts' ), 'tu_language_field_post',true );
		}else{
		$selectedLanguage = get_post_meta(get_the_ID(), 'tu_language_field_post',true );
		}
		$defaultLanguage = get_option( 'TUdefaultLanguage');
		 

		$language = get_locale();
      
		$iscategory = (bool) $wp_query->is_category;

		if(!is_front_page() && ! is_search(  ) && !is_author(get_the_author_meta('ID') )){
		if(!empty($selectedLanguage) && !empty($defaultLanguage)){

	    if(!in_array($language ,$selectedLanguage) && $defaultLanguage!=$language){
             $site =  str_replace( strtolower($language.'/'), '', home_url($wp->request));
             exit( wp_redirect($site ) ); 
		}
		
	    }elseif(empty($selectedLanguage)){
			//if(@!in_array($language ,$selectedLanguage) ){
				//$wp_query->set_404();
				//status_header( 404 );
				//  nocache_headers();
			 // include( get_query_template( '404' ) );
			 // die();
			//}
		}
	}
	}
 

	function TUT_custom_page_rules() {
		global $wp_rewrite;
		global $wp_query;
		global $wpseo_front;
        

		 if($this->login!=200)
		 return false;
		 
		if(empty(get_option( 'TUlanguagesAdd' )) && empty(get_option( 'TUdefaultLanguage' )) && empty(get_option( 'TUprojectInfo' )))
		return false;
		
		$wp_rewrite->flush_rules(false);
		$languuagelist = get_option( 'TUlanguagesAdd' );

		$languages =[];
		
		 foreach($languuagelist as $k=>$langugeCode){
	        array_push($languages, strtolower($langugeCode['LangCode']));
		}
		
		if ( !is_admin() ) {
		$site = get_site_url();
			
			if(!empty(get_option( 'TUprojectInfo' ))){
				$project_id = get_option( 'TUprojectInfo');
			    if(!empty($project_id->url))	
			    $site = $project_id->url;
			}
		
		$result = parse_url($site);
		$weburl = str_replace($site ,"",$result['scheme']."://".$result['host'].parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
       
		$segment = explode('/', trim($weburl, '/'));
		 $default = get_option( 'TUdefaultLanguage' );
		if (in_array(strtolower($segment[0]), $languages) && strtolower($default)!=strtolower($segment[0])) {
		update_option( 'home', ''.esc_url($site.'/'.strtolower($segment[0])).'/',false);
		}else{
		update_option( 'home', esc_url($site),false);
		}

		 

	}

	}

	function TUT_selectorContent($par){

		$languuagelist = get_option( 'TUlanguagesAdd' );
		$TUshape = get_option( 'TUflagShape' );
		$class='';
		if(!empty($TUshape)){
		$class= "flag-icon-squared";
		}
		$ex = '';
		$selector= get_option( 'TUlanguageSelectorContent' );
		foreach($languuagelist as $k=>$langugeCode){
			$langPart = explode('-',  $langugeCode['LangCode']);
			$langArray = strtolower($langugeCode['LangCode']);
			
				 $specialFlags = array('NL-BE','EN-CA','FR-BE','FR-CA','GL-GL','GN-GN','KN-KN','KI-KI','LA-LA','MS-MS','ML-ML','MR-MR','MX-MX','MO-MO','NE-NE','OM-OM','PS-PS','PA-PA','SA-SA','ST-ST','SR-SR','SD-SD','SL-SL','ES-AR','ES-DO','SV-SV','TT-TT','BO-BO','TK-TK','VI-VI');

			
			
			if($par==$langArray)
			{
				 if(!in_array($langugeCode['LangCode'],$specialFlags)) 
                 $langArray = strtolower($langPart[1]);
				 else
				 $langArray = strtolower($langugeCode['LangCode']);
				
				if($selector==1)
				$ex = '<span class="flag-icon flag-icon-'.strtolower($langArray).' '.$class.'"></span>';
				elseif($selector==2)
				$ex = $langugeCode['SystemName'];
				else
				$ex = '<span class="Tushape"><span class="flag-icon flag-icon-'.strtolower($langArray).' '.$class.'"></span></span> '.esc_html($langugeCode['SystemName']).'';
			}
		}
        return $ex;
	}

	function TUT_restructure_menu_links( $items, $args ) {
	    global $wp_query;
	    if($this->login!=200)
        return $items;
	
	
		$type = get_option( 'TUlanguageSelectorType' );
		if($type >= 2 or !empty(get_option( 'TUshortcode' )))
		return $items;
	
		if(!empty($args->menu->name) && ($args->menu->name != 'Primary' && $args->menu->name != 'PrimaryMobile')){
		     return $items;
		}
		
		$language = get_locale();
		
		$current_category = get_queried_object();
		$languagesPost =[];
		if(!empty($current_category->ID) && !is_author()){
		$link = str_replace(get_site_url().'/','', get_the_permalink($current_category->ID));
		$link2 = str_replace(''.strtolower($language).'/','', $link);
		$post = get_post_meta( $current_category->ID, 'tu_language_field_post',true);
		$slug = $link2;
		}elseif(is_author()){	 
		$author_id = get_query_var('author'); 
		$slug = 'author/'.get_the_author_meta( 'nicename', $author_id );
		}
		else{
		$slug = '';
		if(!empty($current_category)){
		$slug = str_replace(get_site_url().'/','', get_term_link($current_category->term_id,$current_category->taxonomy));
        $slug =  str_replace(''.strtolower($language).'/','', $slug);
		}
		}
		
		if(!empty($post)){
		foreach($post as $s=>$postsc){
	        array_push($languagesPost, strtolower($postsc));
		}
		}
	    $langT =strtolower(get_option( 'TUdefaultLanguage' ));
		
		if(count($languagesPost)==1 && $languagesPost[0]==$langT)
		return $items;
	
	    if(empty($slug) && !is_front_page()){ 
	      return $items;
		}

		$new_links = array();
		$site = get_site_url();
	
	
	
		$languages =[];
		
		
		$languuagelist = get_option( 'TUlanguagesAdd' );
		
		if(empty($languuagelist))
		return $items;
		
		$languages =[];
		if(empty($post)){
		 foreach($languuagelist as $k=>$langugeCode){
	        array_push($languages, strtolower($langugeCode['LangCode']));
		 }
		}
		else{
		foreach($post as $k=>$langugeCode){
	        array_push($languages, strtolower($langugeCode));
		 }
		 }
		$site = get_site_url();
		$result = parse_url($site);
		$weburl = str_replace($site ,"",$result['scheme']."://".$result['host'].parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
       
		$segment = explode('/', trim($weburl, '/'));
		// Create a nav_menu_item object
		if (in_array(strtolower($segment[0]), $languages)) {
		$label = $this->TUT_selectorContent(strtolower($segment[0]));
		$item = array(
			'title'            => $label,
			'menu_item_parent' => 0,
			'ID'               => 'tu1',
			'db_id'            => 'tu1',
			'url'              => '#',
			'xfn'              => null,
			'target'           => null,
			'current'          => null,
			'classes'          => array( 'menu-item','menu-item-has-children' )
		);
	    }else{
		
		
			$label = $this->TUT_selectorContent($langT);
			$item = array(
				'title'            => $label,
				'menu_item_parent' => 0,
				'ID'               => 'tu1',
				'db_id'            => 'tu1',
				'url'              => '#',
			    'xfn'              => null,
			    'target'           => null,
				'current'          => null,
				'classes'          => array( 'menu-item','menu-item-has-children' )
			);

		}


	
		$items[] = (object) $item; // Add the new menu item to our array
	    foreach($languages as $lang){
		$label2 = $this->TUT_selectorContent($lang);
		
		$url = ''.esc_url($site.'/'.$lang.'/'.$slug);
		if(strtolower($language)==$langT && $lang == $langT)
		$url = ''.esc_url($site.'/'.$slug);

		$item = array (
			'title'            => $label2,
			'menu_item_parent' => 'tu1',
			'ID'               => '',
			'db_id'            => '',
			'xfn'              => null,
			'target'           => null,
			'current'          => null,
			'url'              =>  $url,
			'classes'          => array( 'menu-item ','menu-item-type-post_type', 'menu-item-object-page' )
		);
		$items[] = (object) $item ;
		
		
		}

	
		return $items;
	}
	
	
		function TUT_add_hreflans() {
	 global $wp_query;
	
	 
	    if($this->login==200){
      
	  
	  $language = get_locale();
	 
 
		
		$project_id = get_option( 'TUprojectInfo');
  
	 $langT2 = strtolower($language);

		$type = get_option( 'TUlanguageSelectorType' );
	
		$current_category = get_queried_object();
	
		$languagesPost =[];
		if(!empty($current_category->ID) && !is_author()){
		$link = str_replace(get_site_url().'/','', get_the_permalink($current_category->ID));
		$link2 = str_replace(''.$langT2.'/','', $link);
		$post = get_post_meta( $current_category->ID, 'tu_language_field_post',true);
		$slug = $link2;
		}elseif(is_author()){
		$author_id = get_query_var('author'); 
		$slug = 'author/'.get_the_author_meta( 'nicename', $author_id );
		}
	    else{
		$slug = '';
		if(!empty($current_category)){
		$slug = str_replace(get_site_url().'/','', get_term_link($current_category->term_id,$current_category->taxonomy));
        $slug =  str_replace(''.$langT2.'/','', $slug);
		}
		}
		$languuagelist = get_option( 'TUlanguagesAdd' );
		
		$languages =[];
		if(empty($post)){
		 foreach($languuagelist as $k=>$langugeCode){
			$langArray = strtolower($langugeCode['LangCode']);
			
	        array_push($languages, $langArray);
		 }
		}
		else{
		foreach($post as $k=>$langugeCode){
			$langArray = strtolower($langugeCode);
		
	        array_push($languages, $langArray);
		 }
		 }
		 
		  if(empty($slug) && !is_front_page()){ 
		  
		  }
		 
		 
		$langT = strtolower(get_option( 'TUdefaultLanguage' ));
		
 
	    $list ='';
		$drop = '';
		$site = get_site_url();
		foreach($languages as $k=>$langugeCode){

			if($slug=='/')
			$slug = '';
		 
			$list = '<link rel="alternate" href="'.esc_url($site.'/'.$langugeCode.'/'.$slug).'"  hreflang="'.$langugeCode.'" />';
			if(strtolower($langugeCode)==$langT )
			$list = '<link rel="alternate" href="'.esc_url($site.'/'.$slug).'" hreflang="x-default" />';
			$drop .=$list;
			
			
		}
		
	      if(empty($slug) && !is_front_page()){ 
		  $drop = '';
		  }
		
	?> 
	
	    <?php echo $drop ?>
    
	<?php
           
	}
   
	
	}

	function TUT_start_modify_html( ) {
	   
		ob_start('Text_United_Translation_Public::TUT_end_modify_html');
		
	 }
	function TUT_foo_buffer_stop(){
    ob_end_flush();
}

 
      function TUT_titleSeo($title){
	
		global $wp_query;
	       if($this->login!=200)
           return false;	
 
 	
       $default =  get_option( 'TUdefaultLanguage' ) ;	
 	   $language = get_locale();
	   
	   if($default==$language)
	   return $title;
		
		$wp_request_headers = array(
		  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
		  'Content-Type'   => 'application/json',
		  'x-textunited-addin' => 'wordpress'
		);
		
		$project_id = get_option( 'TUprojectInfo');
 
	 
	if(!is_front_page()){
		$the_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
		
		if(!empty($the_page->ID))
		$url = get_post_meta($the_page->ID, 'tu_language_field_post_url',true );
		elseif(!empty($the_page->term_id))
		$url = get_term_meta($the_page->term_id, 'tu_language_field_term_url',true );
		else
		return $title;
		// var_dump( $url);
		$body = array(
		"webTranslationId" => (int)$project_id->id,
        "url" => $url,
		"languageCode" => $language,
        );
		}else{
		 $body = array(
		"webTranslationId" => (int)$project_id->id,
        "url" => '/',
		"languageCode" => $language,
        );
		}

      $wp_add_project = wp_remote_request(
      'https://api.textunited.com/ProjectAutomation/api/WebTranslationSegment',
      array(
          'method'    => 'POST',
          'headers'   => $wp_request_headers,
          'body' =>  json_encode($body),
		  'timeout'     => 30
      )
     );
	 
   $the_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
    $project = wp_remote_retrieve_body( $wp_add_project );
    $project = json_decode( $project );

	
	 if(!empty($project_id->id) && !empty($project)){
 
	  foreach($project as $s=>$translation){
	  
	  		if($translation->htmlId=="title"){
			        
                   $title = $translation->targetText;
				   return $title;
	        }
	}
	
	}else{
	
	
	}
	
	
	return $title;
	}
    
    
	 
	 function TUT_end_modify_html($buffer) {
	 global $wp_query;
	 $html = $buffer;
	 
	    if($this->login!=200){
        return $html;
       }
	  
	  $language = get_locale();
	 
	  $default = strtolower(get_option( 'TUdefaultLanguage' ));	
		$wp_request_headers = array(
		  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
		  'Content-Type'   => 'application/json',
		  'x-textunited-addin' => 'wordpress'
		);
		
		$project_id = get_option( 'TUprojectInfo');
  
	 $langT2 = strtolower($language);

		$type = get_option( 'TUlanguageSelectorType' );
	
		$current_category = get_queried_object();
	
		$languagesPost =[];
		if(!empty($current_category->ID) && !is_author()){
		$link = str_replace(get_site_url().'/','', get_the_permalink($current_category->ID));
		$link2 = str_replace(''.$langT2.'/','', $link);
		$post = get_post_meta( $current_category->ID, 'tu_language_field_post',true);
		$slug = $link2;
		}elseif(is_author()){
		$author_id = get_query_var('author'); 
		$slug = 'author/'.get_the_author_meta( 'nicename', $author_id );
		}
	    else{
		$slug = '';
		if(!empty($current_category)){
		$slug = str_replace(get_site_url().'/','', get_term_link($current_category->term_id,$current_category->taxonomy));
        $slug =  str_replace(''.$langT2.'/','', $slug);
		//if($current_category->taxonomy!='post_tag')
		//$slug = $current_category->taxonomy.get_term_link($current_category->term_id,$current_category->taxonomy).'/'.$current_category->slug;
		//else
		//$slug = 'tag/'.$current_category->slug;
		}
		}
		$languuagelist = get_option( 'TUlanguagesAdd' );
		
		$languages =[];
		if(empty($post)){
		 foreach($languuagelist as $k=>$langugeCode){
			$langArray = strtolower($langugeCode['LangCode']);
			
	        array_push($languages, $langArray);
		 }
		}
		else{
		foreach($post as $k=>$langugeCode){
			$langArray = strtolower($langugeCode);
		
	        array_push($languages, $langArray);
		 }
		 }
		   if(empty($slug) && !is_front_page()){ 
			  return $html;
		   }
		 
		
	    $langT = strtolower(get_option( 'TUdefaultLanguage' ));
		
		$TUshape = get_option( 'TUflagShape' );
		$class='';
		if(!empty($TUshape) && get_option('TUlanguageSelectorContent') == 1){
		$class= "min-width:initial";
		}
		
		 if(($type == 2 or $type == 3) and empty(get_option( 'TUshortcode' ))){
		
		if($type==2){
		$style = '';
		$aling = '';
		}
		else{
		$style = 'left:10px;right:auto';
		$aling = 'text-align:left;display:block';
		}
		
		$drop = '<div class="dropdowns">
		<div id="TUT_myDropdown" class="dropdown-content text-left" style="'.$style.'">';
		$languuagelist = get_option( 'TUlanguagesAdd' );
	    
		$site = get_site_url();
		foreach($languages as $k=>$langugeCode){

			if($slug=='/')
			$slug = '';
			
			$list = '<a href="'.esc_url($site.'/'. $langugeCode.'/'.$slug).'">'.$this->TUT_selectorContent( $langugeCode ).'</a>';
			if(strtolower($language)==$langT && $langugeCode == $langT)
			$list = '<a href="'.esc_url($site.'/'.$slug).'">'.$this->TUT_selectorContent( $langugeCode ).'</a>';
			$drop .=$list;
			
			
		}
		  $drop .= '</div>
		<a style="'.$aling.'"><span class="dropbtn" style="background-color:#e9553b;display:block;padding:5px" onclick="TUT_myFunction()"></span></a>
	  </div> ';
	  if(count($languages)==1 && $languages[0]==$default){}
	  else
	  $html .= '<div id="languageWidget" style="'.$style.'">'.$drop.'</div>';
	  
	
	  
	}elseif(($type >= 4) and empty(get_option( 'TUshortcode' ))){
	    if($type==5){
		$style = 'float:left;position:fixed;bottom:0;left:30px;z-index:9999;';
		$aling = 'text-align:right;display:block';
		}
		else{
		$style = 'float:right;position:fixed;bottom:0;right:30px;z-index:9999;';
		$aling = 'text-align:left;display:block;';
		}
		
		$top = '';
		if($type==6 or $type==7){
		$top = 'top:40px !important;bottom:initial !important;';
		if($type==7)
		$style = 'float:right;position:fixed;top:0;left:30px;z-index:9999;';
		if($type==6)
		$style = 'float:right;position:fixed;top:0;right:30px;z-index:9999;';
		}
	  
	$drop  = '<aside style="'.$style.'"><div class="Tudropdown" style="padding-right:20px;'.$class.'">
  <div id="Tudropdown-menu" class="Tudropdown-menu"  style="'.$class.' '.$top.'">';
		$site = get_site_url();
  		foreach($languages as $k=>$langugeCode){

			if($slug=='/')
			$slug = '';

			$list = '<a  class="Tudropdown-item" href="'.esc_url($site.'/'. $langugeCode.'/'.$slug).'">'.$this->TUT_selectorContent( $langugeCode ).'</a>';
			if(strtolower($language)==$langT && $langugeCode == $langT)
			$list = '<a  class="Tudropdown-item" href="'.esc_url($site.'/'.$slug).'">'.$this->TUT_selectorContent( $langugeCode ).'</a>';
			$drop .=$list;

			
		}

      $drop .= '</div><span class="TUarrow" onclick="TUT_tuDropdown()">'.$this->TUT_selectorContent($langT2).' <div class="arrow"><div class="arrow-top"></div><div class="arrow-bottom"></div></div></span></div></aside>';
     
   	  if(count($languages)==1 && $languages[0]==$default){}
	  else
	  $html .= $drop;
	  }
	  
	  if($default==strtolower($language) || is_404()){
	  return $html;
	  }
	 
	 $defaultLanguage = strtolower(get_option( 'TUdefaultLanguage'));

	 $content = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
	 $doc = new DOMDocument();
	 libxml_use_internal_errors(true);
	 @$doc->loadHTML($content );
	 libxml_clear_errors();
	 $doc->preserveWhiteSpace = false;
  
	 $doc->removeChild($doc->doctype);
 
				if($defaultLanguage!=$language){
		        $element3 = $doc->getElementsByTagName("img");
			    foreach ($element3 as $elements)
				{
				$elements->setAttribute('src',str_replace(''.$language.'/', '',$elements->getAttribute('src')));
				}
				}

	  
	    if(!is_user_logged_in()){
		
		$checkHash = Text_United_Translation_Public::TUT_get_cache();
		
		if((!empty($checkHash) && !empty($checkHash['hash']) && $checkHash['hash'] !=md5($html)) || empty($checkHash['hash'])){
		$body =  Text_United_Translation_Public::TUT_make_body_for_api($html);
		}else{
        $check = Text_United_Translation_Public::TUT_check_cache();
		
	    if(!empty($check) && $check->cacheUpdateRequired == true)
		$body =  Text_United_Translation_Public::TUT_make_body_for_api($html);
		elseif(empty($check))
		$body =  Text_United_Translation_Public::TUT_make_body_for_api($html);
		else
		$body =  Text_United_Translation_Public::TUT_get_cache();
        }
		
		}else{
		$body =  Text_United_Translation_Public::TUT_make_body_for_api($html);
        }
		

	 if(!empty($project_id->id) && !empty($body['body'])){	
	  
	    
	  $doc->getElementsByTagName('body')->item(0)->nodeValue = htmlspecialchars(base64_decode($body['body']));
 
	 
	  $render = $doc->saveHTML();
	  $patterns = array("/<title>(.*?)<\/title>/","/description\" content=\"(.*?)\"/","/description\":\"(.*?)\"/","/:title\" content=\"(.*?)\"/");
      $replacements = array('<title>'.$body['title'].'</title>','description" content="'.$body['description'].'"','description":"'.$body['description'].'"',':title" content="'.$body['title'].'"');
      $output = preg_replace($patterns, $replacements, $render);  
	  $outputNew = str_replace( $default.'/', "", $output );	  
	  return html_entity_decode(str_replace(['<html><body>', '</body></html>','<html><head>'], '',$outputNew));
	 }else{
	    return $html;
	 }
	
	 }	


   	 	function TUT_get_cache()
	  {
	    $language = get_locale();
	    $project_id = get_option( 'TUprojectInfo');
		
		
	  	if(!is_front_page() && !is_author() && !is_search() && !is_404()){
		$the_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
		//var_dump($the_page);
		if(!empty($the_page->ID)){
		$cache = get_transient( 'cache_'.$language.'_'.$the_page->post_type.'_'.$the_page->ID.'');
		}
		else{
		//var_dump($the_page);
		$cache = get_transient( 'cache_'.$language.'_'.$the_page->taxonomy.'_'.$the_page->term_id.'');
		}

		}elseif(is_author()){
		$author_id = get_post( $the_page->ID )->post_author; 
		$cache = get_transient( 'cache_'.$language.'_author_'.$author_id.'');
		}else{
		//var_dump($the_page);
		$cache = get_transient( 'cache_'.$language.'_home');
		}
			
	    return $cache;
	}


		   function TUT_check_cache()
	   {
	   global $wp;
		$wp_request_headers = array(
		  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
		  'Content-Type'   => 'application/json',
		  'x-textunited-addin' => 'wordpress'
		);
	    $language = get_locale();
	    $project_id = get_option( 'TUprojectInfo');
	  	if(!is_front_page() && !is_author() && !is_search() && !is_404()){
		$the_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
		if(!empty($the_page->ID)){
		$url = get_post_meta($the_page->ID, 'tu_language_field_post_url',true );
		$cache = get_transient( 'cache_'.$language.'_'.$the_page->post_type.'_'.$the_page->ID.'');
		}
		else{
		$url = get_term_meta($the_page->term_id, 'tu_language_field_term_url',true );
		$cache = get_transient( 'cache_'.$language.'_'.$the_page->taxonomy.'_'.$the_page->term_id.'');
		}
		
		$body = array(
		"webTranslationId" => (int)$project_id->id,
        "url" => str_replace( home_url(), "", home_url(add_query_arg(array(), $wp->request)).'/' ),
		"languageCode" => $language,
		"CacheDate" => $cache['datetime']
        );
		}elseif(is_author()){
		$author_id = get_post( $the_page->ID )->post_author; 
		$url =	get_user_meta( $author_id, 'tu_language_field_author_url',true);
		$cache = get_transient( 'cache_'.$language.'_author_'.$author_id.'');
		$body = array(
		"webTranslationId" => (int)$project_id->id,
        "url" => str_replace( home_url(), "", get_author_posts_url($author_id).'/' ),
		"languageCode" => $language,
		"CacheDate" => $cache['datetime']
        );
		}else{
		$cache = get_transient( 'cache_'.$language.'_home');
		$body = array(
		"webTranslationId" => (int)$project_id->id,
        "url" => '/',
		"languageCode" => $language,
		"CacheDate" => $cache['datetime']
        );
		}	
		
		   $wp_checkCache = wp_remote_request(
		    'https://api.textunited.com/ProjectAutomation/api/WebTranslationPageCache',
			 array( 'method'    => 'POST','headers'   => $wp_request_headers,'timeout' => 30,'body' =>  json_encode($body)));
            $checkCache = wp_remote_retrieve_body( $wp_checkCache );
            $checkCache = json_decode( $checkCache );  
			
			
		
			
			
	        return $checkCache;
	    }
	 


	function TUT_make_body_for_api($html)
	{
	
	    date_default_timezone_set('UTC');
		$wp_request_headers = array(
		  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
		  'Content-Type'   => 'application/json',
		  'x-textunited-addin' => 'wordpress'
		);
	    $language = get_locale();
	    $project_id = get_option( 'TUprojectInfo');
	  	if(!is_front_page() && !is_author() && !is_search() && !is_404()){
		$the_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
		if(!empty($the_page->ID)){
		$page = 'cache_'.$language.'_'.$the_page->post_type.'_'.$the_page->ID;
		$url = get_post_meta($the_page->ID, 'tu_language_field_post_url',true );
		}
		else{
		$url = get_term_meta($the_page->term_id, 'tu_language_field_term_url',true );
		$page = 'cache_'.$language.'_'.$the_page->taxonomy.'_'.$the_page->term_id;
		}
		
		$body = array(
		"webTranslationId" => (int)$project_id->id,
        "url" => $url,
		"languageCode" => $language,
		"html" => base64_encode($html)
        );
		}elseif(is_author()){
		$author_id = get_the_author_meta('ID'); 
		$url =	get_user_meta( $author_id, 'tu_language_field_author_url',true);
		$page = 'cache_'.$language.'_author_'.$author_id;
		$body = array(
		"webTranslationId" => (int)$project_id->id,
        "url" => $url,
		"languageCode" => $language,
		"html" => base64_encode($html)
        );
		}else{
		 $page = 'cache_'.$language.'_home';
		 $body = array(
		"webTranslationId" => (int)$project_id->id,
        "url" => '/',
		"languageCode" => $language,
		"html" => base64_encode($html)
        );
		}
		
	 $wp_add_project = wp_remote_request(
      esc_url('https://api.textunited.com/ProjectAutomation/api/WebTranslationPageTranslate'),
      array(
          'method'    => 'POST',
		  'timeout'     => 45,
          'headers'   => $wp_request_headers,
          'body' =>  json_encode($body)
      )
     );
	 
 
	 
     $project = wp_remote_retrieve_body( $wp_add_project );
     $project = json_decode( $project );
     
	 $cache_save = '';
     if(!empty($project)){
	 $cache_save = array(
	    'body' => $project->html,
		'datetime' => date('Y-m-d\TH:i:s'),
		'title' => $project->title,
		'description' => $project->description,
		'hash' => md5($html)
	 );
	
	   if(!is_user_logged_in() && !empty($page))
      set_transient($page,$cache_save,DAY_IN_SECONDS);
	  }
       
		return $cache_save;
	  
	}
 
	public function TUT_display_filter($rules) {
		
		       if($this->login!=200)
	           return $rules;
	    	if ( is_admin() ) {
			return $rules;
			}
			$languages =get_option( 'TUlanguagesAdd' );
			
			if ( empty(get_option( 'TUcomapnyId' )) or empty($languages) ) {
			return $rules;
			}
			
			$languages =get_option( 'TUlanguagesAdd' );
		

		$site = get_site_url();
		$result = parse_url($site);
		$weburl = str_replace($site ,"",$result['scheme']."://".$result['host'].parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
       
		$segment = explode('/', trim($weburl, '/'));
			$ex = get_option( 'TUdefaultLanguage' ) ;
			foreach($languages as $k=>$langs){
			
				if(strtolower($segment[0])==strtolower($langs['LangCode'])){
					$ex = strtoupper($langs['LangCode']);
				}
			}
			return $ex;
	   
	   }


   function TUT_menu_swap( $args){
       if($this->login!=200)
	   return $args;
   
	   $language = get_locale();
	   $defaultLanguage = get_option( 'TUdefaultLanguage');
	   $langArray = explode('-',  $defaultLanguage);
	   $langArray = strtolower($langArray[1]);

	   $currentLanguage = strtolower($language);
	 
    if( $args['theme_location'] == 'primary') {
	$locations = get_nav_menu_locations();
	if(!empty($locations[$currentLanguage])){
	$menu = wp_get_nav_menu_object($locations[$currentLanguage]);
	$args['menu'] = $menu->name;
	}
	}

	return $args;
   }



   function TUT_blog_language($query) {
    global $wpdb;
	 if($this->login!=200)
	 return false;
	$language = get_locale();
	
	$code = $language;

	if ( is_admin() || !$query->is_main_query()) {
		return $query;
	}

	
	if ($query->is_home() && $query->is_main_query() && ! is_post_type_archive() && ! is_singular() && ! is_404()) {
		
	
		$query->set( 'meta_query', array(
			array(
				'key' => 'tu_language_field_post',
				'value' =>  $wpdb->esc_like($code) ,
				'compare' => 'LIKE',
				
			)
		 ) );
	
		
		}
		
	if ((is_author() || is_category()) && $query->is_main_query()) {
		
	
		$query->set( 'meta_query', array(
			array(
				'key' => 'tu_language_field_post',
				'value' =>  $wpdb->esc_like($code) ,
				'compare' => 'LIKE',
				
			)
		 ) );
	
		
		}
		
	if (is_search() && $query->is_main_query()) {
		
		$query->set( 'meta_query', array(
			array(
				'key' => 'tu_language_field_post',
				'value' =>  $wpdb->esc_like($code) ,
				'compare' => 'LIKE',
				
			)
		 ) );

		
		}
		
	if (is_tag() && $query->is_main_query()) {
		
		$query->set( 'meta_query', array(
			array(
				'key' => 'tu_language_field_post',
				'value' =>  $wpdb->esc_like($code) ,
				'compare' => 'LIKE',
				
			)
		 ) );
	
		
		}
 
		return $query;

   }



  function TUT_get_prev_past_events_where($where) {
	
	global $wpdb;
	 if($this->login!=200)
	 return $where;
	$language = get_locale();

	$code = $language;
	
	$search_text = "%" . $wpdb->esc_like($code) . "%";
	
    $where .= $wpdb->prepare("AND  p.ID IN ( SELECT post_id FROM {$wpdb->postmeta} WHERE ($wpdb->postmeta.post_id = p.ID ) AND $wpdb->postmeta.meta_key = 'tu_language_field_post' AND $wpdb->postmeta.meta_value LIKE  %s )", $search_text );

    return $where ;
  }

  function TUT_get_next_past_events_where($where) {
	
	global $wpdb;
	 if($this->login!=200)
	 return $where;
	$language = get_locale();
    $code = $language;
	$search_text = "%" . $wpdb->esc_like($code) . "%";
 
    $where .= $wpdb->prepare("AND  p.ID IN ( SELECT post_id FROM {$wpdb->postmeta} WHERE ($wpdb->postmeta.post_id = p.ID ) AND $wpdb->postmeta.meta_key = 'tu_language_field_post' AND $wpdb->postmeta.meta_value LIKE %s  )", $search_text );

	

	return $where;
  }


        function TUT_afterCommentPost($location, $commentdata){
		
		    if($this->login!=200)
            return wp_get_referer()."#comment-".$commentdata->comment_ID;	
		
		
		  $post_id = $commentdata->comment_post_ID;
		 $editor_id = get_post_meta(get_option( 'page_for_posts' ), 'tu_language_field_post',true );  
		$pages_array = array();
		$pages = array();
		array_push($pages_array,array("url" => get_the_permalink( get_option( 'page_for_posts' ) ),"enable" => true));
		array_push($pages_array,array("url" => get_the_permalink(  $post_id ),"enable" => true));
		
		
		$projects = array();
		
        $selected = get_option( 'TUlanguagesAdd' );
        foreach($editor_id as $id){
		
		
		foreach($selected as $k=>$select){
		if($select['LangCode']==$id){
		$agency = null;
		$transaltor = (int)$select['EmployeeId'];
		if($select['EmployeeId']<0){
		$agency = (int)$select['EmployeeId'];
		$transaltor = null;
		}
		$machine = (bool) $select['MachineTranslation'];
		}
		}
		
		
		
		$pagesArray = [
		"targetLanguageShort" => $id, 
		"published" => true,
		"enabled" => true,
		"pages" => $pages_array,
		"translatorId" => $transaltor,
		"agencyId" =>$agency,
		"machineTranslation"=>$machine,
		];
		if($id!=get_option('TUdefaultLanguage'))
		array_push($pages,$pagesArray);
		}
	
		
	    $project_id = get_option( 'TUprojectInfo');
	 	$body = array(
		"id" => (int)$project_id->id,
        "name" => get_bloginfo(),
        "url" => get_site_url(),
		"sourceLanguageShort" => get_option( 'TUdefaultLanguage'),
		"projects" => $pages,
        "comment" => "",
		"type" => "wordpress"
        );
		$wp_request_headers = array(
		  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
		  'Content-Type'   => 'application/json',
		  'x-textunited-addin' => 'wordpress'
		);
      $wp_add_project = wp_remote_request(
      'https://api.textunited.com/ProjectAutomation/api/WebTranslation',
      array(
          'method'    => 'PUT',
          'headers'   => $wp_request_headers,
          'body' =>  json_encode($body),
		  'timeout'     => 30
      )
     );
	  
		sleep (10);
		return wp_get_referer()."#comment-".$commentdata->comment_ID;
		
		}

}