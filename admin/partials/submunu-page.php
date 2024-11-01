<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.textunited.com/
 * @since      1.0.0
 *
 * @package    Text_United_Translation_Plugin
 * @subpackage Text_United_Translation_Plugin/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php 

	$wp_request_headers = array(
	  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
	  'Content-Type'   => 'application/json',
	  'x-textunited-addin' => 'wordpress'
	);
	$wp_request_url = 'https://www.textunited.com/api/employees';
		$wp_delete_post_response = wp_remote_request(
		  $wp_request_url,
			 array(
				  'method'    => 'GET',
				  'headers'   => $wp_request_headers,
				  'timeout'     => 30
			  )
	);

	$wp_delete_get_languages = wp_remote_request(
		'https://www.textunited.com/api/languages',
		array(
			'method'    => 'GET',
			'headers'   => $wp_request_headers,
			'timeout'     => 30
		)
	  );

 
	$body = wp_remote_retrieve_body( $wp_delete_post_response );
	$language = wp_remote_retrieve_body( $wp_delete_get_languages );
	$data = json_decode( $body );
	$languages = json_decode( $language ); 

 
	$active1 = 'none';
	$active2 = 'none';
	$active3 = 'none';
	$active5 = '';
	$active4 = '';
	$active6 = '';
	$active7 = '';
	$active8 = '';
	$active33 = '';
	$languageAdd = '';
	$languageList = '';
	$team = '';
	$disabled = 'disabled="disabled"';
if(wp_remote_retrieve_response_code( $wp_delete_post_response ) == 200){
$disabled = '';
}
$disabled2 = 'disabled="disabled"';
if(!empty(get_option( 'TUdefaultLanguage')))
$disabled2 = '';

if(empty(get_option( 'TUdefaultLanguage')) && isset($_GET['module']) && (int)$_GET['module']==3 && isset($_GET['sub']) && (int)$_GET['sub']==2){
 echo '<script>location.href = "'.admin_url().'admin.php?page=text-united-translation%2Fimporter.php&module=3&sub=1"</script>';
exit;
}

if(!isset($_GET['module']))
$active2 = 'active';


if(isset($_GET['module']) && (int)$_GET['module']==1)
$active2 = 'active';

if(isset($_GET['module']) && (int)$_GET['module']==2) 
$active2 = 'active';

if(isset($_GET['module']) && (int)$_GET['module']==3 && isset($_GET['sub']) && (int)$_GET['sub']==1) {
$active3 = 'active';
$active33 = 'active';
$active4 = 'active2';

}

if(isset($_GET['module']) && (int)$_GET['module']==3 && isset($_GET['sub']) && (int)$_GET['sub']==2) {
$active3 = 'active';
$active4 = 'active2';
$languageAdd = 'active';
}

if(isset($_GET['module']) && (int)$_GET['module']==3 && isset($_GET['sub']) && (int)$_GET['sub']==3) {
$active3 = 'active';
$active4 = 'active2';
$languageList = 'active';
}

if(isset($_GET['module']) && (int)$_GET['module']==3 && isset($_GET['sub']) && (int)$_GET['sub']==4) {
$active3 = 'active';
$active4 = 'active2';
$team = 'active';
}

if(isset($_GET['sub']) && (int)$_GET['sub']==1 )
$active5 = 'activeSub';

if(isset($_GET['sub']) && (int)$_GET['sub']==2 )
$active6 = 'activeSub';

if(isset($_GET['sub']) && (int)$_GET['sub']==3 )
$active7 = 'activeSub';
if(isset($_GET['sub']) && (int)$_GET['sub']==4 ){
$active8 = 'activeSub';
$team = 'active';
}

$disabled2 = 'disabled="disabled"';
if(!empty(get_option( 'TUdefaultLanguage')))
$disabled2 = '';

settings_errors('TUT_companuy_info_error');
?>

<div class="container">
  <div class="row ">
    <div class="col col-sm-12 col-lg-12 col-md-12">
<h1 class="mt-3">Settings</h1>
</div>
</div>

<div class="row" style="margin:0px 80px 0px 10px">
  <div class="col col-sm-12 col-md-12 col-lg-3" style="padding-right:0px">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
       <div class="<?php echo esc_attr($active2);?>"><a class="nav-link" id="v-pills-profile-tab" href="<?php echo esc_url(admin_url().'admin.php?page=text-united-translation%2Fimporter.php&module=2'); ?>">My Account</a></div>
       <div class="<?php echo esc_attr($active3);?>"><a  <?php echo $disabled ?> class="nav-link" id="v-pills-messages-tab" href="<?php echo esc_url(admin_url().'admin.php?page=text-united-translation%2Fimporter.php&module=3&sub=1'); ?>">Language Settings</a></div>
	   <div class="sumbs <?php echo $active4;?> ">
	      <a class="mt-2 <?php echo esc_attr($active5);?>" href="<?php echo esc_url(admin_url().'admin.php?page=text-united-translation%2Fimporter.php&module=3&sub=1'); ?>">Default Language</a>
		   <a <?php echo $disabled2 ?> class="mt-2 <?php echo esc_attr($active6);?>" href="<?php echo esc_url(admin_url().'admin.php?page=text-united-translation%2Fimporter.php&module=3&sub=2'); ?>">Languages List & Translators</a>
		    <a <?php echo $disabled2 ?> class="mt-2 <?php echo esc_attr($active7);?>" href="<?php echo esc_url(admin_url().'admin.php?page=text-united-translation%2Fimporter.php&module=3&sub=3'); ?>">Languages Selector</a>
			
	   </div>
	   
     
    </div>
  </div>
  <div class="col col-sm-12 col-md-12 col-lg-9 contB" style="background:#ffffff">
    <div class="tab-content" id="v-pills-tabContent">
	<div class="tab-pane  <?php echo $active2;?>" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
	<?php if(wp_remote_retrieve_response_code( $wp_delete_post_response ) == 200){ ?>
	<h4 style="color:#5956db;margin-top:10px;">My Account</h4>
	<?php }else{?>
	<h5 class="mt-3" style="color:#5956db;margin-top:10px;font-size: 1rem;">Company information from Text United</h5>
	<?php } ?>
	<hr class="bars"/>
	 <form method="post" action="options.php">
        <?php
        settings_fields( 'TUT_customsettings' );
        do_settings_sections('TUT_customsettings' );
		 
		echo '<input name="TUtoken" value="'.sha1(time()).'" type="hidden" />';
        ?>
        <?php
		 if(get_option( 'TUcomapnyId' ) && wp_remote_retrieve_response_code( $wp_delete_post_response ) == 200){
            echo '<div class="alert alert-success" role="alert"><img src="'.esc_url(plugins_url('img/text_united_logo.png', __FILE__ )).'" width="180" class="mr-1"/> You are connected to Text United</div>';
        }else{
		    if(empty(get_option( 'TUcomapnyId' )) && empty(get_option( 'TUapiKey' )))
			echo '<div class="alert alert-danger" role="alert">Please enter your <b>Company ID</b> and <b>API key</b></div>';
			else
            echo '<div class="alert alert-danger" role="alert">The authorization data is incorrect</div>';
        }
		?>
        <div class="form-group">
            <label for="TUcomapnyId">Your company ID:</label>
            <input style="background:#eeeefb;border:1px solid #dddde3;" type="text" name="TUcomapnyId" value="<?php echo get_option( 'TUcomapnyId' ) ?>" class="form-control" id="TUcomapnyId" placeholder="Your company ID">
        </div>

        <div class="form-group">
            <label for="TUcomapnyId">Your API key:</label>
            <input style="background:#eeeefb;border:1px solid #dddde3;" type="text" name="TUapiKey" value="<?php echo get_option( 'TUapiKey' ) ?>" class="form-control" id="TUapiKey" placeholder="Your API key">
        </div><div class="form-group text-right">
        <button type="submit" class="btn btn-primary mb-2 pl-5 pr-5">SAVE</button>
		</div>
        </form>
	
	</div>
	<?php if(wp_remote_retrieve_response_code( $wp_delete_post_response ) == 200){ ?>
  
	 
      
      <div style="margin-bottom:50px" class="tab-pane  <?php echo $active33;?>" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
	  
	  	<h5 class="mt-3" style="color:#5956db;margin-top:10px;font-size: 1rem;">Default Website Language</h5>
	    <hr class="bars"/>
		<p>What is the original (source) language of your website?</p>
	        <form method="post" action="options.php">
            <?php
			 settings_fields( 'TUT_languagesettingsDefaultMain' );
             do_settings_sections('TUT_languagesettingsDefaultMain' );
			?>

      <div class="form-group">
      <input type="hidden" id="TUdefaultLanguageLangCode" name="TUdefaultLanguageMain[LangCode]"  value="<?php echo esc_attr(get_option( 'TUdefaultLanguage')); ?>" >
            <input type="hidden" id="TUdefaultLanguageDescriptiveName" name="TUdefaultLanguageMain[DescriptiveName]"  value="" >
            <input type="hidden" id="TUdefaultLanguageSystemName" name="TUdefaultLanguageMain[SystemName]"  value="" >
            <input type="hidden"  name="TUdefaultLanguageMain[Order]"  value="" >
            <input type="hidden"  name="TUdefaultLanguageMain[Default]"  value="1" >
			<input type="hidden"  name="TUdefaultLanguageMain[EmployeeId]"  value="" >
			<input type="hidden"  name="TUdefaultLanguageMain[MachineTranslation]"  value="0" >
            <select style="visibility:hidden;width:100%" class="form-control" name="TUdefaultLanguageMain[Id]" id="TUdefaultLanguage">
			<option></option>
            <?php 
               $selected = get_option( 'TUdefaultLanguage' );
               foreach($languages as $lang){
                echo'<option system="'.esc_attr($lang->NativeName).'" ';if( $selected == $lang->LangCode){echo 'selected="selected';} echo 'title="'.esc_attr($lang->LangCode).'" value="'.esc_attr($lang->Id).'">'.esc_html($lang->DescriptiveName).'</option>';
               }

            ?>
            </select>
        </div>
<div class="form-group text-right">
      <button type="submit" class="btn btn-primary mb-2 pl-5 pr-5">SAVE</button>
	  </div>
      </form>
	  
	  
	  </div>
	  
	  
	  
	  <div class="tab-pane show <?php echo $languageList ;?>" id="v-pills-home2" role="tabpanel" aria-labelledby="v-pills-home-tab">
	    <h5 class="mt-3" style="color:#5956db;margin-top:10px;font-size: 1rem;">Languages Selector</h5>
		<hr class="bars"/>
      <form method="post" action="options.php">
    
        <?php
        settings_fields( 'TUT_languagesettings' );
        do_settings_sections('TUT_languagesettings' );
        $select1 = get_option( 'TUlanguageSelectorContent' );
        $select2 = get_option( 'TUlanguageSelectorType' );
		$select3 = get_option( 'TUshortcode' );
		$select4 = get_option( 'TUflagShape' );
        ?>
      <div class="form-group">
      <label>Language selector content</label>
      <select class="form-control" style="max-width:100%" required name="TUlanguageSelectorContent" >
        <option <?php if($select1==1){echo 'selected="selected"';} ?> value="1">Flags</option>
        <option <?php if($select1==2){echo 'selected="selected"';} ?> value="2">Language name</option>
        <option <?php if($select1==3){echo 'selected="selected"';} ?> value="3">Flags with language name</option>
       </select>
      </div>
	  	<div class="form-group pb-3">
		<?php if(empty($select4)){
          echo '<input type="checkbox" class="form-check-input mt-2" name="TUflagShape" id="TUflagShape">';
		  }else{
		   echo '<input checked type="checkbox" class="form-check-input mt-2" name="TUflagShape" id="TUflagShape">';
		  }?>
          <label class="form-check-label pl-4" for="TUflagShape">Round flag shape</label>
        </div>
		<hr />
      <div class="form-group pt-3"  >
      <label>Language selector type</label>
      <select class="form-control" style="max-width:100%" required name="TUlanguageSelectorType" >
        <option <?php if($select2==1){echo 'selected="selected"';} ?> value="1">Position in main menu</option>
        <option <?php if($select2==2){echo 'selected="selected"';} ?> value="2">Widget right</option>
        <option <?php if($select2==3){echo 'selected="selected"';} ?> value="3">Widget left</option>
		<option <?php if($select2==4){echo 'selected="selected"';} ?> value="4">Widget dropup right</option>
		<option <?php if($select2==5){echo 'selected="selected"';} ?> value="5">Widget dropup left</option>
		<option <?php if($select2==6){echo 'selected="selected"';} ?> value="6">Widget dropdown right</option>
		<option <?php if($select2==7){echo 'selected="selected"';} ?> value="7">Widget dropdown left</option>
       </select>
      </div>
	  <p>If you select langage selector type as "Position in main menu" change your main menu name to "Primary" for mobile version "PrimaryMobile"</p>
	    <div class="form-group">
		<?php if(empty($select3)){
          echo '<input type="checkbox" class="form-check-input mt-2" name="TUshortcode" id="shortCode">';
		  }else{
		   echo '<input checked type="checkbox" class="form-check-input mt-2" name="TUshortcode" id="shortCode">';
		  }?>
          <label class="form-check-label pl-4" for="shortCode">Display as shortcode</label>
        </div>
		<p>You can add [textunited_selector] shortcode wherever you want in theme.</p>
     <div class="form-group text-right">
      <button type="submit" class="btn btn-primary mb-2 pl-5 pr-5 mb-ms-auto">SAVE</button>
	  </div>
      </form>
	  </div>
	  
	  <?php if(!empty(get_option( 'TUlanguagesAdd' ))){?>
	  	  <div class="tab-pane show <?php echo $languageAdd ;?>" id="v-pills-home3" role="tabpanel" aria-labelledby="v-pills-home-tab">
	    <h5 class="mt-3" style="color:#5956db;margin-top:10px;font-size: 1rem;">Languages List & Translators</h5>
		<hr class="bars"/>
		<p>Choose languages you want to translate into.</p>
      <form class="form-inline mb-2 formAdd" method="post" action="options.php">
      <?php settings_fields( 'TUT_languageArrayAdd' );
        do_settings_sections('TUT_languageArrayAdd' );
	   ?>
	  							

			
      <div class="form-group " style="width:100%">
            <input type="hidden" id="TUlanguagesAddLangCode" name="TUlanguagesAddNew[LangCode]"  value="" >
            <input type="hidden" id="TUlanguagesAddDescriptiveName" name="TUlanguagesAddNew[DescriptiveName]"  value="" >
            <input type="hidden" id="TUlanguagesAddSystemName" name="TUlanguagesAddNew[SystemName]"  value="" >
            <input type="hidden"  name="TUlanguagesAddNew[Order]"  value="" >
            <input type="hidden"  name="TUlanguagesAddNew[Default]"  value="0" >
            <select required style="visibility:hidden;background:#eeeefb;max-width:280px" class="form-control xs-mx-auto xs-mb-2" name="TUlanguagesAddNew[Id]" id="TUlanguagesAdd">
            <option></option>
            <?php 
         
               $selected = get_option( 'TUlanguagesAdd' );

               foreach($languages as $k=>$lang){
               foreach($selected as $option){
                if($option['Id'] == $lang->Id) 
                unset($languages[$k]);
              }
            }


               foreach($languages as $k=>$lang){
               echo'<option system="'.esc_attr($lang->NativeName).'" title="'.esc_attr($lang->LangCode).'" value="'.esc_attr($lang->Id).'">'.esc_attr($lang->DescriptiveName).'</option>';
              }

            ?>
            </select>
			<select style="max-width:180px" required class="form-control ml-2" name="TUlanguagesAddNew[EmployeeId]">
            <option value="">Select a translator</option>
			
            <?php 
         
               $selectedE = $data;
               foreach($selectedE as $k=>$employee){
               echo'<option value="'.esc_attr($employee->Id).'">'.esc_html($employee->FirstName).' '.esc_html($employee->LastName).'</option>';
              }

            ?>
			<option value="-1">Text United professional services</option>
            </select>
       

      <button type="submit" class="btn btn-primary ml-auto pl-4 pr-4 mb-ms-auto"><?php _e('Add a language','text-united-translation'); ?></button>
					<label for="wporg_field" class="alignleft pb-3 pt-3">
					<input checked="checked" style="margin-top: 1px;" id="TUlanguagesAddMachineTranslation" type="checkbox" name="TUlanguagesAddNew[MachineTranslation]"/>
					<span class="checkbox-title">Pretranslate with machine translation</span>
					</label>
      </div>
      </form>
 


      <form method="post" action="options.php">
      <?php
        settings_fields( 'TUT_languageArray' );
        do_settings_sections('TUT_languageArray' );
      
        ?>

      <div class="form-group">
           
            <ul id="my-list" class="list-group">
            <?php 
               $selected = get_option( 'TUlanguagesAdd' );
              
               foreach($selected as $k=>$select){
                $langArray = explode('-',  $select['LangCode']);
				 $specialFlags = array('NL-BE','EN-CA','FR-BE','FR-CA','GL-GL','GN-GN','KN-KN','KI-KI','LA-LA','MS-MS','ML-ML','MR-MR','MX-MX','MO-MO','NE-NE','OM-OM','PS-PS','PA-PA','SA-SA','ST-ST','SR-SR','SD-SD','SL-SL','ES-AR','ES-DO','SV-SV','TT-TT','BO-BO','TK-TK','VI-VI');
				 if(!in_array($select['LangCode'],$specialFlags)) 
                 $langArray = strtolower($langArray[1]);
				 else
				 $langArray = strtolower($select['LangCode']);
			   
                if($select['Default']==1)
				$notransalte="display:none;";
				else
				$notransalte="";
				
               echo '<li style="align-items: center;
               display: flex;margin-bottom:0" class="list-group-item">
               <input type="hidden"  name="TUlanguagesAdd['.$k.'][LangCode]"  value="'.esc_attr($select['LangCode']).'" />
               <input type="hidden"  name="TUlanguagesAdd['.$k.'][DescriptiveName]"  value="'.esc_attr($select['DescriptiveName']).'" />
               <input type="hidden"  name="TUlanguagesAdd['.$k.'][Default]"  value="'.esc_attr($select['Default']).'" />
               <input type="hidden"   name="TUlanguagesAdd['.$k.'][Id]"  value="'.esc_attr($select['Id']).'" />
				
               <span class="flag-icon flag-icon-'.$langArray.' mr-3"></span> <span class="deviderTu"></span>
               <input class="form-control ml-3 mr-3" style="max-width:150px" type="text"  name="TUlanguagesAdd['.$k.'][SystemName]"  value="'.$select['SystemName'].'" />
			   <span style="'.$notransalte.'" class="deviderTu"></span>
			   <select '; if($select['Default']!=1) {echo'required="required"';} echo' style="'.$notransalte.' max-width:150px" class="form-control ml-3 mr-3" name="TUlanguagesAdd['.$k.'][EmployeeId]">
               <option value="">Select a translator</option>';
			  
               $selectedE = $data;
               foreach($selectedE as $employee){
               echo '<option ';if(!empty($select['EmployeeId']) && $select['EmployeeId']==$employee->Id){echo 'selected="selected" ';} echo 'value="'.$employee->Id.'">'.$employee->FirstName.' '.$employee->LastName.'</option>';
              }
			    echo '<option ';if(!empty($select['EmployeeId']) && $select['EmployeeId']<0){echo 'selected="selected" ';} echo 'value="-1">Text United professional services</option>';
              echo ' </select>
			    <span style="'.$notransalte.'" class="deviderTu"></span>
						<label style="'.$notransalte.';font-size:11px;line-height:50%" for="wporg_field" class="alignleft pb-1 mt-3 ml-3 ">
						<span class="checkbox-title">Machine<br />translation</span>';
					if(!empty($select['MachineTranslation']) && (bool)$select['MachineTranslation'] )	
					echo '<input checked="checked" class="mb-2 ml-2" type="checkbox" name="TUlanguagesAdd['.$k.'][MachineTranslation]"   />';
					else
					echo '<input class="mb-2 ml-2" type="checkbox" name="TUlanguagesAdd['.$k.'][MachineTranslation]"/>';
					
					echo'</label>
               ';
               if($select['Default']!=1)
               echo '<span class="btn btn-danger ml-auto  deleteList"><span class="dashicons dashicons-trash"></span></span>';
               if($select['Default']!=1)
               echo '<span style="cursor: all-scroll;" class=" ml-3 btn btn-info sor"><span class="dashicons dashicons-move"></span></span>';
			   else
			   echo '<span style="cursor: all-scroll;" class=" ml-auto btn btn-info sor"><span class="dashicons dashicons-move"></span></span>';

              echo' </li>';
              
              }
         
            ?>
           </ul>
        </div>

     <div class="form-group text-right">
         <span id="saveButtonTU" style="color:red;display:none">Click SAVE to submit changes.&nbsp;&nbsp;</span>
         <button type="submit" class="btn btn-primary mb-2 pl-5 pr-5">SAVE</button>
		</div>
      </form>
	  </div>
	   <?php } ?>
	  
	  
	   <?php } ?>
    </div>
    </div>
  </div>
</div>


<?php wp_reset_postdata(); ?>

