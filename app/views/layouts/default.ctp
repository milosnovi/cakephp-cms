<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
		<title><?= $title_for_layout ?></title>
		<?= $this->Html->css('default', null, array('media' => 'screen')) ?>
		<?= $this->Html->css('inkoplan', null, array('media' => 'screen')) ?>

		<!--
			<link rel="stylesheet" type="text/css" href="js/ext-3.3.0/resources/css/ext-all.css" />
			
			<script type="text/javascript" src="js/ext-3.3.0/adapter/ext/ext-base.js"></script>
	        <script type="text/javascript" src="js/ext-3.3.0/ext-all-debug.js"></script>
	        
	        <script type="text/javascript" src="js/JSinkoplan.js"></script>
        -->
        <script type="text/javascript" src="js/jquery-1.4.2.js"></script>
		
		<?//$scripts_for_layout;?>
	</head>
 	<body class="bodyLayout">
 		<div style="border:1px solid red; color: red; font-size: 35px; text-align: center; margin: 10px 0;">This site is still under construction</div>
		<?= $this->element('header') ?>
		<div id="content">
			<?//= $this->element('session_messages') ?>
			<?= $content_for_layout ?>
		</div>
		<?=$this->element('footer', array('company_data' => $company_data)) ?>
		<?=$this->element('sql_dump'); ?>
		<?php if(isset($js)) echo $js->writeBuffer(); ?>
	</body>
</html>