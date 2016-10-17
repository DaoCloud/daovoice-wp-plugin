<?php
/*
Plugin Name: DAOVOICE
Plugin URI: https://wordpress.org/plugins/daovoice
Description: Official <a href="http://www.daocloud.io/cloud/voice.html">DaoVoice</a> support for WordPress.
Author: Wenter
Author URI: http://www.daocloud.io/cloud/voice.html
Version: 0.2
 */


function daovoice_setting_menu() {
	add_menu_page('DaoVoice Settings', 'DaoVoice', 'administrator', __FILE__, 'daovoice_settings_page' , plugins_url('/images/icon.png', __FILE__) );
	add_action( 'admin_init', 'register_daovoice_plugin_settings' );
}

function register_daovoice_plugin_settings() {
	register_setting( 'daovoice-widget-settings-group', 'app_id' );
	register_setting( 'daovoice-widget-settings-group', 'app_secert' );
	register_setting( 'daovoice-widget-settings-group', 'is_ensure_user_id' );
}

function daovoice_settings_page(){
?>
<div class="wrap">
<h1>DaoVoice</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'daovoice-widget-settings-group' ); ?>
    <?php do_settings_sections( 'daovoice-widget-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">DaoVoice APP ID</th>
        <td>
        <input type="text" name="app_id" value="<?php echo esc_attr( get_option('app_id') ); ?>" />
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">APP Secert</th>
        <td>
        <input type="text" name="app_secert" value="<?php echo get_option('app_secert') ?>" />
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">强制启用聊天窗口认证</th>
        <td>
        <select name="is_ensure_user_id">
        	<option value="1" <?php if (get_option('is_ensure_user_id') == '1'){echo 'selected="selected"';} else {echo '';} ?>>是</option>
        	<option value="0" <?php if (get_option('is_ensure_user_id') == '0'){echo 'selected="selected"';} else {echo '';} ?>>否</option>
        </select>
        </td>
        </tr>
         
    </table>
    
    <?php submit_button(); ?>
    </form>
    </div>
<?php }

function daovoice_js(){
	$wp_user = wp_get_current_user();
	$app_id = esc_attr( get_option('app_id') );

	if(empty($app_id)){
		return '';
	}

	$user_id = $wp_user->user_login;

	$daovoiceSetting = [
		"app_id" => $app_id
	];

	if ( get_option('is_ensure_user_id') ){
		$daovoiceSetting['user_id'] = $user_id;
		$daovoiceSetting['secure_digest'] = hash_hmac("sha1", $user_id, get_option('app_secert'));
	}

	if(!empty($wp_user)) {
		if (!empty($wp_user->user_email))
		{
		  $daovoiceSetting["email"] = $wp_user->user_email;
		}
		if (!empty($wp_user->display_name))
		{
		  $daovoiceSetting["name"] = $wp_user->display_name;
		}
	}

	$daovoiceSettingJson = json_encode($daovoiceSetting);

	$str = <<<HTML
<script>(function(i,s,o,g,r,a,m){i["DaoVoiceObject"]=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,"script","//widget.daovoice.io/widget/{$app_id}.js","daovoice");</script>
<script>
  daovoice('init', $daovoiceSettingJson);
  daovoice('update');
</script>
HTML;
	echo $str;
}
add_action("wp_footer","daovoice_js");

add_action('admin_menu', 'daovoice_setting_menu');
