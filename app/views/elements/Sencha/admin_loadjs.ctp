<?
	$contentType = 'text/javascript';
	$extension = 'jtp';
	
	header("Content-Type: $contentType");
	
	$this->ext = ".$extension";
	echo $this->element("Sencha/$resourse");
	exit();
?>