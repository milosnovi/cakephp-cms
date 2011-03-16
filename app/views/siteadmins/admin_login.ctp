<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?= $this->Html->css('default', null, array('media' => 'screen')) ?>
		<?= $this->Html->css('inkoplan', null, array('media' => 'screen')) ?>
	</head>
 	<body class="bodyLayout">
		
	<h1 style="color: white; font-size: 17px; text-align: center;"><?__("Inkoplan Controlpanel Login")?></h1>
	<div id="login">
		<?=$form->create(SITEADMIN, array("id" =>"siteadmin_login", "method" => "post", "action" => "admin_login"))?>
			<fieldset>
				<div class="wrap">
					<div class="credentials">
						<div id="session_messages">
							<?php echo $session->flash(); ?>
						</div>
						<ul>
							<li>
								<?=$form->label(Siteadmin::Username, __('Username:', true))?>
								<?=$form->text(Siteadmin::Username, array('class' => 'text'))?>
							</li>
							<li>
								<?=$form->label(Siteadmin::Password, __('Password:', true))?>
								<?=$form->password(Siteadmin::Password, array('class' => 'text'))?>
							</li>
							<li class="remember_me">
								<input type="submit" class="button" value="<?__('Login')?>" />
								<?=$form->label('RememberLogin', $form->checkbox(Siteadmin::A_RememberMe). __(' Remember me', true))?>
							</li>
						</ul>
						<?=$form->hidden(Siteadmin::A_T_Redirect, array('value' => '/admin'))?>
					</div>
				</div>
			</fieldset>
	<?=$form->end()?>
	</div>
	</body>
</html>
