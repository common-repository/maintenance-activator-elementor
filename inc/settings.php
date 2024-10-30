<?php
function wpac_settings_page_html() {
    //Check if current user have admin access.
    if(!is_admin()) {
        return;
    }
    ?>
        <div class="wrap">
            <h1 style="padding:10px; background:#333;color:#fff">Elementor Maintenance Mode ON/OFF</h1>
            <form action="options.php" method="post">
                <?php 
                    // output security fields for the registered setting "wpac-settings"
                    settings_fields( 'wpac-settings' );

                    // output setting sections and their fields
                    // (sections are registered for "wpac-settings", each field is registered to a specific section)
                    do_settings_sections( 'wpac-settings' );

                    // output save settings button
                    submit_button( 'Save Changes' );
                ?>
            </form>
        </div>
    <?php

}

//Top Level Administration Menu
function wpac_register_menu_page() {
    add_menu_page( 'Maintenace Mode Setting', 'Maintenace Mode', 'manage_options', 'wpac-settings', 'wpac_settings_page_html', 'dashicons-admin-generic', 30 );
}
add_action('admin_menu', 'wpac_register_menu_page');

//Sub-Level Administration Menu
/* function wpac_register_menu_page() {
    add_theme_page( 'WPAC Like System', 'WPAC Settings', 'manage_options', 'wpac-settings', 'wpac_settings_page_html', 30 );
}
add_action('admin_menu', 'wpac_register_menu_page'); */
function wpac_plugin_settings_section_cb(){
    //echo '<p>Define Button Labels</p>';
}
// Register settings, sections & fields.
function wpac_plugin_settings(){

    // register 2 new settings for "wpac-settings" page
    register_setting( 'wpac-settings', 'wpac_like_btn_label' );
   

    // register a new section in the "wpac-setings" page
    add_settings_section( 'wpac_label_settings_section', 'Elementor Maintenance Setting', 'wpac_plugin_settings_section_cb', 'wpac-settings' );

    // register a new field in the "wpac-settings" page
    add_settings_field( 'wpac_like_label_field', 'Enter Database Table ID', 'wpac_like_label_field_cb', 'wpac-settings', 'wpac_label_settings_section' );

}
add_action('admin_init', 'wpac_plugin_settings');



// Field callback function
function wpac_like_label_field_cb(){ 
    // get the value of the setting we've registered with register_setting()
    global $wpdb;
    $products_table = $wpdb->prefix . 'options';
     $ID2 = $wpdb->get_var("SELECT option_id FROM $products_table WHERE option_name='elementor_maintenance_mode_mode'");
    if(!empty($ID2)){
        echo '<p>Please Enter this ID <b>'.$ID2.'</b> below and click on save changes This id generate automatically</p>';
    }
    else{ ?>
        <b><p>Looking like you had not select template and enable it first time for make this plugin work please create template from <a href="<?php echo admin_url(); ?>admin.php?page=elementor-tools#tab-maintenance_mode">here</a></p></b>
    <?php }
  $setting = get_option('wpac_like_btn_label');
    // output the field
    ?>
    <input type="text" name="wpac_like_btn_label" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
    <?php

}

