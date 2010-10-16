<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?= $title_for_layout ?></title>
		<?= $this->Html->css('default', null, array('media' => 'screen')) ?>
		<?= $this->Html->css('jquery-ui-1.8.1.custom') ?>
		<?= $this->Html->script('jquery-1.4.2') ?>
		<?= $this->Html->script('jquery-ui-1.8.1.custom.min') ?>
		<?= $this->Html->script('jquery.form') ?>
		<?= $scripts_for_layout ?>
	</head>
 	<body>
		<?= $this->element('header') ?>
		<div id="content">
			<?// = $this->element('session_messages') ?>
			<?= $content_for_layout ?>
		</div>
		<?=$this->element('footer') ?>
		<?= $this->element('sql_dump'); ?>
		<?php if(isset($js)) echo $js->writeBuffer(); ?>
	</body>
</html>