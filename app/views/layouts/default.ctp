<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta content="ADR, opasne materije, sertifikat, ispit, Procena rizika" name="keywords">
		<meta content="Polaganje ispita i izdavanje ADR sertifikata. Cara Dušana 18000 Niš, tel: 063/774 10 40" name="description">
		
		<? if (isset($metaCanonicalSlug)):?>
			<link rel="canonical" href=<?="http://www.inkoplan.rs/$metaCanonicalSlug"?> />
		<? endif;?>
		<title><?= $title_for_layout ?></title>
		<?= $this->Html->css('default', null, array('media' => 'screen')) ?>
		<?= $this->Html->css('inkoplan', null, array('media' => 'screen')) ?>
        <script type="text/javascript" src="js/jquery-1.4.2.js"></script>
	</head>
	
 	<body class="bodyLayout">
		<?= $this->element('header') ?>
		<div id="content">
			<? if (!empty($sideMenuitems)) :?>
				<div id="left" class="position_left" >
					<div id="menu" class="menu">
						<div class="content">
						<ul>
						<? foreach($sideMenuitems as $sideMenuitem) : ?>
							<? $activeClass = isset($active_main_menuitem) && ($sideMenuitem['Menuitem']['id'] == $active_main_menuitem); ?>
							<li class="<?=$sideMenuitem['Menuitem']['class'] . ($activeClass ? ' active' : '')?>">
								<a href=<?="/inkoplan{$sideMenuitem['Menuitem']['slug_url']}"?>><span><?=$sideMenuitem['Menuitem'][Menuitem::Title]?></span></a>
								<? if (isset($sideMenuitem[Menuitem::A_Children])) :?>
									<ul>
									<? foreach($sideMenuitem[Menuitem::A_Children] as $children):?>
										<? $activeClass = isset($active_main_menuitem) && ($children['Menuitem']['id'] == $active_main_menuitem); ?>
										<li class="<?=$children['Menuitem']['class'] . ($activeClass ? ' active' : '')?>">
											<a href=<?="/inkoplan{$children['Menuitem']['slug_url']}"?>><span><?=$children['Menuitem'][Menuitem::Title]?></span></a>
										</li>
									<? endforeach;?>
									</ul>	
								<? endif; ?>
							</li>
						<? endforeach;?>
						</ul>
						</div>
					</div>
				</div>
			<? endif;?>
			
			<div class='position_main'>
				<?= $content_for_layout ?>
			</div>
			
		</div>
		<?=$this->element('footer', array('company_data' => $company_data)) ?>
		<?=$this->element('sql_dump'); ?>
		<?php if(isset($js)) echo $js->writeBuffer(); ?>
	</body>
</html>