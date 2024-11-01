<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.textunited.com/
 * @since      1.0.0
 *
 * @package    Text_United_Translation
 * @subpackage Text_United_Translation/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php 
if(isset($_POST['disconnect'])){
	 update_option( 'TUcomapnyId',"");
	 update_option( 'TUapiKey',"");
	 update_option( 'TUtoken',"");
}

$wp_request_headers2 = array(
  'Authorization' => 'Basic ' . base64_encode( ''.get_option( 'TUcomapnyId' ).':'.get_option( 'TUapiKey' ).'' ),
  'Content-Type'   => 'application/json',
  'x-textunited-addin' => 'wordpress'
);
$wp_request_url = 'https://www.textunited.com/api/employees';
$wp_delete_post_response = wp_remote_request(
  $wp_request_url,
  array(
      'method'    => 'GET',
      'headers'   => $wp_request_headers2,
	  'timeout'     => 30
  )
);



if(isset($_POST['TuRegister'])){

$wp_request_headers = array(
  'Authorization' => 'Basic ' . base64_encode( '15:ed92abfc-9196-42e6-9613-64b04a20cdbc' ),
  'Content-Type'   => 'application/json',
  'x-textunited-addin' => 'wordpress'
);
             $post_name= sanitize_text_field($_POST[ 'name' ]);
			 $last_name = sanitize_text_field($_POST[ 'lname' ]);
			 $post_choose = sanitize_text_field($_POST[ 'choose' ]);
			 $post_email = sanitize_email($_POST[ 'email' ]);
      		if(isset( $post_name ) and strlen($post_name)>1 ) 
                $name = $post_name;
            else 
                $error['name'] = 'First name required';
				
      		if(isset( $last_name ) and strlen($last_name)>1 ) 
                $lname = $last_name;
            else 
                $error['lname'] = 'Last name required';
				
			if(isset( $post_choose ) and strlen($post_choose)>8 ) 
                $choose = $post_choose;
            else 
                $error['choose'] = 'Password required minimum 8 characters ';
				
				
			if (isset( $post_email) and  preg_match( '/^[a-zA-Z][a-zA-Z0-9_.-]+@[a-zA-Z0-9][a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $post_email ) )
            {
                $email = $post_email;
 
            }
            else
                $error['email'] = 'Email is not valid';
        if(!isset($error)){				
        $body = array(
		     "FirstName" =>(string)$name,
			 "LastName" =>(string)$lname,
		     "Password" =>(string)$choose,
			 "Email" =>(string)$email,
		     "Key" => (string)"6011b866-3c94-402b-8ccb-806861602fef-baae14d4-8c97-40ca-8452-d89fe0ba327b" 
		);

      $wp_add_project = wp_remote_request(
      'https://www.textunited.com/api/Signup',
      array(
          'method'    => 'POST',
          'headers'   => $wp_request_headers,
          'body' =>  json_encode($body),
		  'timeout'     => 30
      )
     );
	 
   
    $project = wp_remote_retrieve_body( $wp_add_project );
    $project = json_decode( $project );
   
	if(!empty($project->companyId) && !empty($project->apikey)){

	 $wp_request_headers_api = array(
		  'Authorization' => 'Basic ' . base64_encode( ''.$project->companyId.':'.$project->apikey.'' ),
		  'Content-Type'   => 'application/json',
		  'x-textunited-addin' => 'wordpress'
		);
	
	
    $bodyEvent= array(
    "Type" => 3,
    "Timestamp" => date('Y-m-d\TH:i:s'),
    "Data" => $project->companyId,
    "Tags" => array("Hubspot", "wordpress plugin installed"));
   
   
    $wp_add_event = wp_remote_request(
      'http://api.textunited.com/Events/api/events',
      array(
          'method'    => 'POST',
          'headers'   => $wp_request_headers_api,
          'body' =>  json_encode($bodyEvent),
		  'timeout'     => 30
      ) );
	
	 update_option( 'TUcomapnyId',$project->companyId);
	 update_option( 'TUapiKey',$project->apikey);
	 update_option( 'TUtoken',sha1(time()));
     echo '<script>location.href = "'.admin_url().'admin.php?page=text-united-translation%2Fmainsettings.php"</script>';
     exit;
	}else{
	$error['info']=$project;
	}

	}
}

?>
<style>
.link-button { 
     background: none !important;
     border: none !important;
     color: black;
     text-decoration: none;
     cursor: pointer; 
	 font-weight:blod;
}
</style>
<div class="container">
  <div class="row ">

  <div class="col col-sm-12 col-lg-7 col-md-7 mx-auto mt-5">
    <div class="card loginBodyTu" style="max-width:570px;padding: 1.6em 1em 1em;">
      <div class="card-body" style="padding:0px;">
      <h5 class="card-title mx-3" style="margin-bottom:0" ><img src="<?php echo esc_url(plugins_url('img/text_united_logo.png', __FILE__ )); ?>" width="180"/></h5>
	  <hr />
	  <div class="mx-3"><h4 style="color:#5956db">Welcome !</h4>

   <?php if(wp_remote_retrieve_response_code( $wp_delete_post_response ) != 200){ ?>	

   	  <p>To start using this plugin, you'll need to have an active account. Sign up for a <b>14 days free trial</b> below. <span data-toggle="modal" data-target="#exampleModal" style="color:#e9553b;cursor:pointer" class="dashicons dashicons-info mt-1"></span></p>
     <?php
	 if(isset($error['info'])){
        echo '<div class="alert alert-danger" role="alert">'.$error['info'].'</div>';
		}
	  ?>
   
  <form method="post" action="">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputPassword4">First name</label>
      <input type="text" class="form-control" name="name" id="name" required>
	  <div class="invalid-feedback" <?php if(isset($error['name'])){echo 'style="display:block"'; } ?>>
        <?php if(isset($error['name'])){echo $error['name']; } ?>
      </div>
    </div>
	    <div class="form-group col-md-6">
      <label for="inputEmail4">Last name</label>
      <input type="text" class="form-control" name="lname" id="lname" required>
	  	  <div class="invalid-feedback" <?php if(isset($error['lname'])){echo 'style="display:block"'; } ?>>
        <?php if(isset($error['lname'])){echo $error['lname']; } ?>
      </div>
    </div>
  </div>
    <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputPassword4">Choose password</label>
      <input type="password" class="form-control" name="choose" id="choose" required>
	  	  <div class="invalid-feedback" <?php if(isset($error['choose'])){echo 'style="display:block"'; } ?>>
        <?php if(isset($error['choose'])){echo $error['choose']; } ?>
      </div>
    </div>
	    <div class="form-group col-md-6">
      <label for="inputEmail4">Email</label>
      <input type="email" class="form-control" name="email" id="email" required>
	  	  	  <div class="invalid-feedback" <?php if(isset($error['email'])){echo 'style="display:block"'; } ?>>
        <?php if(isset($error['email'])){echo $error['email']; } ?>
      </div>
    </div>
  </div>
    <div class="form-row">
	    <div class="form-group col-md-12 text-center">
		
    <button type="submit" name="TuRegister" class="btn mb-2 pl-5 pr-5 btn-lg bgTu" style="padding-top: 0px !important;margin-top:20px;background-color:#e8e8e8" disabled>Submit</button>
    </div>
  </div>
  </form>
  <div class="mb-4" style="font-size:14px">In case you have an account please enter your <b>Company ID</b> and <b>API key</b> <a href="<?php echo admin_url().'admin.php?page=text-united-translation/importer.php'; ?>"><b style="color:#5ce7c5">here</b></a>.</div>
  <?php } else {?>
 
        <div class="alert alert-success" role="alert"><span style="padding-right:100px">You are connected to Text United</span>
       	
		<span style="position:absolute;right:20px">
		 <form method="post" action="">	
		<input style="background: none !important;padding:0" type="submit" name="disconnect" class="link-button" value="Disconnect" />
		</form>
		</span>
		</div>
         <?php
	   if(empty(get_option( 'TUdefaultLanguage'))){
        echo '<div class="alert alert-danger" role="alert">Source language of the website is not selected. Go to <a href="'.admin_url().'admin.php?page=text-united-translation%2Fimporter.php&module=3&sub=1">settings</a>.</div>';
		}
	  ?>
 
  <p>Manage your translations in <a href="<?php echo admin_url().'edit.php?post_type=page'; ?>">Pages</a> and <a href="<?php echo admin_url().'edit.php'; ?>">Posts</a> or read <a href="<?php echo admin_url().'admin.php?page=text-united-translation%2Ffaq.php'; ?>">How to Use</a></p><br />
  
  <?php } ?>
</div>
      </div> 
    </div>
</div> 
</div>
  
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"  style="border:0px">
        <h5 class="modal-title" id="exampleModalLabel" style="color: #5956db;">Text United Terms Of Use</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-body-tu pt-0 pb-0">
	  <hr class="mt-0" />
        <p>By registering, I agree to the Text United <a href="https://www.textunited.com/terms-and-conditions/" target="_blank" style="color:#5ce7c5">Terms Of Use</a>.</p>

<p>Text United is committed to respecting your privacy, and we’ll only use your personal information to administer your account and to provide the products and services you requested. See our <a href="https://www.textunited.com/privacy-policy/" target="_blank" style="color:#5ce7c5">Privacy Policy</a>.</p>
      </div>
      <div class="modal-footer  pt-0" style="border:0px">
        <button style="background-color:#5CE7C4;border:0" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>
<?php
/* Restore original Post Data */
wp_reset_postdata();

?> 