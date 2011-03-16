<script type="text/javascript">	
	var currentPosition = 1;
	var myTimer;
	function changeHeaderImage(pos) {
		$('#1').animate({
		    'marginLeft' : pos
		}, 'slow');
	}
	function swapHeaderImage() {
		
		if (1 == currentPosition) {
			currentPosition = 2;
			movement = '-=931px';
		} else if (2 == currentPosition) {
			currentPosition = 3;
			movement = '-=931px';
		}else if (3 == currentPosition) {
			currentPosition = 1;
			movement = '+=1862px';
		}
		changeHeaderImage(movement);
	}
	
	function restartTimer(timer) {
		clearInterval(myTimer);
		myTimer = setInterval("swapHeaderImage()", 5000);
	}

	$(document).ready(function() {
		myTimer = setInterval("swapHeaderImage()", 5000);
		
		$('#first').click(function(e,t){
			changeHeaderImage("-=931px");
			currentPosition = 2; // current position after change a image
			restartTimer(myTimer);
		});
		
		$('#second').click(function(e,t){
			changeHeaderImage("-=931px");
			currentPosition = 3;
			restartTimer(myTimer);
		});
		
		$('#third').click(function(e,t){
			changeHeaderImage("+=1862px");
			currentPosition = 1;
			restartTimer(myTimer);
		});
		
	});
</script>
 <style type="text/css">
	div.flash ul {
	    height: 350px;
	    list-style-type: none;
	    margin: 0;
	    padding: 0;
	    position: relative;
		list-style-type: none;
		width: 2598px;
		margin: auto;
	}
	
	div.flash ul li {
		display: block;
		height: 100%;
		position: relative;
		width: 100%;
	}
</style>
<div class="position_main" id="main">
<div class="page">
	<div class="contents" >
		<div class="flash" style="width:931px; height: 350px; overflow: hidden; position: relative;">
				<ul>
				<div id="1" style="width: 2793px; position: relative;">
					<div id="first" style="width: 931px; height: 350px; position:absolute; left: 0px">
						<li style="background-image: url(/inkoplan/img/1.jpg);"></li>
					</div>
					<div id="second" style="width: 931px; height: 350px; position:absolute; left: 931px">
						<li style="background-image: url(/inkoplan/img/2.jpg);"></li>
					</div>
					<div id="third" style="width: 931px; height: 350px; position:absolute; left: 1862px;">
						<li style="background-image: url(/inkoplan/img/3.jpg);"></li>
					</div>
				
				</div>
			</ul>
			
		</div>			
			<div class="top-blocks">
				<div class="block">
					<h2><a title="ADR" href="" style="text-decoration: none;color:#1F6FA6;">ADR</a></h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nec risus nec lacus malesuada rutrum quis at ante. In aliquet, enim eu tincidunt mollis, nibh tortor aliquam nisl, et volutpat turpis lorem non odio. Etiam bibendum, ante eu laoreet egestas, nisi nisi egestas tellus, et rhoncus nisl arcu id sapien. Quisque sollicitudin justo vitae purus congue eleifend. Duis dapibus odio id augue venenatis luctus. Praesent accumsan cursus risus id fermentum.<br><br>
					<a href="/inkoplan/pages/view/1">Read detail...</a></p>
				</div>
				
				<div class="block">
					<h2><a title="Procena rizika" href="" style="text-decoration: none;color:#1F6FA6;">Procena rizika</a></h2> 
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nec risus nec lacus malesuada rutrum quis at ante. In aliquet, enim eu tincidunt mollis, nibh tortor aliquam nisl, et volutpat turpis lorem non odio. Etiam bibendum, ante eu laoreet egestas, nisi nisi egestas tellus, et rhoncus nisl arcu id sapien. Quisque sollicitudin justo vitae purus congue eleifend. Duis dapibus odio id augue venenatis luctus. Praesent accumsan cursus risus id fermentum. <br><br>
					<a href="/inkoplan/pages/view/2">Read detail...</a></p></p>
				</div>
			</div>
		</div>
	</div>
</div>
