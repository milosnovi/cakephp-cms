<?php
	class UserContact extends AppModel {
		public $useTable = false;
	
		const Name = 'name';
		const Email = 'email';
		const Message = 'message';
		
		const T_Name = 'Usercontact.name';
		const T_Email = 'Usercontact.email';
		const T_Message = 'Usercontact.message';
		
		public $validate = array(
			self::Name => array(
				array(
					'rule' => 'notEmpty',
					'message' => "Name can not be empty"
				)
			),
			self::Email => array(
				array(
					'rule' => 'notEmpty',
					'message' => "Email can not be empty"
				),
				array(
					'rule' => array('email', true),
					'message' => 'Email is not corect'
				)
			),
			self::Message => array(
				array(
					'rule' => 'notEmpty',
					'message' => "Message can\'t be empty"
				)
			)
		);
	}
?>