<?php
/*
* Plugin Name: Maintenance Activator Elementor
* Author: Yash
* Author URI: https://www.yashomparkash.com/
* Description: This plugin help users to turn on and off maintenance mode of elementor from thier dashboard.
* Version: 1.0.0
* License: GPL2
* License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

//If this file is called directly, abort.
if (!defined( 'WPINC' )) {
    die;
}

//Define Constants
if ( !defined('WPAC_PLUGIN_VERSION')) {
    define('WPAC_PLUGIN_VERSION', '1.0.0');
}
if ( !defined('WPAC_PLUGIN_DIR')) {
    define('WPAC_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
}

//Include Scripts & Styles
if( !function_exists('wpac_plugin_scripts')) {
    function wpac_plugin_scripts() {
        wp_enqueue_style('dmin_css_foo', WPAC_PLUGIN_DIR. 'assets/css/style.css');
    }
    add_action('admin_enqueue_scripts', 'wpac_plugin_scripts');
}
//Settings Menu & Page
require plugin_dir_path( __FILE__ ). 'inc/settings.php';
//Elementor Maintenance Section Code Start here
 //calling fontawesme library for button icon
 function fontawesome_Maintenance_elementor() {
   wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', '', '5.8.1', 'all');
}
//adding css for off/on buttons please change the css according to your website
add_action('admin_init', 'fontawesome_Maintenance_elementor'); 

//Elementor Maintenance Registering Widget on wordpress dashboard
add_action('wp_dashboard_setup', 'Elementor_Maintenance_dashboard_widget',9999);

function Elementor_Maintenance_dashboard_widget() {
	global $wp_meta_boxes;
	 
	wp_add_dashboard_widget('Elementor_Maintenance_Switch_Section', 'Elementor Maintenance Mode ', 'Elementor_Maintenance_Section');
}
function Elementor_Maintenance_Section() {
    if (  current_user_can( 'manage_options' ) ) {
    global $wpdb;
    $products_table = $wpdb->prefix . 'options';
    $ID = get_option('wpac_like_btn_label');
    if(empty($ID))
    {
       return; 
    }
	//Buttons Code Start Here
    if(isset($_POST["btn_turn_on"])){
	  if(wp_verify_nonce('MAE_Mode','Maintenance_Button_Mode')){
		 echo 'Sorry Something want wrong please try again';  
	  }
	  else{
        global $wpdb;
		//calling Maintenance Table from database and updating user selected value This code work when user click turn on button 
        $wpdb->query( $wpdb->prepare("UPDATE $products_table SET option_value = %s WHERE option_id = %s",'coming_soon',$ID));
		//id at the end will replace according to website database table elementor_maintenance_mode_mode id
    ?>
    <script type="text/javascript">
    location.reload();
    </script>
    <?php
     }
    }
    if(isset($_POST["btn_turn_off"])){
		if(wp_verify_nonce('MAE_Mode','Maintenance_Button_Mode')){
		 echo 'Sorry Something want wrong please try again';  
	  }
	  else{
        global $wpdb;
		//calling Maintenance Table from database and updating user selected value This code work when user click turn off button 
        $wpdb->query( $wpdb->prepare("UPDATE $products_table SET option_value = %s WHERE option_id = %s",'',$ID));
		//id at the end will replace according to website database table elementor_maintenance_mode_mode id
	 
     ?>
    <script type="text/javascript">
    location.reload();
    </script>
    <?php
    } }
//Buttons Code End Here
	global $current_user;
	$username = $current_user->user_login;
    echo '<form method="post">';
    wp_nonce_field( 'Maintenance_Button_Mode', 'MAE_Mode' );
    global $wpdb;
	$current_status = $wpdb->get_var("SELECT option_value from $products_table WHERE option_id =$ID");
    if($current_status!='')
    {
        echo '<center><b><h2 style="color:green;">Maintenance Mode is Active</h2></b></center>';
        $on=1;
    }
    else if($current_status=='')
    {
        echo '<center><b><h2 style="color:red;">Maintenance Mode is disbale</h2></b></center>';
        $on=0;
    }
if($on==0){
   echo '<center><button type="submit" class="maintenance-elementor-btn-on" name="btn_turn_on" id="btn_turn_on">&#xF011;</button></center>';
}
else{
   echo '<center><button type="submit" class="maintenance-elementor-btn-off" name="btn_turn_off" id="btn_turn_off">&#xF011;</button></center>';
}
    echo '</form>';
    
    
//Buttons Code End Here  
}
}
//Elementor Maintenance Section Code End here


