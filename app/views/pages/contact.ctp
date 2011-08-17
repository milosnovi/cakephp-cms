<div class="contact">
<h1><?__('Contact us')?></h1>
<div class="contact_content" >
	<p>
	<?__('Fill out the form below to send us a message. Fields marked with <span style="color: red">*</span> are required.<br/>One of our staff will contact you.')?>
	</p>
	<form id="contact_form" action="" method="post">
		<div id="session_messages" style="text-align: left;">
			<?php echo $session->flash(); ?>
		</div>
		<fieldset style="display: none;">
			<input type="hidden" value="POST" name="_method">
		</fieldset>	
		<fieldset>
			<ul>
				<li>
					<label for="UsercontactName"><?__('Name/Company:')?><span class="required">*</span></label>
					<input type="text" id="UsercontactName" class="text autofocus" value="" name="data[Usercontact][name]">
				</li>
				
				<li>
					<label for="UsercontactEmail"><?__('Your email:')?><span class="required">*</span></label>
					<input type="text" id="UsercontactEmail" class="text" value="" name="data[Usercontact][email]">
				</li>
			</ul>
		</fieldset>
		<fieldset>
			<legend><label for="UsercontactMessage"><?__('Message:')?><span class="required">*</span></label></legend>
			<textarea id="UsercontactMessage" rows="10" cols="50" name="data[Usercontact][message]"></textarea>		
		</fieldset>
		<p>
			<label class="checkbox" for="UsercontactSendCopy">
				<input type="hidden" value="0" id="UsercontactSendCopy_" name="data[Usercontact][send_copy]">
				<input type="checkbox" id="UsercontactSendCopy" value="1" tabindex="3" name="data[Usercontact][send_copy]">
				Send me copy
			</label>
		</p>
		
		<div class="submit">
			<input type="submit" value="Send" class="button primary_button">
		</div>
		
		<!--<div>
			<input type="text" id="UsercontactMessage2" class="contact_message2" value="" name="data[Usercontact][message2]">
		</div>
		<div class="webfront_form_confirm" id="userContactHashCheck"></div>
		-->
	</form>
	<iframe width="415" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.rs/maps/ms?hl=sr&amp;gl=rs&amp;ie=UTF8&amp;oe=UTF8&amp;msa=0&amp;msid=117626630494957520572.00049300b85048d5c1eb7&amp;ll=43.319531,21.906824&amp;spn=0,0&amp;iwloc=00049300b8536aac11806&amp;output=embed"></iframe>
</div></div>