<style>
#content form {
    width: 1000px;
}
#content form input.text{
    border: 1px solid #E4E4E4;
    color: #4D4D4D;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 13px;
    height: 20px;
    padding: 5px 10px;
    width: 333px;
    float: left;
}
#content .search form .submit {
    margin: 3px 0 0 5px;
}

#content fieldset {
    clear: both;
    float: left;
    width: 100%;
}

#content form .submit input {
    border: 1px solid black;
    float: left;
    font-family: Arial,Helvetica,sans-serif;
    font-weight: bold;
    height: 30px;
    margin-left: 10px;
    padding: 4px;
    text-transform: uppercase;
}

#content h2, h3{
    color: #1F6FA6;
    font-family: Georgia,serif;
    font-size: 18px;
    font-weight: normal;
    line-height: 1.2;
    margin-bottom: 0;
}

#session_messages .message {
    color: red;
    margin-top: 5px;
    text-align: left;
}
</style>

<? $search_term = !empty($search_term) ? $search_term : '';?>
<form class="search_form" method="post" action="/inkoplan/search"">
	<fieldset>
		<input type="text" class="text search-string autofocus" id="SearchTxt" value="<?=$search_term?>" name="data[Site][SearchTxt]">
		<div class="submit" style="float: left"> 
			<input type="submit" class="button" value="Pretraga">
		</div>
	</fieldset>
</form>
<div id="session_messages" style="text-align: left;">
	<?=$session->flash(); ?>
</div>
<? if(!empty($finalSearchArray)):?>
	<h2><?__('REZULTAT PRETRAGE:');?></h2>
	<div class="result">
		<?if (0 < count($finalSearchArray)) :?>
			<h3><?=count($finalSearchArray)?> <?__('stranica sadrže frazu koju tražite:');?></h3>
				<? foreach ($finalSearchArray as $result) :?>
					<ul>
						<li><a href='/inkoplan<?=$result["link"]?>'> <?=$result['title']?></a><br>
							<span class="excerpt"><?=$result['matched_string']?>...</span>
						</li>
					</ul>
				<? endforeach;?>
		<? else: ?>
			<?__('Ni jedna stranica ne sadrži željenu frazu.')?>		
		<? endif;?>
	</div>
<? endif;?>
