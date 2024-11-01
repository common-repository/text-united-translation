<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.textunited.com/
 * @since      1.0.0
 *
 * @package    Text_United_Translation
 * @subpackage Text_United_Translation/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Text_United_Translation
 * @subpackage Text_United_Translation/admin
 * @author     Text United <servus@textunited.com>
 */
class Text_United_Translation_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
				  $wp_request_headers = array(
				  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
				  'Content-Type'   => 'application/json',
				  'x-textunited-addin' => 'wordpress'
				);
    
	 $this->TUTlanguageArray = array();
	 $selected = get_option( 'TUlanguagesAdd' );
	 if(!empty($selected)){
	 foreach($selected as $languages){
	 array_push($this->TUTlanguageArray,$languages['LangCode']);
	 }
	 }
		$wp_get_languages = wp_remote_request(
			'https://www.textunited.com/api/languages',
			array(
				'method'    => 'GET',
				'headers'   => $wp_request_headers
			)
		  );
		  
		update_option("TUcheckAuth",wp_remote_retrieve_response_code( $wp_get_languages ),false);
        $this->login = get_option("TUcheckAuth");  

        $this->languageArray = array();
		$language = wp_remote_retrieve_body( $wp_get_languages );
		$language = json_decode( $language);
		if(!empty($language)){
		 foreach($language as $languagesArr){
	       array_push($this->languageArray,$languagesArr->LangCode);
	     }
		}

	}

	/**
	 * Register the stylesheets for the admin area.
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
		 
        $screen = null;
		if(!empty($_GET['page'])) 
		$screen = $_GET['page'];

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/text-united-translation-admin.css', array(), $this->version, 'all' );
		
		if($screen=='text-united-translation/mainsettings.php' || $screen=='text-united-translation/faq.php' || $screen=='text-united-translation/importer.php')
		wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
	
        wp_enqueue_style( 'select2-css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'select2Bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/select2-bootstrap4.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "flags-ico",plugin_dir_url( __FILE__ ) . 'css/flag-icon.css', array(), $this->version, 'all'  );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/text-united-translation-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'select2-js', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'jquery-sortable', plugin_dir_url( __FILE__ ) . 'js/jquery-sortable.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'populatequickedit', plugin_dir_url( __FILE__ ) . '/js/populate.js', array( 'jquery' ) , $this->version, false);
		wp_localize_script( 'liker_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 

	}
	
	public function tu_admin_menu() {
		
		add_menu_page('Text United Welcome', 'Text United', 'manage_options', 'text-united-translation/mainsettings.php', array($this,'tu_plugin_admin_page'), 'dashicons-tu',250 );
        add_submenu_page( 'text-united-translation/mainsettings.php', 'Text United settings', 'Settings', 'manage_options', 'text-united-translation/importer.php',array($this, 'tu_plugin_admin_sub_page') );
		add_submenu_page( 'text-united-translation/mainsettings.php', 'How to use?', 'How to use?', 'manage_options', 'text-united-translation/faq.php',array($this, 'tu_plugin_admin_sub_page_faq') );
	}

	public function tu_plugin_admin_page() {
        require_once 'partials/text-united-translation-admin-display.php';
	}

	public function tu_plugin_admin_sub_page() {
        require_once 'partials/submunu-page.php';
	}
	
		public function tu_plugin_admin_sub_page_faq() {
        require_once 'partials/faq-page.php';
	}

	function TUT_add_toolbar_items($admin_bar){
		if(!is_admin(  )){

	
		$admin_bar->add_menu( array(
			'id'    => 'tubar',
			'title' => '<span class="ab-icon"></span>Text United',
			'href'  => '#',
			
			'meta'  => array(
				'title' => __('Text United'),
				'class'    => 'wpse--item',            
			),
		));
		$admin_bar->add_menu( array(
			'id'    => 'my-sub-item',
			'parent' => 'tubar',
			'title' => 'Settings',
			'href'  => admin_url().'admin.php?page=text-united-translation%2Fimporter.php&module=3&sub=2',
			'meta'  => array(
				'title' => __('Settings'),
				
				'class' => 'my_menu_item_class'
			),
		));
		$admin_bar->add_menu( array(
			'id'    => 'my-second-sub-item',
			'parent' => 'tubar',
			'title' => 'Portal - Text United',
			'href'  => 'https://www.textunited.com/my/',
			'meta'  => array(
				'title' => __('Portal - Text United'),
				'target' => '_blank',
				'class' => 'my_menu_item_class'
			),
		));
		$project_id = get_option( 'TUprojectInfo');
		if(!empty($project_id) && !empty($project_id->projects[0]))
		$url = 'https://www.textunited.com/my/translation/preview2/'.$project_id->projects[0]->id.'/';
		else
		$url = '#';
			$admin_bar->add_menu( array(
			'id'    => 'my-third-sub-item',
			'parent' => 'tubar',
			'title' => 'Overlay editor',
			'href'  => $url,
			'meta'  => array(
				'title' => __('Overlay editor'),
				'target' => '_blank',
				'class' => 'my_menu_item_class'
			),
		));

	}
	}


	public function TUT_register_general_settings() {
	
	   $argsTUT_customsettingsInt = array(
            'type' => 'integer', 
            'sanitize_callback' => array( $this, 'TUT_setting_int_validation' ),
            'default' => NULL,
            );
			
	 $argsTUT_customsettingsString = array(
            'type' => 'string', 
            'sanitize_callback' => array( $this, 'TUT_setting_string_validation' ),
            'default' => NULL,
            );
			
	 $argsTUT_customsettingsSelectInt = array(
            'type' => 'integer', 
            'sanitize_callback' => array( $this, 'TUT_setting_selectInt_validation' ),
            'default' => 2,
            );

	 $argsTUT_customsettingsSelectIntContent = array(
            'type' => 'integer', 
            'sanitize_callback' => array( $this, 'TUT_setting_selectIntContent_validation' ),
            'default' => 1,
            );			
	
	 $argsTUT_customsettingsbool = array(
            'type' => 'boolean', 
            'sanitize_callback' => array( $this, 'TUT_setting_bool_validation' ),
            'default' => false,
            );
	
	 $argsTUT_customsettingsDefaultLang = array(
            'type' => 'array', 
            'sanitize_callback' => array( $this, 'TUT_setting_addLangs_validation' ),
            'default' => NULL,
            );
			
     $argsTUT_customsettingsAddNew = array(
            'type' => 'array', 
            'sanitize_callback' => array( $this, 'TUT_setting_addNew_validation' ),
            'default' => NULL,
            );		
		
	 $argsTUT_customsettingsDefaultLangMain = array(
            'type' => 'array', 
            'sanitize_callback' => array( $this, 'TUT_setting_defaultMain_validation' ),
            'default' => NULL,
            );
		
	
		register_setting( 'TUT_customsettings','TUcomapnyId',$argsTUT_customsettingsInt );
		register_setting( 'TUT_customsettings','TUapiKey',$argsTUT_customsettingsString );
		register_setting( 'TUT_customsettings','TUtoken',$argsTUT_customsettingsString );
		register_setting( 'TUT_languagesettings','TUlanguageSelectorType', $argsTUT_customsettingsSelectInt);
		register_setting( 'TUT_languagesettings','TUlanguageSelectorContent',$argsTUT_customsettingsSelectIntContent );
        register_setting( 'TUT_languagesettings','TUshortcode',$argsTUT_customsettingsbool );
		register_setting( 'TUT_languagesettings','TUflagShape',$argsTUT_customsettingsbool );
		register_setting( 'TUT_languagesettingsDefault','TUdefaultLanguage',$argsTUT_customsettingsString);
		register_setting( 'TUT_languagesettingsDefaultMain','TUdefaultLanguageMain',$argsTUT_customsettingsDefaultLangMain);
		register_setting( 'TUT_languageArray','TUlanguagesAdd',$argsTUT_customsettingsDefaultLang );	
        register_setting( 'TUT_languageArrayAdd','TUlanguagesAddNew',$argsTUT_customsettingsAddNew );
		register_meta( 'media', 'textunited_url', array(
			'type' => 'string',
			'description' => 'event location',
			'single' => true,
			'show_in_rest' => true
		));		
	}
	
	
	public function TUT_setting_int_validation($value){
	if(intval($value)){
	return $value;
	}
	else{
	add_settings_error('TUT_company_info_error','my_plg_validate_num_tags_error','Incorrect value entered!','error');
	return '';
	}
	}
	
	public function TUT_setting_string_validation($value){
	$value = sanitize_text_field($value);
	if(!empty($value)){
	return $value;
	}
	else{
	add_settings_error('TUT_company_info_error','my_plg_validate_num_tags_error','Incorrect value entered!','error');
	return '';
	}
	}
	
	public function TUT_setting_selectInt_validation($value){
	$valid_values = array(1,2,3,4,5,6,7);
	if(intval($value) && in_array( $value, $valid_values )){
	return $value;
	}
	else{
	add_settings_error('TUT_company_info_error','my_plg_validate_num_tags_error','Incorrect value entered!','error');
	return 2;
	}
	}
	
	public function TUT_setting_selectIntContent_validation($value){
	$valid_values = array(1,2,3);
	if(intval($value) && in_array( $value, $valid_values )){
	return $value;
	}
	else{
	add_settings_error('TUT_company_info_error','my_plg_validate_num_tags_error','Incorrect value entered!','error');
	return 1;
	}
	}
	
    public function TUT_setting_bool_validation($value){
       return ( isset( $value ) ? true : false );
	}
	
	public function TUT_setting_addLangs_validation($value){
	
	return $value;
	
		$args = array(
		'LangCode'   => FILTER_SANITIZE_STRING,
		'DescriptiveName' => FILTER_SANITIZE_STRING,
		'SystemName' => FILTER_SANITIZE_STRING,
		'Order' => FILTER_SANITIZE_NUMBER_INT,
		'Default' =>FILTER_VALIDATE_BOOLEAN,
		'MachineTranslation' =>FILTER_VALIDATE_BOOLEAN,
		'EmployeeId' =>FILTER_SANITIZE_NUMBER_INT,
		'Id' => FILTER_SANITIZE_NUMBER_INT
	);
 
 	$languageCheck = array();
	  
		 foreach($value as $languagesArr){
	       
		    $myinputs = filter_var_array($languagesArr, $args);
			
			array_push($languageCheck,$myinputs);
	     }
   
    $post_languagesAdd = $languageCheck;
	$array = array();
	foreach( $post_languagesAdd as $checkValue){
		       array_push($array,$checkValue['LangCode']);
	}
	
	if(array_intersect($this->languageArray, $array)){
   
	return $value;
	
	}else{
	return get_option( 'TUlanguagesAdd' );
	
	}

	
	}
	
	public function TUT_setting_defaultMain_validation($value){
  
	if(empty($value))
	return get_option( 'TUlanguagesAdd' );
	
		$args = array(
		'LangCode'   => FILTER_SANITIZE_STRING,
		'DescriptiveName'     => FILTER_SANITIZE_STRING,
		'SystemName' => FILTER_SANITIZE_STRING,
		'Order' => FILTER_SANITIZE_NUMBER_INT,
		'Default' =>FILTER_VALIDATE_BOOLEAN,
		'MachineTranslation' =>FILTER_VALIDATE_BOOLEAN,
		'EmployeeId' =>FILTER_SANITIZE_NUMBER_INT,
		'Id' => FILTER_SANITIZE_NUMBER_INT
	);

    $myinputs = filter_var_array($value, $args);
	
	$arrays =  $myinputs;
   

  if(get_option( 'TUdefaultLanguage') != $arrays['LangCode'] && in_array($arrays['LangCode'],$this->languageArray)
   && $arrays['Order'] == '' && $arrays['EmployeeId']=='' && !$arrays['MachineTranslation']){
  
 
 
    $array = [];
 
	$body = array(
        "name" => get_bloginfo(),
        "url" => get_site_url(),
        "sourceLanguageId" => (int)$arrays['Id'],
        "comment" => "",
		"type" => "wordpress"
    );
	
   $wp_request_headers = array(
	  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
	  'Content-Type'   => 'application/json',
	  'x-textunited-addin' => 'wordpress'
	);
	
    $wp_add_project = wp_remote_request(
      'http://api.textunited.com/ProjectAutomation/api/WebTranslation',
      array(
          'method'    => 'POST',
          'headers'   => $wp_request_headers,
          'body' =>  json_encode($body),
		  'timeout'     => 30
      )
     );
 
    $project = wp_remote_retrieve_body( $wp_add_project );
    $project = json_decode( $project );
	if(!empty($project)) {
    update_option( 'TUprojectInfo',$project,false);
	
	update_option( 'TUdefaultLanguage',$arrays['LangCode'],false);
    update_option( 'TUlanguagesAdd',array($arrays),false);
	
	 $pages = array(
	  'post_type'    => 'any',
	  'orderby'      => 'menu_order',
	  'posts_per_page' => -1,
      'fields' => 'ids',
      'post_status' => 'publish' 
	);
	$the_query = new WP_Query($pages  );

		if ( $the_query->have_posts() ) {
				foreach ( $the_query as $item ) {
	  
				$the_query->the_post();
 
				 $default = array($arrays['LangCode']);
				 update_post_meta(get_the_ID($item), 'tu_language_field_post', $default);
	 
			}
			 
			
		}
		}
	
	
	} 
	
	
	
	}
	
	
	
	public function TUT_setting_addNew_validation($value){

	if(empty($value))
	return '';
    	
		
	$args = array(
		'LangCode'   => FILTER_SANITIZE_STRING,
		'DescriptiveName' => FILTER_SANITIZE_STRING,
		'SystemName' => FILTER_SANITIZE_STRING,
		'Order' => FILTER_SANITIZE_NUMBER_INT,
		'Default' =>FILTER_VALIDATE_BOOLEAN,
		'MachineTranslation' =>FILTER_VALIDATE_BOOLEAN,
		'EmployeeId' =>FILTER_SANITIZE_NUMBER_INT,
		'Id' => FILTER_SANITIZE_NUMBER_INT
	);


    $myinputs = filter_var_array($value, $args);
    $post_languagesAdd = $myinputs;
	

if(get_option( 'TUdefaultLanguage') != $post_languagesAdd['LangCode'] && in_array($post_languagesAdd['LangCode'],$this->languageArray)
    && !$post_languagesAdd['Default'] && $post_languagesAdd['EmployeeId']!=''){
	

$post_languagesAdd['EmployeeId']=(int)$post_languagesAdd['EmployeeId'];
if(empty($post_languagesAdd['MachineTranslation']))
$post_languagesAdd['MachineTranslation']=false;
else
$post_languagesAdd['MachineTranslation']=true;


$agency=null;
$transaltor = (int)$post_languagesAdd['EmployeeId'];
if($post_languagesAdd['EmployeeId']<0){
$agency=(int)$post_languagesAdd['EmployeeId'];
$transaltor = null;
}

$projects = [

  "targetLanguageId" => (int)$post_languagesAdd['Id'], 
  "published" => true,
  "enabled" => true,
  "pages" => array(),
  "translatorId" => $transaltor,
  "machineTranslation" => $post_languagesAdd['MachineTranslation'],
  "agencyId"=> $agency
  

];
 
		$array = array(
		        "url" => get_site_url().'/',
                "enable" => true
		);
		array_push($projects['pages'] ,$array);
		

    $project_id = get_option( 'TUprojectInfo');
	 	$body = array(
		"id" => (int)$project_id->id,
        "name" => get_bloginfo(),
        "url" => get_site_url(),
		"projects" => array($projects),
		"sourceLanguageShort" => get_option( 'TUdefaultLanguage'),
        "comment" => "",
		"type" => "wordpress"
        );
	$wp_request_headers = array(
	  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
	  'Content-Type'   => 'application/json',
	  'x-textunited-addin' => 'wordpress'
	  
	);	
		
		
      $wp_add_project = wp_remote_request(
      'http://api.textunited.com/ProjectAutomation/api/WebTranslation',
      array(
          'method'    => 'PUT',
          'headers'   => $wp_request_headers,
          'body' =>  json_encode($body),
		  'timeout'     => 30
      )
     );
	 
  
    $project = wp_remote_retrieve_body( $wp_add_project );
    $project = json_decode( $project );
	
	 if(!empty($project))
	 update_option( 'TUprojectInfo',$project,false);
 
$array = get_option( 'TUlanguagesAdd' );
 
array_push($array, $post_languagesAdd);
update_option( 'TUlanguagesAdd',$array,false );


return '';
 
 }else{
 return '';
 
 }
		
		
		
		
	}


	function TUT_custom_page_rules() {
		global $wp_rewrite;
		$wp_rewrite->flush_rules(false);
		
		
		$site = get_site_url();
		$home = get_home_url();
		update_option( 'TUhome', $home,false);
		update_option( 'home', $site,false);
		$langArrayAll=[];
		$languages = get_option( 'TUlanguagesAdd' );
       if(!empty($languages))   {        
		foreach($languages as $k=>$select){
		 
		 $langArray = strtolower($select['LangCode']);
		 if($select['Default']==0)
		 $langArrayAll[$langArray] =  __( 'menu '.$langArray.'' );


		}
		
		}
		$array = array('post','page');
		$argse = array(
		   'public'   => true,
		   '_builtin' => false // Use false to return only custom post types
		);
		$taxonomies =  get_post_types($argse);
		
		
		if(!empty($taxonomies)){
		foreach($taxonomies as $taxonomi){
		  array_push($array,$taxonomies[$taxonomi]);
		}
		}
		
	    //register_nav_menus($langArrayAll);

	
	}

	function TUT_custom_columnadd($columns){

	
		 $wp_request_headers = array(
		  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
		  'Content-Type'   => 'application/json',
		  'x-textunited-addin' => 'wordpress' 
		  
		);
		  $project_id = get_option( 'TUprojectInfo');
		  $wp_stats = wp_remote_request('http://api.textunited.com/ProjectAutomation/api/WebTranslationPageStatus/'.$project_id->id.'',
			array(
				'method'    => 'GET',
				'headers'   => $wp_request_headers,
				'timeout'     => 30
			)
		  );
  
  $stats = wp_remote_retrieve_body( $wp_stats );
  $stats = json_decode( $stats );
 
   if(!empty($stats))  
   set_transient( 'TUstatuses', $stats);
 
    
	$columns['language']  = 'Languages';
    return $columns;

	}
	
	function TUT_my_admin_notice() { 
	
	    $stats = get_transient('TUstatuses');
		$i = 0;
		$k = 0;
		$array= array();
		$MTalerts = get_option( 'TUMTalert');
		if(!empty($stats)){
		foreach($stats as $alert) {
		if(!empty($alert->logEntriesToAppend)){
		
		    foreach($alert->logEntriesToAppend as $text){
			array_push($array,$alert->url.' - '.$text);
			 if(++$i > 6) break;
			}
		}
		
		}
		
		if(!empty($MTalerts)){
			echo '<div class="notice notice-info is-dismissible">';
			         foreach($MTalerts as $alertTextMT){
					 echo '<p>All newly added pages and articles for translation into <b>'.esc_html($alertTextMT).'</b> will be machine translated.</p>';
					  if(++$k > 6) break;
					 }
		    echo '</div>';
			update_option( 'TUMTalert','',false);
		}
        
		if(!empty($array)){
			echo '<div class="notice notice-error is-dismissible">';
			         foreach($array as $alertText){
					 echo '<p>'.$alertText.'</p>';
					 }
		    echo '</div>';
		 }
		 }
		 
}


	function TUT_custom_columnaddAction($column_name, $post_id){
	
	       $stats = get_transient('TUstatuses');
		   $defaultLanguage = get_option( 'TUdefaultLanguage');
		   $langArray = explode('-',  $defaultLanguage);
		   $langArray = strtolower($langArray[1]);
		   
		if ($column_name == 'language') {
			$verified = get_post_meta($post_id, 'tu_language_field_post', true);
			if (!empty($verified)) {
				echo '<div class="idk" data-url="'.esc_url(get_permalink($post_id)).'"  data-stuff=\''.wp_json_encode($verified).'\'>';
				foreach($verified as $flags){
				    $styleFlag = 'grey'; 
					$title = 'title="The page is being processed. Referesh the view to see its current status."';
                   if(!empty($stats)){					
					foreach($stats as $grey) {
					 if(!empty($grey->languageShort) && $flags==$grey->languageShort && get_permalink($post_id) == get_site_url().$grey->url && $grey->processedSuccessfully ==  true ){
					 $styleFlag = '';
					 $title = ''; }
					   }
					} 
					 
						
					 
					$flag = explode('-',  $flags);
					$flag = strtolower($flag[1]);
					
					$specialFlags = array('NL-BE','EN-CA','FR-BE','FR-CA','GL-GL','GN-GN','KN-KN','KI-KI','LA-LA','MS-MS','ML-ML','MR-MR','MX-MX','MO-MO','NE-NE','OM-OM','PS-PS','PA-PA','SA-SA','ST-ST','SR-SR','SD-SD','SL-SL','ES-AR','ES-DO','SV-SV','TT-TT','BO-BO','TK-TK','VI-VI');
					 if(in_array($flags,$specialFlags)) 
					 $flag = strtolower($flags);
					
					 if($flags!=$defaultLanguage)
				       echo '<span style="margin:2px;" '.$title.'  class="flag-icon flag-icon-'.$flag.' '.$styleFlag.'"></span>';
				     else
					   echo '<span style="margin:2px;"  class="flag-icon flag-icon-'.$flag.'"></span>';
				}
				
				echo '</div>';
			} else {
				echo '';
			}
		}
	}


	function TUT_custom_post_action_links( $actions, $post ) {
		 
		
		$array = get_option( 'TUcustomTypes' );
	
		 
		if (in_array($post->post_type,$array)) {
		$project_id = get_option( 'TUprojectInfo');
		$project_id = get_option( 'TUprojectInfo');
		
		if(!empty($project_id)){
		$postCode = get_post_meta($post->ID, 'tu_language_field_post',true );
		
		if(empty($postCode))
		return $actions;
		
		if($postCode[0]==get_option( 'TUdefaultLanguage') && count($postCode)>1)
		$postLang = $postCode[1];
		else
		$postLang = $postCode[0];
		$link = '#';
		foreach($project_id->projects as $lang){
		if($lang->targetLanguageShort==$postLang){
		$link = $lang->id;
	    $url = 'https://www.textunited.com/my/translation/previewWP/'.$link.'/?link='.urlencode(get_permalink($post->ID)).'';
		
		/* custom action link will be listed under post listing rows */
		$actions['custom'] = '<a href="'. esc_url($url) .'" target="_blank">Translation</a>';
		}
		}

		
		}
		}
		return $actions;
		
		}


	public function TUT_create_meta_box(){
      	if($this->login!=200)
        return false;	
		add_meta_box( 'tu_language', 'Language', [$this,'TUT_tu_meta_box_html'], get_option( 'TUcustomTypes' ), 'normal', 'high' );
		
	}

	public function	TUT_bulk_edit(){
	
        if($this->login!=200)
        return false;
         
		if ( !wp_verify_nonce( $_POST['nonce'], 'misha_q_edit_nonce' ) ) {
			wp_die();
		}
		
       	$projects = array();
		$pages = array();
        $selected = get_option( 'TUlanguagesAdd' );
		$post_languages = array_map( 'sanitize_text_field', $_POST['price']);
		$items_id = array_map( 'intval', $_POST['post_ids']);
		
		if(!array_intersect($this->TUTlanguageArray, $post_languages))
		return false;
		
		if(!empty($post_languages)){
			
			if(!empty(get_option('TUdefaultLanguage'))){
				if (!in_array(get_option('TUdefaultLanguage'), $post_languages)) {
                    array_unshift($post_languages, get_option('TUdefaultLanguage'));
			    }
	        }

				foreach($post_languages as $ids){
				
						foreach($selected as $select){
							if($select['LangCode']==$ids){
							$agency = null;
							$transaltor = (int)$select['EmployeeId'];
							if($select['EmployeeId']<0){
							$agency = (int)$select['EmployeeId'];
							$transaltor = null;
							}
							if(!empty($select['MachineTranslation']))
							$machine = (bool)$select['MachineTranslation'];
							else
							$machine = false;
							}
						}
				
				
							$pagesArray = [
							"targetLanguageShort" => $ids, 
							"published" => true,
							"enabled" => true,
							"pages" => array(),
							"translatorId" => $transaltor,
							"agencyId" =>$agency,
							"machineTranslation"=>$machine
							];
					if($ids!=get_option('TUdefaultLanguage'))		
					array_push($pages,$pagesArray);
				}
              
		
		foreach( $items_id as $id ) {
		
		    if(!get_permalink($id))
			return false;
			
			update_post_meta( $id, 'tu_language_field_post',$post_languages);
            update_post_meta( $id, 'tu_language_field_post_url',get_permalink($id));
			foreach($post_languages as $languageCode){
			delete_transient('cache_'.$languageCode.'_'.get_page($id)->post_type.'_'.$id);
			}

			   	foreach($pages as $k=>$ids){
					$array = array(
							"url" => get_the_permalink( $id ),
							"enable" => true
							
					);
					array_push($pages[$k]['pages'] ,$array);
					
					
		$taxonomy_names = get_object_taxonomies( get_post($id),  $output = 'names' );
		
		
		if(!empty($taxonomy_names)){
			 foreach($taxonomy_names as $taxonomy){
				 $taxnames = get_the_terms( $id, $taxonomy );
				 
				 		 if(!empty($taxnames) && empty($taxnames->errors)){
							  foreach($taxnames as $items){
							 
								 array_push($pages[$k]['pages'], array("url" => get_term_link($items->term_id,$items->taxonomy),"enable" => true));
								 update_term_meta( $items->term_id, 'tu_language_field_term_url',get_term_link($items->term_id,$items->taxonomy));
								 foreach($post_languages as $languageCode){
									 delete_transient('cache_'.$languageCode.'_'.$items->taxonomy.'_'.$items->term_id);
								 }
							  }
							 }
			 }
			
			
		}			
					

		if(get_page( $id)->post_type == 'post'){
		$author_id = get_post( $id )->post_author;
		if(empty($author_id))
		$author_id = get_current_user_id();
		
		update_user_meta( $author_id, 'tu_language_field_author_url',get_author_posts_url( $author_id ));
		array_push($pages[$k]['pages'],array("url" => get_author_posts_url( $author_id ),"enable" => true));
			foreach($post_languages as $languageCode){
			delete_transient('cache_'.$languageCode.'_author_'.$author_id);
			}
		
		array_push($pages[$k]['pages'],array("url" => get_the_permalink( get_option( 'page_for_posts' ) ),"enable" => true));
		update_post_meta( $id, 'tu_language_field_post_url',get_the_permalink( get_option( 'page_for_posts' )) );
		}

			}
		}
		
	 
	 	if(empty($pages))
		return false;
	 
		$project_id = get_option( 'TUprojectInfo');
	 	$body = array(
		"id" => (int)$project_id->id,
        "name" => get_bloginfo(),
        "url" => get_site_url(),
		"projects" => $pages,
		"sourceLanguageShort" => get_option( 'TUdefaultLanguage'),
        "comment" => "",
		"type" => "wordpress"
        );
		$wp_request_headers = array(
		  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
		  'Content-Type'   => 'application/json',
		  'x-textunited-addin' => 'wordpress'
		);
		
      $wp_add_project = wp_remote_request(
      'http://api.textunited.com/ProjectAutomation/api/WebTranslation',
      array(
          'method'    => 'PUT',
          'headers'   => $wp_request_headers,
          'body' =>  json_encode($body),
		  'timeout'     => 30
      )
     );
	 
	 
 
	   }
		return 'ok';
	}
	public function TUT_save_meta_box($post_id, $post, $update ){ 
	   
	    if($this->login!=200)
        return false;		
		
		if ( !current_user_can( 'edit_post', $post_id ) ) {
		 	return;
		}
		
		if(empty($_POST['tu_language_field_post']))
		return false;
		
		$language_post = array_map( 'sanitize_text_field', $_POST['tu_language_field_post'] );
		
		if(!empty(get_option('TUdefaultLanguage'))){
			if (!in_array(get_option('TUdefaultLanguage'), $language_post)) {
				
				array_unshift($language_post, get_option('TUdefaultLanguage'));
			}
		}
		
		if(!array_intersect($this->TUTlanguageArray, $language_post))
		return false;
		
		if(!get_permalink($post_id))
		return false;
		
		if(isset($language_post)){
		$send = $language_post;
		}else{
		$send = get_post_meta($post_id, 'tu_language_field_post',true );
		}
		
		
		
		 
        if(!empty($send) && !empty($post->post_status) && ($post->post_status=='trash' || $post->post_status=='publish')){
		 
		$pages_array=array();
		$pages = array();
		
		$editor_id = $send;
		
		 
		 
		 $taxonomy_names = get_object_taxonomies( $post,  $output = 'names' );
		 
		
		if(!empty($taxonomy_names)){
			 foreach($taxonomy_names as $taxonomy){
				 $taxnames = get_the_terms( $post_id, $taxonomy );
				 
				 		 if(!empty($taxnames) && empty($taxnames->errors)){
							  foreach($taxnames as $items){
							 
								 array_push($pages_array, array("url" => get_term_link($items->term_id,$items->taxonomy),"enable" => true));
								 update_term_meta( $items->term_id, 'tu_language_field_term_url',get_term_link($items->term_id,$items->taxonomy));
								 foreach($editor_id as $languageCode){
									 delete_transient('cache_'.$languageCode.'_'.$items->taxonomy.'_'.$items->term_id);
								 }
							  }
							 }
			 }
			
			
		}
		
		 if($post->post_status=='trash'){
		 array_push($pages_array, array("url" => str_replace('__trashed','',get_the_permalink( $post_id )),"enable" => false));
		 }
		 else{
		 array_push($pages_array, array("url" => get_the_permalink( $post_id ),"enable" => true));
		 }
		 
		 update_post_meta( $post_id, 'tu_language_field_post',$editor_id);
		 update_post_meta( $post_id, 'tu_language_field_post_url',get_permalink($post_id));
		 
		
		 
		 	foreach($editor_id as $languageCode){
			delete_transient('cache_'.$languageCode.'_'.get_page($post_id)->post_type.'_'.$post_id);
			}
		  
 
		if(get_page( $post_id)->post_type == 'post'){
		$author_id = get_post( $post_id )->post_author;
		if(empty($author_id))
		$author_id = get_current_user_id();
		
		update_user_meta( $author_id, 'tu_language_field_author_url',get_author_posts_url( $author_id ));
		array_push($pages_array,array("url" => get_author_posts_url( $author_id ),"enable" => true));
			foreach($editor_id as $languageCode){
			delete_transient('cache_'.$languageCode.'_author_'.$author_id);
			}
		
		array_push($pages_array,array("url" => get_the_permalink( get_option( 'page_for_posts' ) ),"enable" => true));
		//update_post_meta( $post_id, 'tu_language_field_post_url',get_the_permalink( get_option( 'page_for_posts' )) );
		}
		
		$projects = array();
		
		
		 
          $selected = get_option( 'TUlanguagesAdd' );
              
               
        foreach($editor_id as $id){
		
		foreach($selected as $k=>$select){
		if($select['LangCode']==$id){
		$agency = null;
		$transaltor = null;
		if (!empty($select['EmployeeId']))
		$transaltor = (int)$select['EmployeeId'];
		if(!empty($select['EmployeeId']) && $select['EmployeeId']<0){
		$agency = (int)$select['EmployeeId'];
		$transaltor = null;
		}
		if(!empty($select['MachineTranslation']))
		$machine = (bool)$select['MachineTranslation'];
	    else
		$machine = false;
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
	
		if(empty($pages))
		return false;
		
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
		
		if(!empty($pages)){
      $wp_add_project = wp_remote_request(
      'http://api.textunited.com/ProjectAutomation/api/WebTranslation',
      array(
          'method'    => 'PUT',
          'headers'   => $wp_request_headers,
          'body' =>  json_encode($body),
		  'timeout'     => 30
      )
     );
	 }
	 
    
     $project = wp_remote_retrieve_body( $wp_add_project );
     $project = json_decode( $project );
      
		}


	}

	public function TUT_tu_meta_box_html(  ) {
		$selected = get_option( 'TUlanguagesAdd' );
        if($this->login!=200)
        return false;		
		?>
		
		<label for="wporg_field">Select languages: </label>
		<select style="width:100%" name="tu_language_field_post[]" id="TUlanguagesPost" multiple class="components-select-control__input q-searchtut">
		<option></option>
		<?php
		$selectedLanguage = get_post_meta(get_the_ID(), 'tu_language_field_post',true );
		
		foreach($selected as $k=>$select){
		 
		 $langArray = explode('-',  $select['LangCode']);
		 $langArray = strtolower($langArray[1]);
		    
			if(!empty($selectedLanguage) && in_array($select['LangCode'],$selectedLanguage))
			$selection = 'selected="selected"';
			elseif($select['Default']== 1 && empty($selectedLanguage))
			$selection = 'selected="selected"';
			else
			$selection = '';

		    
			echo '<option '.$selection.' value="'.esc_attr($select['LangCode']).'">'.esc_html($select['DescriptiveName']).'</option>';
			
		

		  }
		  ?>
		</select>
		<?php
		
	}



	function  TUT_custom_edit_box( $column_name, $post_type, $taxonomy ) {
		global $post;
		if($this->login!=200)
        return false;	
		$array = get_option( 'TUcustomTypes' );
		if ( in_array($post->post_type,$array) ) {
		
				$defaultLanguage = get_option( 'TUdefaultLanguage');
				$langArray = explode('-',  $defaultLanguage);
				$langArray = strtolower($langArray[1]);
			if( $column_name === 'language' ){ // same column title as defined in previous step	
			wp_nonce_field( 'misha_q_edit_nonce', 'misha_nonce' );
			echo '

			<div class="q-searchtut"  id="#edit-" style="padding: 0 .5em;">
		
					<div class="inline-edit-col">
						<label style="max-width:100%;width: 100%;"><span class="title" style="margin: .5em 0;display:block">Languages</span>
						<span class="input-text-wrap">
							<select style="width:100%"  class="form-control inline-edit-menu-order-input TUlanguagesAddEdit" multiple="multiple" name="tu_language_field_post[]" >';
							
			
               $languages = get_option( 'TUlanguagesAdd' ); 
               foreach($languages as $lang){
				  
                echo'<option value="'.esc_attr($lang['LangCode']).'">'.esc_html($lang['DescriptiveName']).'</option>';
               }

            echo '
            </select>
            		</span>				
					</label>
					</div>
				</div>';
			}
	
		}
	}


	function TUT_quick_edit_fields( $column_name, $post_type ) {
 	    if($this->login!=200)
        return false;	
		// you can check post type as well but is seems not required because your columns are added for specific CPT anyway
	 
		switch( $column_name ) :
			case 'language': {
	 
				// you can also print Nonce here, do not do it ouside the switch() because it will be printed many times
				wp_nonce_field( 'misha_q_edit_nonce', 'misha_nonce' );
	 
				// please note: the <fieldset> classes could be:
				// inline-edit-col-left, inline-edit-col-center, inline-edit-col-right
				// each class for each column, all columns are float:left,
				// so, if you want a left column, use clear:both element before
				// the best way to use classes here is to look in browser "inspect element" at the other fields
	 
				// for the FIRST column only, it opens <fieldset> element, all our fields will be there
				echo '
					<div class="inline-edit-col q-searchtut" style="padding: 0 .5em;">
						<div class="inline-edit-group wp-clearfix">';
	 
				echo '<label style="max-width:100%;width: 100%;" class="alignleft">
						<span class="title" style="margin: .5em 0;display:block">Languages</span>
						<span class="input-text-wrap" >
						
						<select style="width: 100%"  class="form-control inline-edit-menu-order-input TUlanguagesAddEditBulk" multiple="multiple" name="tu_language_field_postBulk[]" >';
							
						   $languages = get_option( 'TUlanguagesAdd' ); 
						   foreach($languages as $lang){
						    if($lang['Default'])
							echo'<option selected="selected" value="'.esc_attr($lang['LangCode']).'">'.esc_html($lang['DescriptiveName']).'</option>';
							else
							echo'<option value="'.esc_attr($lang['LangCode']).'">'.esc_html($lang['DescriptiveName']).'</option>';
						   }
			
						echo'</select>
						</span>
					</label>
					</div>
					</div>
					
					';
	 
				break;
	 
			}
	 
		endswitch;
	 
	}

	function enqueue_quick_edit_population( $pagehook ) {
 
		// do nothing if we are not on the target pages

	}
	
	
	function TUT_reset_languages($old_value, $value, $option){
	if($this->login!=200)
    return false;	
	
	if(empty(get_option( 'TUprojectInfo')))
	return false;

    $projId =  get_option( 'TUprojectInfoId'); 
    $proj =  get_option( 'TUprojectInfo');  
    if(!empty($proj) && $projId!=$proj->id){
    update_option( 'TUprojectInfoId',$proj->id,false); 
	return false;
    }

	
		$wp_request_headers = array(
		  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
		  'Content-Type'   => 'application/json',
		  'x-textunited-addin' => 'wordpress'
		);
	 
	$lang1 = '';
	$lang2 = '';
	$language_delete = array();
	$MTalert = array();
	if(!empty($old_value)){
	foreach($old_value as $search){
	if($search['Default']==1)
	$lang1 = $search['LangCode'];
	
	
	if(!empty($value)){
	foreach($value as $valueN){
	if(!empty($search['Id']) && !empty($valueN['Id']) &&  $search['Id'] == $valueN['Id'] && (bool)$search['MachineTranslation']==false && (bool)$valueN['MachineTranslation']==true)
	array_push($MTalert,$valueN['SystemName']);
	}
	}
	
	}
	if(!empty($MTalert))
	update_option( 'TUMTalert',$MTalert,false);
	
	}
	
	foreach($value as $search2){
	if($search2['Default']==1)
	$lang2 = $search2['LangCode'];
	
	array_push($language_delete,$search2['LangCode']);
	}
	
	if ($lang2==$lang1) {
	
	
	$pages = array(
	  'post_type'    => 'any',
	  'orderby'      => 'menu_order',
	  'posts_per_page' => -1,
      'fields' => 'ids',
      'post_status' => 'publish' 
	);
	$the_query = new WP_Query($pages  );

		if ( $the_query->have_posts() ) {
			foreach ( $the_query as $item ) {
	  
				$the_query->the_post();
				$verified = get_post_meta(get_the_ID($item), 'tu_language_field_post', true);
				if(!empty($verified)){
				$diffrence=array_diff($verified,$language_delete);
				if(!empty($verified) && !empty($diffrence)){
				 $same=array_intersect($verified,$language_delete);
				 update_post_meta(get_the_ID($item), 'tu_language_field_post', $same);
				}
				}
			}
           
	    }
	  

	  
	}
	
	
 $old = array();
 $new = array();
 $pages = array();
 
 if(!empty($old_value)){
 foreach($old_value as $oldv){
    if($oldv['Default']!=1)
	array_push($old ,$oldv['LangCode']);
 }
 }
 
  foreach($value as $newv){
    if($newv['Default']!=1)
	array_push($new ,$newv['LangCode']);
 }
 
 $diffrence=array_diff($old,$new);
 
  if(!empty($diffrence)){
    foreach( $diffrence as $id){
		
		foreach($old_value as $search){
		if($search['LangCode']==$id){
		$agency = null;
		$transaltor = (int)$search['EmployeeId'];
		if($search['EmployeeId']<0){
		$agency = (int)$search['EmployeeId'];
		$transaltor = null;
		}
		$machine = (bool) $search['MachineTranslation'];
		}
		}

		
		$pagesArray = [
		"targetLanguageShort" => $id, 
		"published" => false,
		"enabled" => false,
		"pages" => array(),
		"translatorId" => $transaltor,
		"agencyId" => $agency,
		"machineTranslation" => $machine
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

      $wp_add_project = wp_remote_request(
      'http://api.textunited.com/ProjectAutomation/api/WebTranslation',
      array(
         'method'    => 'PUT',
          'headers'   => $wp_request_headers,
         'body' =>  json_encode($body),
		 'timeout'     => 30
      )
     );
	 
 
 }
 
 
 
 	if(count($old_value)==1 || count(get_option( 'TUlanguagesAdd'))==1)
	return false;
 
  $old_transaltor = array();
 $new_transaltor = array();
 $pages_transaltor = array();
 
 if(!empty($old_value)){
 foreach($old_value as $oldv){
    if($oldv['Default']!=1)
	array_push($old_transaltor ,$oldv['EmployeeId']);
 }
 }
 
  foreach($value as $newv){
    if($newv['Default']!=1)
	array_push($new_transaltor ,$newv['EmployeeId']);
 }
 
 $diffrence_transaltor=array_diff($old_transaltor,$new_transaltor);
 
    foreach( $value as $id_transaltor){
		
		unset($value[0]);
	
		$agency = null;
		$transaltor = (int)$id_transaltor['EmployeeId'];
		if($id_transaltor['EmployeeId']<0){
		$agency = (int)$id_transaltor['EmployeeId'];
		$transaltor = null;
		}
 
		
		$pagesArray = [
		"targetLanguageShort" => $id_transaltor['LangCode'], 
		"published" => true,
		"enabled" => true,
		"pages" => array(),
		"translatorId" => $transaltor,
		"agencyId" => $agency,
		"machineTranslation" => (bool)$id_transaltor['MachineTranslation']
		];
		if($id_transaltor['Default']!=1)
		array_push( $pages_transaltor,$pagesArray);
		}
		if(empty($pages_transaltor))
		return false;
	    $project_id = get_option( 'TUprojectInfo');
	 	$body = array(
		"id" => (int)$project_id->id,
        "name" => get_bloginfo(),
        "url" => get_site_url(),
		"sourceLanguageShort" => get_option( 'TUdefaultLanguage'),
		"projects" =>  $pages_transaltor,
        "comment" => "",
		"type" => "wordpress"
        );

      $wp_add_project = wp_remote_request(
      'http://api.textunited.com/ProjectAutomation/api/WebTranslation',
      array(
          'method'    => 'PUT',
          'headers'   => $wp_request_headers,
          'body' =>  json_encode($body),
		  'timeout'     => 30
      )
     );
	 
   
  
 
	}
	
	
	function TUT_custom_filter_language(){
	
	
	    global $wpdb, $table_prefix,$post_type;
		
		

            //give a unique name in the select field
            ?><select name="admin_filter_language">
<option value="">All languages</option>

                <?php 
				$filter = "";
                if(isset($_GET['admin_filter_language']))				
				$filter = sanitize_text_field($_GET['admin_filter_language']);
                $current_v = isset($filter)? $filter : '';
				if(!in_array($filter,$this->TUTlanguageArray))
				$current_v = '';
				$languages = get_option( 'TUlanguagesAdd' );
                foreach ($languages as $label => $value) {
                    printf(
                        '<option value="%s"%s>%s</option>',
                        $value['LangCode'],
                        $value['LangCode'] == $current_v? ' selected="selected"':'',
                        $value['LangCode']
                    );
                }
                ?>
            </select>
            <?php
        //}
	
	}
	
	
	function TUT_custom_filter_language_selector($query ){
	      global $pagenow;
     $filter='';		  
     if(isset($_GET['admin_filter_language']))  {
     $filter = sanitize_text_field($_GET['admin_filter_language']);
	 }
	 else{
	 return $query;
	 }
    
	if ( 
      is_admin()
      && $pagenow=='edit.php'
      && isset($filter)
      && $filter != ''
      && $query->is_main_query() && in_array($filter,$this->TUTlanguageArray)
) {	
			
$query->query_vars['meta_query'][] = array(
    'key'     => 'tu_language_field_post',
    'value'   => $filter,
    'compare' => 'LIKE'
);

}

  
	}

	
	
   function TUT_reset_plugin($old_value, $new_value, $option){
	
 $data = null; 
 if(!empty(get_option( 'TUcomapnyId' ))){
 $wp_request_headers = array(
  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
  'Content-Type'   => 'application/json',
  'x-textunited-addin' => 'wordpress'
);
$wp_request_url = 'http://159.8.238.6:32000/api/WebTranslation/url/'.urlencode(get_site_url()).'';
$wp_delete_post_response = wp_remote_request(
  esc_url($wp_request_url),
  array(
      'method'    => 'GET',
      'headers'   => $wp_request_headers,
	  'timeout'     => 30
  )
);
 
$body = wp_remote_retrieve_body( $wp_delete_post_response );

$data = json_decode( $body );
 
$projectInfo = get_option( 'TUprojectInfo');


$bodyEvent= array(
   "Type" => 3,
   "Timestamp" => date('Y-m-d\TH:i:s'),
   "Data" => get_option( 'TUcomapnyId' ),
   "Tags" => array("Hubspot", "wordpress plugin installed")

);
    $wp_add_event = wp_remote_request(
      'http://api.textunited.com/Events/api/events',
      array(
          'method'    => 'POST',
          'headers'   => $wp_request_headers,
          'body' =>  json_encode($bodyEvent)
      ) );
	  
	  
   }

if($data!=null){
 
  $proj = get_option( 'TUprojectInfo');
  update_option( 'TUprojectInfoId',$proj->id,false);  
  update_option( 'TUdefaultLanguage',$data->sourceLanguageShort,false);
  update_option( 'TUprojectInfo',$data,false);
  
  $wp_delete_get_languages = wp_remote_request(
    'https://www.textunited.com/api/languages',
    array(
        'method'    => 'GET',
        'headers'   => $wp_request_headers
    )
  );
  
  $language = wp_remote_retrieve_body( $wp_delete_get_languages );
  $languages = json_decode( $language ); 
  
   $native1 = 'none';
   $descriptive1 = 'none';
     foreach($languages as $langa){
	 if($langa->Id==$data->sourceLanguageId){
	 $native1 = $langa->NativeName;
	 $descriptive1 = $langa->DescriptiveName;
	 }
	 }
  
  $addLangs = array(['LangCode'=>$data->sourceLanguageShort,'DescriptiveName'=> $descriptive1,'SystemName' =>$native1,
  'Order' => 0,'Default'=>1,'Id'=>$data->sourceLanguageId]);
   $order = 1;
   $native = 'none';
   $descriptive = 'none';
   if(!empty($data->projects)){
   foreach($data->projects as $adds){
     foreach($languages as $lang){
	 if($lang->Id==$adds->targetLanguageId){
	 $native = $lang->NativeName;
	 $descriptive = $lang->DescriptiveName;
	 }
	 
	 }
   
   if($adds->agencyId!=null)
   $employee = -1;
   else
   $employee = (int) $adds->translatorId;
    if($adds->enabled==true){  
    array_push($addLangs,array('MachineTranslation'=>(bool)$adds->machineTranslation,'LangCode'=>$adds->targetLanguageShort,'DescriptiveName'=> $descriptive,'SystemName' =>$native,
  'Order' =>  $order,'Default'=>0,'Id'=>$adds->targetLanguageId,'EmployeeId'=>$employee));
   }
   $order++;
  }}
  
  
  		 $pages = array(
	      'post_type'    => 'any',
	      'orderby'      => 'menu_order',
	      'posts_per_page' => -1,
          'fields' => 'ids',
		  'post_status' => 'publish' 
		);

			$categories = get_categories( array(
			  'orderby' => 'name',
			  'order'   => 'ASC'
			) );

			$tags = get_tags();
            $the_query = new WP_Query( $pages );
 
				if ( $the_query->have_posts() ) {
		
							foreach ( $the_query as $item ) {
								$the_query->the_post();
								
								
								$languageArray=array($data->sourceLanguageShort);
								update_post_meta( get_the_ID($item), 'tu_language_field_post',$languageArray);
								  foreach($data->projects as $infoObject){
             
									  if(!empty($infoObject->pages)){
									  foreach($infoObject->pages as $pagesObject){
											   
											 if(get_the_permalink( get_the_ID($item) ) == get_option('siteurl').$pagesObject->url){
													array_push($languageArray,$infoObject->targetLanguageShort);
													update_post_meta( get_the_ID($item), 'tu_language_field_post',$languageArray);
													update_post_meta( get_the_ID($item), 'tu_language_field_post_url',get_permalink(get_the_ID($item)));
											 
											 }
											
											}
											}
									  
									  
									  }

								
								}
								
						}
			            update_option( 'TUlanguagesAdd',$addLangs,false);	  
  
   }else{
   
     update_option( 'TUdefaultLanguage',"",false);
     update_option( 'TUprojectInfo',"",false);
     update_option( 'TUlanguagesAdd',"",false);
	 update_option( 'TUprojectInfoId',"",false);  
   
     	 $pages = array(
	    'post_type'    => 'any',
	    'orderby'      => 'menu_order',
	    'posts_per_page' => -1,
        'fields' => 'ids',
        'post_status' => 'publish' 
	);
	$the_query = new WP_Query($pages  );

		if ( $the_query->have_posts() ) {
			foreach ( $the_query as $item ) {
	  
				$the_query->the_post();
				 
				 update_post_meta(get_the_ID($item), 'tu_language_field_post', "");
 
				
			}
			
		}   
   
   
   
    
   }

}	
	
		function TUT_custom_media_add_media_custom_field( $form_fields, $post ) {
			$field_value = get_post_meta( $post->ID, 'textunited_url', true );

			$form_fields['textunited_url'] = array(
				'value' => $field_value ? $field_value : '',
				'label' => __( 'Translation Source URL:' ),
				'helps' => __( 'Enter an URL of a file used in the source language version.' ),
				'input'  => 'textarea'
			);

			return $form_fields;
		}
	
	 

//save your custom media field
	function TUT_custom_media_save_attachment( $attachment_id ) {
		if ( isset( $_REQUEST['attachments'][ $attachment_id ]['textunited_url'] ) ) {
			$custom_media_style = $_REQUEST['attachments'][ $attachment_id ]['textunited_url'];
			update_post_meta( $attachment_id, 'textunited_url', $custom_media_style );

		}
	}


    function TUT_adding_url_meta_rest() {
        register_rest_field( 'attachment',
            'textunited_url',
            array(
                'get_callback'      => 'Text_United_Translation_Admin::TUT_url_meta_callback',
                'update_callback'   => null,
                'schema' => array(
            
            'type'        => 'string'
        ),
            )
        );
    }    
 
   public static function TUT_url_meta_callback( $post, $field_name, $request) {
       $field_value = get_post_meta( $post['id'], 'textunited_url', true );
	   return $field_value ;
    }
	
	
	function TUT_rest_url_filters($args, $request) {
		  $you_categorie_name = $request->get_param('textunited_url');
	  
		   if (!empty($you_categorie_name)) {
			   
			  $args['meta_key'] = 'textunited_url';
			  $args['meta_value'] = sanitize_text_field($you_categorie_name);
			  $args['meta_compare'] = '=';
		   }
		  return $args;
		}

}
