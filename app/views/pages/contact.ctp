<div class="contact">
<h1>Contact us</h1>
<div class="position_main" id="main">
	<p>
	Fill out the form below to send us a message. Fields marked with <span style="color: red">*</span> are required.<br/>
	One of our staff will contact you.
	</p>
	<form action="" method="post">
		<fieldset style="display: none;">
			<input type="hidden" value="POST" name="_method">
		</fieldset>	
		<fieldset>
			<ul>
				<li>
					<label for="UsercontactName">Name/Company:<span class="required">*</span></label>
					<input type="text" id="UsercontactName" class="text autofocus" value="" name="data[Usercontact][name]">
				</li>
				
				<li>
					<label for="UsercontactEmail">Your email:<span class="required">*</span></label>
					<input type="text" id="UsercontactEmail" class="text" value="" name="data[Usercontact][email]">
				</li>
			</ul>
		</fieldset>
		<fieldset>
			<legend><label for="UsercontactMessage">Message:<span class="required">*</span></label></legend>
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
</div></div>