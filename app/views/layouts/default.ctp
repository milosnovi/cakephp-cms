<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?= $title_for_layout ?></title>
		<?= $this->Html->css('default', null, array('media' => 'screen')) ?>
		<?= $this->Html->css('inkoplan', null, array('media' => 'screen')) ?>
		
		<link rel="stylesheet" type="text/css" href="js/ext-3.3.0/resources/css/ext-all.css" />
		
		<script type="text/javascript" src="js/ext-3.3.0/adapter/ext/ext-base.js"></script>
        <script type="text/javascript" src="js/ext-3.3.0/ext-all-debug.js"></script>
        
        <script type="text/javascript" src="js/JSinkoplan.js"></script>
		
		<?=$scripts_for_layout;?>
	</head>
 	<body class="bodyLayout">
		<?= $this->element('header') ?>
		<div id="content">
			<?//= $this->element('session_messages') ?>
			<?= $content_for_layout ?>
		</div>
		<?=$this->element('footer') ?>
		<?= $this->element('sql_dump'); ?>
		<?php if(isset($js)) echo $js->writeBuffer(); ?>
	</body>
</html>