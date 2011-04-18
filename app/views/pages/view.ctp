<div id="left" class="position_left" >
	<div id="menu" class="menu">
		<div class="content">
		<ul>
		<?php if (!empty($sideMenuitems)) :?>
			<? foreach($sideMenuitems as $sideMenuitem) : ?>
				<? $activeClass = $sideMenuitem['Menuitem']['id'] == $active_main_menuitem; ?>
				<li class="<?=$sideMenuitem['Menuitem']['class'] . ($activeClass ? ' active' : '')?>">
					<a href=<?="/inkoplan{$sideMenuitem['Menuitem'][Menuitem::Url]}"?>><span><?=$sideMenuitem['Menuitem'][Menuitem::Title]?></span></a>
					
					<? if (isset($sideMenuitem[Menuitem::A_Children])) :?>
						<ul>
						<? foreach($sideMenuitem[Menuitem::A_Children] as $children):?>
							<? $activeClass = $children['Menuitem']['id'] == $active_main_menuitem; ?>
							<li class="<?=$children['Menuitem']['class'] . ($activeClass ? ' active' : '')?>">
								<a href=<?="/inkoplan{$children['Menuitem'][Menuitem::Url]}"?>><span><?=$children['Menuitem'][Menuitem::Title]?></span></a>
							</li>
						<? endforeach;?>
						</ul>	
					<? endif; ?>
					
				</li>
			<? endforeach;?>
		<?php endif;?>
		</ul>
</div></div>
</div>
<div class="position_main" id="main">
	<div class="page">
		<div class="contents">
			<h2><?= $page['Page']['title']?></h2>
			<div class="text">
				<?= $page['Page']['content']?>
			</div>
		</div>
	</div>	
</div>
	