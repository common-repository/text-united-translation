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
$active = '';
$active1 = 'none';
$active2 = 'none';
$active3 = 'none';
$active5 = '';
$active6 = '';
$active7 = '';
$active8 = '';
$active33 = '';
$languageAdd = '';
$languageList = '';
$disabled = '';
$media = '';

if(!isset($_GET['module']))
$active2 = 'active';


if(isset($_GET['module']) && (int)$_GET['module']==1)
$active = 'active';

if(isset($_GET['module']) && (int)$_GET['module']==2) 
$active2 = 'active';

if(isset($_GET['module']) && (int)$_GET['module']==2) 
$active2 = 'active';

if(isset($_GET['module']) && (int)$_GET['module']==6)
$media = 'active';

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

if(isset($_GET['sub']) && (int)$_GET['sub']==1 )
$active5 = 'activeSub';

if(isset($_GET['sub']) && (int)$_GET['sub']==2 )
$active6 = 'activeSub';

if(isset($_GET['sub']) && (int)$_GET['sub']==3 )
$active7 = 'activeSub';

?>
<style>
.tab-pane p{font-size: 18px;
font-weight: 300;}
</style>


<div class="container">


<div class="row" style="margin:74px 80px 0px 10px">
  <div class="col col-sm-12 col-md-12 col-lg-3" style="padding-right:0px">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
       <div class="<?php echo $active2;?>"><a class="nav-link" id="v-pills-section-1" href="<?php echo esc_url(admin_url().'admin.php?page=text-united-translation%2Ffaq.php&module=2'); ?>">How to use?</a></div>
       <div class="<?php echo $active3;?>"><a  <?php echo $disabled ?> class="nav-link" id="v-pills-section-2" href="<?php echo esc_url(admin_url().'admin.php?page=text-united-translation%2Ffaq.php&module=3&sub=1'); ?>">Starting a Project</a></div>
	   <div class="sumbs <?php echo $active4;?> ">
	      <a class="mt-2 <?php echo $active5;?>" href="<?php echo esc_url(admin_url().'admin.php?page=text-united-translation%2Ffaq.php&module=3&sub=1'); ?>">Starting a Project</a>
		   
		    
	   </div>
	   <div class="<?php echo $media;?>"><a class="nav-link" id="v-pills-section-6" href="<?php echo esc_url(admin_url().'admin.php?page=text-united-translation%2Ffaq.php&module=6'); ?>">Media localization</a></div>
     
    </div>
  </div>
  <div class="col col-sm-12 col-md-12 col-lg-9 contB" style="background:#ffffff">
    <div class="tab-content" id="v-pills-tabContent">
	<div style="font-size:14px" class="tab-pane  <?php echo $active2;?>" id="v-pills-section-1" role="tabpanel" aria-labelledby="v-pills-section-1">
	<h5 class="mt-3" style="color:#5956db;margin-top:10px;font-size: 1rem;">How to use?</h5>
	<hr class="bars"/>
     <p>To start using the plugin you need to connect it with an account on Text United. </p>
	 <p>Text United offers a range of pricing options, including a free version for one target language as well as a 14-day free trial for more advanced installations (current pricing <a href="https://www.textunited.com/pricing/" target="_blank">here</a>). </p>
	 <p>If you are new to Text United, simply provide your email address directly in the plugin and follow instructions.</p>
	 <p>We will automatically create your Text United account and start a 14-day free trial. After 14 days, unless you subscribe, it will switch to a free edition which allows you to continue using the plugin for one target language for free. </p>
	 <p>If you already have a Text United account, login to Text United and: </p>
	 <ul style="list-style:square;margin-left:30px;font-size:14px">
	    <li>Copy your Company ID and API key from <a href="https://www.textunited.com/my/extras/api/" target="_blank">https://www.textunited.com/my/extras/api/</a></li>
		<li>Paste it into the <b>My account</b> section in the <b>Settings</b>. </li>
	 </ul>
    <p>You should see a message "You have successfully connected to Text United."</p>	 
	<p>Hurray! Now you can add the first new language to your website. Navigate to “Starting a project” section to check out the next steps</p>	 
	</div>
	
  
	 
      
      <div style="margin-bottom:50px" class="tab-pane  <?php echo $active33;?>" id="v-pills-section-2" role="tabpanel" aria-labelledby="v-pills-section-2">
	  
	  	<h5 class="mt-3" style="color:#5956db;margin-top:10px;font-size: 1rem;">Starting a Project</h5>
	    <hr class="bars"/>

         	 <p>First, you must tell the system which is the source language of your website: </p>
	 <ul style="list-style:square;margin-left:30px;font-size:14px">
	    <li>Navigate to <b>Language Settings</b></li>
		<li>In the <b>Default Language</b> select the source language (the original language of your website)  </li>
	 </ul>
	   <p>Second, you must tell the system which is the target language(s) of your website. These are the languages your website will be translated into: </p>
	   	 <ul style="list-style:square;margin-left:30px;font-size:14px">
	    <li>In <b>Language List & Translators</b> select a target language(s) </li>
	 </ul>
	 <p>At this step, you can also decide who will translate your website.<br />
You can choose between yourself, a translator from your team (if you added one in your Text United account) or you can send the translation to Text United professional service, which is a paid option. </p>
	  <ul style="list-style:square;margin-left:30px;font-size:14px">
	    <li>Choose who will translate from <b>Select a translator</b> dropdown menu. </li>
	 </ul>
	 <p>Finally: </p>
	 <ul style="list-style:square;margin-left:30px;font-size:14px">
	    <li>click <b>Add a language</b>.</li>
	 </ul>
	 <p>The system will start adding your language as a new project and machine translate it if the option was chosen. The option will pre-translate your website using Google translate as the default choice. The translation can be later modified by the selected translator.</p>
     <p>Important:<br /> A free trial has a limited number of words which can be pre-translated by a machine. However, you can purchase additional packages of words. Check your current words allowance <a href="https://www.textunited.com/my/account/machinetranslation" target="_blank">here</a>. You can buy an extra package anytime <a href="https://www.textunited.com/my/account/machinetranslation/" target="_blank">here</a>. </p>	 

	 </div>
	  

 
     <div class="tab-content" id="v-pills-tabContent">
	<div style="font-size:14px" class="tab-pane  <?php echo $media;?>" id="v-pills-section-6" role="tabpanel" aria-labelledby="v-pills-section-6">
	<h5 class="mt-3" style="color:#5956db;margin-top:10px;font-size: 1rem;">Media localization in Text United Wordpress plugin</h5>
	<hr class="bars"/>
     <p><b>How to change images and media files into translated versions using TextUnited WordPress plugin</b></p>
	 <p>Upload the translated version of your image or a PDF file to the WordPress media library. </p>
	 <?php echo '<img src="'.esc_url(plugins_url('img/media1.png', __FILE__ )).'" width="447" class="mr-3"/>'; ?><br />	<br />	
	 <p>The file name should be same as the source file with added language code after a dot.</p>
	 <p>For example, if you have a translated image into French (Canada), the file name should include</p>
	 <p>".fr-ca": </p>
	 <ul style="list-style:square;margin-left:30px;font-size:14px">
	    <li>source file name: image.jpeg </li>
		<li>translated file name: image.fr-ca.jpeg </li>
	 </ul>
    <p>Open the source version of the image and copy its URL. </p>
    <?php echo '<img src="'.esc_url(plugins_url('img/media2.png', __FILE__ )).'" width="433" class="mr-3"/>'; ?><br /><br />		
	<p>Open the target language version of the image and paste the URL into "Translation Source URL" field. </p>	 
	</div>
 
 
    </div>
    </div>
  </div>
</div>

<?php wp_reset_postdata(); ?>