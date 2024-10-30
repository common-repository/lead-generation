<?php
/*
Plugin Name: Popups by ActiveConvert
Plugin URI: http://www.activeconvert.com
Description: Beautiful popups and full screen overlays to generate leads and reduce bounce rates. To get started: 1) Click the "Activate" link to the left of this description, 2) Go to your ActiveConvert plugin Settings page, and click Get My API Key.
Version: 1.0.88
Author: ActiveConvert
Author URI: http://www.activeconvert.com/
*/

$aclg_domain = plugins_url();
add_action('init', 'aclg_init');
add_action('admin_notices', 'aclg_notice');
add_filter('plugin_action_links', 'aclg_plugin_actions', 10, 2);
add_action('wp_footer', 'aclg_insert',4);
add_action('admin_footer', 'aclgRedirect');
define('aclg_DASHBOARD_URL', "https://www.activeconvert.com/dashboard.do?wp=true");
define('aclg_SMALL_LOGO',plugin_dir_url( __FILE__ ).'ac-small-white.png');
function aclg_init() {
    if(function_exists('current_user_can') && current_user_can('manage_options')) {
        add_action('admin_menu', 'aclg_add_settings_page');
        add_action('admin_menu', 'aclg_create_menu');
    }
}
function aclg_insert() {

    global $current_user;
    if(strlen(get_option('aclg_widgetID')) == 32 ) {
	echo("\n<!-- Popup WP Plugin by ActiveConvert (www.activeconvert.com) -->\n<script src='//www.activeconvert.com/api/activeconvert.1.0.js#".get_option('aclg_widgetID')."' async='async'></script>\n");
    }
}

function aclg_notice() {
    if(!get_option('aclg_widgetID')) echo('<div class="error" style="padding:10px;"><p><strong><a style="text-decoration:none;border-radius:3px;color:white;padding:10px; ;background:#029dd6;border-color:#06b9fd #029dd6 #029dd6;margin-right:20px;"'.sprintf(__('href="%s">Activate your account</a></strong>  Almost done - activate your ActiveConvert account to convert visitors into customers.' ), admin_url('options-general.php?page=lead-generation')).'</p></div>');
}

function aclg_plugin_actions($links, $file) {
    static $this_plugin;
    $aclg_domain = plugins_url();
    if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if($file == $this_plugin && function_exists('admin_url')) {
        $settings_link = '<a href="'.admin_url('options-general.php?page=lead-generation').'">'.__('Settings', $aclg_domain).'</a>';
        array_unshift($links, $settings_link);
    }
    return($links);
}

function aclg_add_settings_page() {
    function aclg_settings_page() {
        global $aclg_domain ?>
	<div class="wrap">
        <p style="margin-left:4px;font-size:18px;">Popups by <?php wp_nonce_field('update-options') ?>
		<a href="http://www.activeconvert.com/wordpress_lead_generation.jsp" target="_blank" title="ActiveConvert"><?php echo '<img src="'.plugins_url( 'activeconvert.png' , __FILE__ ).'" height="17" style="margin-bottom:-1px;"/>';?></a> helps convert website visitors into customers.</p>
 		
	<div id="aclg_register" class="inside" style="padding: -30px 10px"></p>	
        	<div class="postbox" style="max-width:600px;height:50px;padding:30px;">
            	
		<div style="float:left">	
			<b>Activate ActiveConvert</b> <br>
			<p>Login or sign up now for a free trial.</p>
		</div>
		<div><a href='https://www.activeconvert.com/wordpress_lead_generation.jsp' class="right button button-primary" target="_blank">Get My API Key</a></div>
		</div>

   		<div class="postbox" style="max-width:600px;height:50px;padding:30px;">
            	<div style="float:left">
			<b>Enter Your API Key</b> <br>
			<p>If you already know your API Key.</p>
		</div>
		<div class="">
		<form id="saveSettings" method="post" action="options.php">
                   <?php wp_nonce_field('update-options') ?>
			<div style="float:right">
			<input type="text" name="aclg_widgetID" id="aclg_widgetID" placeholder="Your API Key" value="<?php echo(get_option('aclg_widgetID')) ?>" style="margin-right:10px;" />
                        <input type="hidden" name="page_options" value="aclg_widgetID" />
			<input type="hidden" name="action" value="update" />
                        <input type="submit" class="right button button-primary" name="aclg_submit" id="aclg_submit" value="<?php _e('Save Key', $aclg_domain) ?>" /> 
			</div>
                </form>
		</div>
               
            	
	</div>
	</div>
	

	<div id="aclg_registerComplete" class="inside" style="padding: -20px 10px;display:none;">
	<div class="postbox" style="max-width:600px;height:250px;padding:30px;padding-top:5px">
<h3 class=""><span id="sicp_noAccountSpan">ActiveConvert Settings</span></h3>
		<p>Here are some shortcuts to your ActiveConvert settings. By default your popup is triggered when a visitor is leaving your site.  You can change this to trigger immediately, or after a configurable amount of time.</p>
		<p>
		<div style="text-align:center">
		
		<a href='https://www.activeconvert.com/dashboard.do?wp=true' class="button button-primary" target="_ac">Dashboard</a>&nbsp;
<a href='https://www.activeconvert.com/campaigns.do' class="button button-primary" target="_ac">Campaigns</a>&nbsp;
<a href='https://www.activeconvert.com/sequences.do?wp=true' class="button button-primary" target="_ac">Email Sequences</a>&nbsp;
		
<a href='https://www.activeconvert.com/wppreview.do?wid=<?php echo(get_option('aclg_widgetID')) ?>' class="button button-primary" target="_ac">Popup Preview</a>&nbsp;
		<br><br><a id="changeWidget" class="" target="_blank">Enter Different API Key</a>&nbsp;
		</div>
		</p>* The popup is only triggered once per browser session.  Open a new browser window to test multiple times.

</div>
</div>
<script>
jQuery(document).ready(function($) {

var aclg_wid= $('#aclg_widgetID').val();
if (aclg_wid=='') 
{}
else
{

	$( "#aclg_enterwidget" ).hide();
	$( "#aclg_register" ).hide();
	$( "#aclg_registerComplete" ).show();
	$( "#aclg_noAccountSpan" ).html("ActiveConvert Settings");

}

$(document).on("click", "#aclg_inputSaveSettings", function () {
	$( "#saveDetailSettings" ).submit();
});

$(document).on("click", "#changeWidget", function () {
$( "#aclg_register" ).show();
$( "#aclg_inputSaveSettings" ).hide();
});


});
</script>
<?php }
$aclg_domain = plugins_url();
add_submenu_page('options-general.php', __('ActiveConvert', $aclg_domain), __('ActiveConvert', $aclg_domain), 'manage_options', 'lead-generation', 'aclg_settings_page');
}
function addaclgLink() {
$dir = plugin_dir_path(__FILE__);
include $dir . 'options.php';
}
function aclg_create_menu() {
  $optionPage = add_menu_page('ActiveConvert', 'ActiveConvert', 'administrator', 'aclg_dashboard', 'addaclgLink', plugins_url('ac-small-white.png', __FILE__));
}
function aclgRedirect() {
$redirectUrl = "https://www.activeconvert.com/dashboard.do?wp=true";
echo "<script> jQuery('a[href=\"admin.php?page=aclg_dashboard\"]').attr('href', '".$redirectUrl."').attr('target', '_blank') </script>";}
?>