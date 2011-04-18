<div id="header">
	<div id="top_left" class="position_top_left">
		<div class="logo">
			<a href="http://localhost/inkoplan">
			<img style="height: 80px;border: none;padding:10px 0 0 10px;" src="http://localhost/inkoplan/img/logo.png"></a>
		</div>
	</div>
	<div id="top" class="position_top">
		<div id="menu" class="menu">
			<div class="content">
			<ul>	
				<?php foreach ($menuitemsData as $menuitem) : ?>
					<?php $active_main_menuitem = !empty($active_main_menuitem) ? $active_main_menuitem : false?>
					<? $activeClass = $menuitem['Menuitem']['id'] == $active_main_menuitem; ?>
					<li class="<?=$menuitem['Menuitem']['class'] . ($activeClass ? ' active' : '')?>">
						<a title="<?=$menuitem['Menuitem']['title']?>" href="/inkoplan<?=$menuitem['Menuitem']['url']?>"><span><?=$menuitem['Menuitem']['title']?></span></a>
					</li>
				<?php endforeach; ?>
			</ul>				
			</div>
		</div>	
	</div>
	<div id="top_right" class="position_top_right">
		<div class="searchbox">
			<h2>Search</h2>
			<div class="content">
				<form action="" method="post">
					<div class="search_input">
						<input type="text" class="text search-string" id="SearchBoxTxt" name="data[Site][SearchTxt]">
					</div>
					<div class="search_button">
						<input type="submit" class="button" value="Search">
					</div>
				</form>
			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>