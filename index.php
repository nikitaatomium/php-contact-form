<?php
/*
Plugin Name: Advanced contact widget
Description: A contact form that sends notifications to Telegram and email
Author: Nikita Y.
Version: 0.1
*/

add_action('admin_menu', 'contact_wid_plugin_setup_menu');
add_action( 'wp_ajax_submit_contact_info', 'ajax_contactform_to_callback' );
add_action( 'wp_ajax_nopriv_submit_contact_info', 'ajax_contactform_to_callback' );
add_action( 'wp_ajax_setup_contact_acw', 'ajax_save_contactform_info_acw' );
add_action( 'wp_ajax_setup_status_acw', 'ajax_save_status_info_acw' );
function load_jquery() {
    if ( ! wp_script_is( 'jquery', 'enqueued' )) {
        wp_enqueue_script( 'jquery' );
    }
}
add_action( 'wp_enqueue_scripts', 'load_jquery' );
add_action( 'wp_head' , function() {
$acw_status = get_option( 'acw_statusd' );
if($acw_status=='on'){
    // widget code goes here
echo '<style>
.center {
    margin: auto;
    width: 60%;
    padding: 20px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
.hideform {
    display: none;
}
.contact {height:40px;width:40px}
.contact {animation: pulse 1s infinite;}
@keyframes pulse {
  0% {
   transform: scale(0.9);
  }
  70% {
    transform: scale(1);
    box-shadow: 0 0 0 50px rgba(90, 153, 212, 0);
  }
    100% {
     transform: scale(0.9);
    box-shadow: 0 0 0 0 rgba(90, 153, 212, 0);
  }
}
.contact{border-radius:80%;box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);display:block;width: 60px;height: 60px;padding:10px;position:fixed;bottom:10px;right:10px;-webkit-transform-origin:50% 50%;transform-origin:50% 50%;-webkit-transition:all 240ms ease-in-out;transition:all 240ms ease-in-out;z-index:9999;text-decoration:none;background:#fff;
}
.contact:hover{opacity:1;-webkit-transform:scale(1.0925);
  transform:scale(1.0925);
}
.contact>*{-webkit-transition:inherit;transition:inherit;
}</style>';
    echo '<div class="center hideform">
    <button id="close" style="float: right;">X</button>
    <form action="/action_page.php">
        First name:<br>
        <input type="text" name="firstname" value="Mickey">
        <br>
        Last name:<br>
        <input type="text" name="lastname" value="Mouse">
        <br><br>
        <input type="submit" value="Submit">
    </form>
</div>';
echo '<a id="show" href="javascript:void(0)" class="contact"><img src="https://storage.googleapis.com/stateless-tuneer-app/false/2022/05/f42c8845-phone.png" style="width: 40px;"/></a>';
echo '<script type="text/javascript">
jQuery("#show").click(function () {
    jQuery(".center").show();
    jQuery(this).hide();
})

jQuery("#close").click(function () {
    jQuery(".center").hide();
    jQuery("#show").show();
})
</script>';
  }
}, 10 );



function contact_wid_plugin_setup_menu(){
    add_menu_page( 'Advanced contact widget', 'Popup contact widget', 'manage_options', 'advcontact-widget-plugin', 'contact_wid_settings_init' );
}

function acw_presave_data($acw_option, $acw_value){
  if(!get_option($acw_option)){
        add_option('acw_statusdo' , $acw_value);
    } else {
        update_option('acw_statusdo' , $acw_value);
        
    }
}
 
function contact_wid_settings_init(){
  if ( current_user_can( 'manage_options' ) ) {
  $acwstatusop = '';  
  $admin_email = get_option( 'admin_email' );
  $acw_status = get_option( 'acw_statusdo' );
  if($acw_status=='on'){
    $acwstatusop = 'checked';
  } 
  echo '<style>';
  echo '
body ul {
  list-style-type: none;
}
body ul li {
  position: relative;
  margin-bottom: 1em;
}
body h1 {
  line-height: 0.99em;
}
body .toggles {
  padding-left: 7.5px;
}
body .toggles span {
  font-size: 1.4em;
  display: inline-block;
  margin-left: 60px;
  color: #FF512F;
  margin-top: -5px;
}
body .acw_input_ {
      border-radius: 0;
    border: 0;
    border-bottom: 1px solid #a0a0a0;
    margin-bottom: 0;
    height: 30px;
    line-height: 30px;
    border-width: 0 0 1px;
    font-size: 18px;
    color: #656565;
    margin-top: 15px;
    font-weight: 300;
}
body .toggles .switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 28px;
  margin-top: 10px;
}
body .toggles .switch input {
  display: none;
}
body #submit {
  font-size:15px;
}
body .toggles .slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
}
body .toggles .slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: 0.4s;
}
body .spacer {
  padding: 10px;
}
body .toggles input:checked + .slider {
  background-color: #FF512F;
}
body .toggles input:focus + .slider {
  box-shadow: 0 0 1px #FF512F;
}
body .toggles input:checked + .slider:before {
  transform: translateX(22px);
}
body .toggles .slider.round {
  border-radius: 34px;
}
body .toggles .slider.round:before {
  border-radius: 50%;
}
body .acw_desc_ {
  display: inline-block;
    margin-bottom: 5px;
    font-weight: 400;
    line-height: 2em;
    text-transform: uppercase;
    letter-spacing: .05em;
    font-size: 11px;
    font-family: "Montserrat","Helvetica Neue",Helvetica,Arial,sans-serif;
}
  ';
  echo '</style>';
    echo '
    <form id="landingForm" method="POST" style="
    margin: 20px 20px 0px 0;
    padding: 40px;
    background-color: #fff;
    border-radius: 25px;
">
<h1>Welcome to Advanced Contact Widget (ACW) setup!</h1>
<div class="toggles">
<label class="switch">
  <input type="checkbox" id="acwType" name="acwType" class="acwType" '.$acwstatusop.'>
  <div class="slider round"></div>
  <span>Status</span></label>
  </div>
<input id="telegram_id" type="number" name="telegram_id" class="acw_input_" placeholder="Telegram ID">
<label for="telegram_id" class="acw_desc_"><font color="red">*</font> Telegram id</label></br>  
<input id="email_id" type="email" name="email_id" class="acw_input_" placeholder="Your email" value="'.$admin_email.'"> <label for="email_id" class="acw_desc_"><font color="red">*</font> Place your email here</label> </br>  
<div class="spacer"></div>  
<a id="submit" style="color: #fff; background-color: #777; border-radius:25px;     padding: 12px;" onclick="return acw_save_settings();">SAVE</a>
  </form>
 <script type="text/javascript">
var ajaxurl = "'. admin_url('admin-ajax.php') .'";
function acw_save_settings(){
  var acwform = jQuery("#landingForm")[0];
    jQuery.ajax({
        url: ajaxurl,
        data: {
            "action" : "setup_contact_acw",
        },
        type: "POST",  
        success: function(acwres) {
          alert(acwres); 
      }
    })
  }  
jQuery(".acwType").click(function() {
       if(this.checked){
           jQuery.ajax({
            url: ajaxurl,
            data: {
            "action" : "setup_status_acw",
            "acwstatus" : "acwon",
        },
          type: "POST",  
          success: function(acwres) {
             alert(acwres); 
       }
     })
    } else {
      // not checked
      jQuery.ajax({
            url: ajaxurl,
            data: {
            "action" : "setup_status_acw",
            "acwstatus" : "acwoff",
        },
          type: "POST",  
          success: function(acwres) {
             alert(acwres); 
       }
     })
    }
 });
</script>';
  }
}

function acw_send_telegram_notification($message){
   $url = "https://api.telegram.org/".$botid;
    $url = $url . "&text=" . urlencode($message);
    $ch = curl_init();
    $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function ajax_save_contactform_info_acw(){
  if ( current_user_can( 'manage_options' ) ) {
   echo 'Saved';
 } else {
  echo 'Not Saved';
 }
 die();
}

function ajax_save_status_info_acw(){
  if ( current_user_can( 'manage_options' ) ) {
     $status = $_POST['acwstatus'];
      switch ($status) {
        case 'acwon':
         // Status is on
          acw_presave_data('acw_statusd', 'on');
        break;
        case 'acwoff':
         // Status is off
         acw_presave_data('acw_statusd', 'off'); 
        break;
    }
    echo get_option( 'acw_statusdo' );
 } else {
  echo 'Status updated';
 }
 die();
}

function ajax_contactform_to_callback() {
  $error = '';
  $status = 'error';
  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
    $error = 'All fields are required to enter.';
  } else {
    if (!empty($_POST['email2'])) {
      $error = 'Verification error, try again.';
    } else {
      $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
      $email = $_POST['email'];
      $subject = 'A messsage from sunexpo.asia:';
      $message = stripslashes($_POST['message']);
      $message .= PHP_EOL.PHP_EOL.'IP address: '.$_SERVER['REMOTE_ADDR'];
      $message .= PHP_EOL.'Sender\'s name: '.$name;
      $message .= PHP_EOL.'Contact details: '.$email;
      $notification = telegram_notification($message);
      $error = 'Thanks, for the message. We will respond as soon as possible.';
      $status = 'Ok';
    }
  }
  $resp = array('status' => $status, 'errmessage' => $error);
  header( "Content-Type: application/json" );
  echo json_encode($resp);
  die();
}

 
?>
