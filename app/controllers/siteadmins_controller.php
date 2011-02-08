<?php
	class SiteadminsController extends AppController {
		var $uses = array('Menuitem');
		
		public function admin_control_panel() {
			$this->viewPath = 'elements/Sencha';
			$this->render('admin_control_panel', 'empty');
		}
		
		
		public function admin_loadjs($dir, $name = null) {
			$this->viewPath = 'elements/Sencha';
			$this->set('resourse', $name ? "$dir/$name" : "$dir");
			$this->render('admin_loadjs', 'empty');
		}
		
		public function admin_edit_company_data() {
			if (RequestHandlerComponent::isPost()) {
				
			}
			$objDOM = new DOMDocument();
			$objDOM->load('files/company_data.xml');
			
			$company_data = $objDOM->getElementsByTagName('company_data');
			
			foreach($company_data as $value) {
				$title = $value->getElementsByTagName("title");
				$title  = $title->item(0)->nodeValue;
				
				$address = $value->getElementsByTagName("address");
				$address = $address->item(0)->nodeValue;
				
				$city = $value->getElementsByTagName("city");
				$city = $city->item(0)->nodeValue;
				
				$phone = $value->getElementsByTagName("phone");
				$phone  = $phone->item(0)->nodeValue;
				
				$fax = $value->getElementsByTagName("fax");
				$fax  = $fax->item(0)->nodeValue;
				
				$email = $value->getElementsByTagName("email");
				$email = $email->item(0)->nodeValue;
			} 
			echo $title;
			echo $address;
			echo $city;
			echo $phone;
			echo $fax;
			echo $email;
			exit();
		}
	}