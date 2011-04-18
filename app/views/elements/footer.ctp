<div class="footer">
	<h2 style="margin: 0px !important; padding: 0px; font-size: 16px;">
		<?=$company_data['Title']?>
	</h2>
	<?php if(!empty($company_data['Address'])):?>
		<span class="street-address"><?=$company_data['Address']?></span><br>
	<?php endif;?>	
	<?php if(!empty($company_data['City'])):?>
		<span class="postal-code"><?=$company_data['City']?></span><br/>
	<?php endif;?>	
	<?php if(!empty($company_data['Phone1'])):?>
		Telefon: <?=$company_data['Phone1']?><br/>
	<?php endif;?>
	
	<?php if(!empty($company_data['Fax'])):?>
		Fax: <?=$company_data['Fax']?><br/>
	<?php endif;?>
		
	<?php if(!empty($company_data['Email'])):?>
		Email: <?=$company_data['Email']?><br/>
	<?php endif;?>
</div>