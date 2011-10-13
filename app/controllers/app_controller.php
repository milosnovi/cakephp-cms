<?php
	App::import('Model', 'Menuitem');
	
	class AppController extends Controller{
		var $uses = array('Menuitem');
		
		var $helpers = array('Html', 'Form', 'Javascript', 'Ajax', 'Session');
		var $components = array('Session', 'RequestHandler', 'Cookie', 'Email');
		
		function beforeFilter() {
			/*
			 *Znaci da slug za dati url ne postoji.  
			 *Probaj da za kombinaciju kontroler+akcija+id nadjes main slug i ako postoji redirektuj na tu adresu
			 * */
			if (empty($this->params[SLUG])) {
				$controller = $this->name;
				$action = empty($this->params['action']) ? false : $this->params['action'];
				$pass = empty($this->params['pass']) ? false : $this->params['pass'];
				if ($controller && $action) {
					$fk = (is_array($pass) && (0 < count($pass))) ? array_shift($pass) : null;

					$matchedSlug = Slug::getSlugsPerRawUrl($controller, $action, $fk);
					if ($matchedSlug) {
						// First array value is empty because of starting slash. Cleaned $pass from empty params
						$this->redirect301(Slug::parseSlugUrl($matchedSlug[SLUG][Slug::Url]));
					}
				}
			} else {
				/*
			 	* Nadjen je slug u tabeli za dati url
			 	* ako je canonical setuj parametar za metacanonical tagove
			 	*/
				if (Slug::TypeCanonical == $this->params[SLUG][Slug::Type]) {
					$url = Slug::parseSlugUrl($this->params[Slug::F_MainSlug][Slug::Url]);
					$this->set('metaCanonicalSlug', $url);
				}
			}
		}
		
		function beforeRender() {
			$mainMenuId = $this->Menuitem->getRootNodeId(Menuitem::TypeMain);
			$menuitemsData = $this->Menuitem->getMenuChildren($mainMenuId);
			$this->set('menuitemsData', $menuitemsData);

			if (!in_array($this->action, array('home', 'contact'))) {
				$sideMenuId = $this->Menuitem->getRootNodeId(Menuitem::TypeSide);
				if (!empty($sideMenuId)) {
					$finalSideMenuitems = $this->Menuitem->getMenuChildren($sideMenuId);
					$this->set('sideMenuitems', $finalSideMenuitems);
				}
			}
			$xmlObj = simplexml_load_string(file_get_contents('files/company_data.xml')); // convert string of XML into an object
			$arrXml = objectsIntoArray($xmlObj);
			$this->set('company_data', $arrXml); 
		}
		
		public function redirect301($url) {
			$this->redirect("/$url", 301);
		}
		
		public function returnJsonData($data) {
			Configure::write('debug', 0);
			if (RequestHandlerComponent::isAjax()) {
				header('Content-Type: text/javascript; charset="utf-8"');
			}
			echo json_encode($data); 
			exit(0);
		} 
	}