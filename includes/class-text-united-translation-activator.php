<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.textunited.com/
 * @since      1.0.0
 *
 * @package    Text_United_Translation
 * @subpackage Text_United_Translation/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Text_United_Translation
 * @subpackage Text_United_Translation/includes
 * @author     Text United <servus@textunited.com>
 */
class Text_United_Translation_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	    $args2 = array(
			//'post__not_in' => $inclPages,
			'post_type'    => 'any',
			'orderby'      => 'menu_order',
			'posts_per_page' => 99
		  );
		  
		  $categories = get_categories( array(
			'orderby' => 'name',
			'order'   => 'ASC'
		  ) );
		  
		  $the_query = new WP_Query( $args2 );
		  $defaultLanguage = get_option( 'TUdefaultLanguage');
		 
		  // The Loop
		  if ( $the_query->have_posts() ) {
			  while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$language = get_post_meta(get_the_ID(), 'tu_language_field_post',true );
				if(empty($language))
				update_post_meta(get_the_ID(), 'tu_language_field_post','');
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
		update_option( 'TUcustomTypes', $array);
			
        
        add_option( 'TUtoken', '', '', 'yes' );
		add_option( 'TUlanguagesAdd', '', '', 'yes' );
		add_option( 'TUdefaultLanguage', '', '', 'yes' );
		add_option( 'TUprojectInfo', '', '', 'yes' );
	}

}
