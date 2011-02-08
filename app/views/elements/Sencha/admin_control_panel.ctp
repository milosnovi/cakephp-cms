<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
		<title><?//= $title_for_layout ?></title>
		
		<script type="text/javascript" src="http://localhost/inkoplan/app/webroot/js/tinymce/jscripts/tiny_mce/tiny_mce_src.js"></script>
				
		<link rel="stylesheet" type="text/css" href="http://localhost/inkoplan/js/ext-3.3.0/resources/css/ext-all.css" />
		<link rel="stylesheet" type="text/css" href="http://localhost/inkoplan/css/cp_ext.css" />
		
		<script type="text/javascript" src="http://localhost/inkoplan/js/ext-3.3.0/adapter/ext/ext-base.js"></script>
        <script type="text/javascript" src="http://localhost/inkoplan/js/ext-3.3.0/ext-all-debug.js"></script>
		
	</head>
 	<body>
 	
	<div id="top">
		<h2>INKOPLAN ADMIN PANEL</h2>
 		<?php echo $html->link(__('VIEW YOUR SITE', true), '/', array('target' => '_blank', 'style' => 'color: black'));?>
	</div>

	<script type="text/javascript" src="http://localhost/inkoplan/admin/siteadmins/loadjs/overrides.js"></script>		
	<script type="text/javascript" src="http://localhost/inkoplan/admin/siteadmins/loadjs/layout/layout.js"></script>		
	<script type="text/javascript" src="http://localhost/inkoplan/admin/siteadmins/loadjs/pages/pages.js"></script>		
	<script type="text/javascript" src="http://localhost/inkoplan/admin/siteadmins/loadjs/siteadmins/siteadmins.js"></script>		
 	<script type="text/javascript">
 	
	Ext.BLANK_IMAGE_URL = 'http://localhost/inkoplan/js/ext-3.3.0/resources/images/default/s.gif';
        Ext.onReady(function() {
        	CP.Layout.init();
        });
	</script>
	</body>
</html>