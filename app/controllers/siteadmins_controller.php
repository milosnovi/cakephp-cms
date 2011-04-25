<?php
	class SiteadminsController extends AppController {
		var $uses = array('Siteadmin', 'Menuitem');
		
		public function admin_control_panel() {
			if (!$this->Session->read('siteadmin')) {
				$this->redirect('/admin/siteadmins/login', 401);
				exit();
			} 
			$this->viewPath = 'elements/Sencha';
			$this->render('admin_control_panel', 'empty');
		}
		
		public function admin_login() {
			$cookiesData = $this->Cookie->read('Siteadmin.login');
			if ($cookiesData) {
				$siteadmin = $this->Siteadmin->find('all', array(
					'conditions' => array(
						Siteadmin::T_Username => $cookiesData[Siteadmin::T_Username],
						Siteadmin::T_Password => md5($cookiesData[Siteadmin::T_Password])
					) 
				));
				if (1 == count($siteadmin) && $this->Session->read('siteadmin')) { // cookie is OK
					$this->redirect('/admin');
					exit();
				} else {
					$this->Session->delete('siteadmin');
				}
			}
			if (isset($this->data)) {
				$siteadmin = $this->Siteadmin->find('all', array(
					'conditions' => array(
						Siteadmin::T_Username => $this->data[SITEADMIN][Siteadmin::Username],
						Siteadmin::T_Password => md5($this->data[SITEADMIN][Siteadmin::Password])
					) 
				));
				if (!$siteadmin || (1 < count($siteadmin))) {
					$success = false;
					$message = $this->Session->setFlash('Wrong username or password. Try again');
				} else if (1 == count($siteadmin)) {
					$siteadmin = $siteadmin[0];
					$this->Session->write('siteadmin', $siteadmin);
					if ($this->data[SITEADMIN][Siteadmin::A_RememberMe]) {
						$cookie = array(
							Siteadmin::T_Username => $this->data[SITEADMIN][Siteadmin::Username],
							Siteadmin::T_Password => $this->data[SITEADMIN][Siteadmin::Password]
						);
						$this->Cookie->write('Siteadmin.login', $cookie, false);
					}
					$this->redirect("{$this->data[SITEADMIN][Siteadmin::A_Redirect]}");
					exit();
				}
			}
			//$this->viewPath = 'siteadmins';
			echo $this->render('admin_login', 'empty');
			exit(0);
		}
		
		public function admin_loadjs($dir, $name = null) {
			$this->viewPath = 'elements/Sencha';
			$this->set('resourse', $name ? "$dir/$name" : "$dir");
			$this->render('admin_loadjs', 'empty');
		}
		
		public function admin_edit_company_data() {
			$xmlUrl = 'files/company_data.xml';
			
			if ($this->RequestHandler->isPost()) {
				//create the xml document
				$xmlDoc = new DOMDocument();
				
				//create the root element
				$root = $xmlDoc->appendChild($xmlDoc->createElement("company_data"));
				//create a tutorial element
				$tutTag = $root->appendChild($xmlDoc->createElement("Title", $this->data['CompanyData']['Title']));
				$tutTag = $root->appendChild($xmlDoc->createElement("Address", $this->data['CompanyData']['Address']));
				$tutTag = $root->appendChild($xmlDoc->createElement("City", $this->data['CompanyData']['City']));
				$tutTag = $root->appendChild($xmlDoc->createElement("Phone1", $this->data['CompanyData']['Phone1']));
				$tutTag = $root->appendChild($xmlDoc->createElement("Fax", $this->data['CompanyData']['Fax']));
				$tutTag = $root->appendChild($xmlDoc->createElement("Email", $this->data['CompanyData']['Email']));
				           
				header("Content-Type: text/plain");
				//make the output pretty
				$xmlDoc->formatOutput = true;
				
				$fh = fopen($xmlUrl, 'w');
				fwrite($fh, $xmlDoc->saveXML());
				fclose($fh);
			}
			
			$xmlStr = file_get_contents($xmlUrl);
			
			$xmlObj = simplexml_load_string($xmlStr); // convert string of XML into an object
			$arrXml = objectsIntoArray($xmlObj);
			$this->returnJsonData(array(
				'success' => true,
				'company_data' => $arrXml,
			)); 
		}
}