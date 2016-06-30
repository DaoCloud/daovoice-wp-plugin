<?php
/*
Plugin Name: DAOVOICE
Plugin URI: https://wordpress.org/plugins/daovoice
Description: Official <a href="http://www.daocloud.io/cloud/voice.html">DaoVoice</a> support for WordPress.
Author: Wenter
Author URI: http://www.daocloud.io/cloud/voice.html
Version: 0.1
 */
function daovoice_js(){
	$app_id = getenv("DAOVOICE_APP_ID");
	$str = <<<EOT
<script>(function(i,s,o,g,r,a,m){i["DaoVoiceObject"]=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,"script","//widget.daovoice.io/widget/{$app_id}.js","daovoice");</script>
<script>
  daovoice('init', {
    app_id: "$app_id",
  });
  daovoice('update');
</script>
EOT;
	echo $str;
}

if (getenv("DAOVOICE_APP_ID")!=null){
	add_action("wp_footer","daovoice_js");
       
}


