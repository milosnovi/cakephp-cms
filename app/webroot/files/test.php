<?php
/*$html = "<p>
	<span style='font-size: 14px;'><img alt='' src='/ckfinder/userfiles/images/transport-of-dangerous-goods-tanker.jpg' style='width: 590px; height: 240px;' /></span></p>
	<span style='font-size: 14px;'>European Agreement<br />
	Concerning the International Carriage of Dangerous Goods by Road<br />
	Evropski sporazum za me<p style='text-align: justify;'>";
	$html_reg = "/<+\s*\/*\s*([A-Z][A-Z0-9]*)\b[^>]*\/*\s*>+/i";
	echo htmlentities( preg_replace( $html_reg, '', $html ) );*/

	echo '<b>milos</b>';
	echo '<br/>';
	
	echo htmlentities('<b>milos</b>eđu!@#$%^&*()_,123465798');
	echo '<br/>';
	
	echo htmlspecialchars('<b>milos</b>eđu!@#$%^&*()_,123465798');
	echo '<br/>';
?>